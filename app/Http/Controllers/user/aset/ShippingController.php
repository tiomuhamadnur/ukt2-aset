<?php

namespace App\Http\Controllers\user\aset;

use App\Models\Pulau;
use App\Models\Seksi;
use App\Models\Barang;
use App\Models\Gudang;
use App\Models\Kontrak;
use App\Models\FormasiTim;
use App\Models\BarangPulau;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PengirimanBarang;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ShippingController extends Controller
{
    // KASI
    public function index_pengiriman(Request $request)
    {
        $seksi_id = auth()->user()->struktur->seksi->id;
        $perPage = $request->perPage ?? 50;
        $sort = 'DESC';
        $pengiriman_barang = PengirimanBarang::select(
                            'no_resi',
                            'submitter_id',
                            'receiver_id',
                            'gudang_id',
                            'tanggal_kirim',
                            'tanggal_terima',
                            'catatan',
                            'status')
                            ->whereRelation('barang.kontrak', 'seksi_id', '=', $seksi_id)
                            ->distinct()
                            ->orderBy('tanggal_kirim', $sort)
                            ->paginate($perPage);

        $gudang_pulau = Gudang::orderBy('name', 'ASC')->get();
        $gudang_id = '';
        $status = '';
        $start_date = '';
        $end_date = '';

        return view('user.aset.kasi.pengiriman.index', [
            'pengiriman_barang' => $pengiriman_barang,
            'gudang_pulau' => $gudang_pulau,
            'sort' => $sort,
            'gudang_id' => $gudang_id,
            'status' => $status,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
    }

    public function create_pengiriman(Request $request)
    {
        $request->validate([
            'barang_id' => 'array|required'
        ]);

        $barang_id = $request->barang_id;

        $barang = Barang::whereIn('id', $barang_id)->where('stock_aktual', '>', 0)->get();

        $gudang = Gudang::all();
        $seksi = Seksi::all();
        return view('user.aset.kasi.pengiriman.form_kirim', compact([
            'barang',
            'gudang',
            'seksi',
        ]));
    }

    public function store_pengiriman(Request $request)
    {
        $request->validate([
            'photo.*' => 'required|image',
            'submitter_id' => 'required',
            'tanggal_kirim' => 'required',
            'gudang_id' => 'required',
            'barang_id.*' => 'required',
            'qty.*' => 'required|numeric',
        ]);

        $submitter_id = $request->submitter_id;
        $tanggal_kirim = $request->tanggal_kirim;
        $gudang_id = $request->gudang_id;
        $barang_ids = $request->barang_id;
        $qty = $request->qty;
        $photo = $request->file('photo');
        $catatan = $request->catatan;
        $no_resi = $this->generateNoResi();

        foreach ($barang_ids as $key => $barang_id) {
            $image = Image::make($photo[$key]);

            $imageName = time() . '-' . $photo[$key]->getClientOriginalName();
            $detailPath = 'asset/photo_pengiriman/';
            $destinationPath = public_path('storage/'. $detailPath);

            $image->resize(null, 500, function ($constraint) {
                $constraint->aspectRatio();
            });

            $image->save($destinationPath.$imageName);
            $photo_kirim = $detailPath.$imageName;

            PengirimanBarang::create([
                'no_resi' => $no_resi,
                'submitter_id' => $submitter_id,
                'gudang_id' => $gudang_id,
                'barang_id' => $barang_id,
                'qty' => $qty[$key],
                'photo_kirim' => $photo_kirim,
                'tanggal_kirim' => $tanggal_kirim,
                'catatan' => $catatan,
                'status' => 'Dikirim',
            ]);

            $barang = Barang::findOrFail($barang_id);
            $barang->update([
                'stock_aktual' => $barang->stock_aktual - $qty[$key],
            ]);
        }

        return redirect()->route('aset.pengiriman.index')->withNotify('Data pengiriman barang berhasil disimpan');
    }

    public function show_pengiriman($no_resi)
    {
        $pengiriman_barang = PengirimanBarang::where('no_resi', $no_resi)->get();
        $validasiBAST = PengirimanBarang::where('no_resi', $no_resi)->where('status', 'Dikirim')->count();
        $nomor_resi = $no_resi;
        return view('user.aset.kasi.pengiriman.detail_pengiriman', compact(['pengiriman_barang', 'validasiBAST', 'nomor_resi']));
    }

    public function generateBAST(Request $request)
    {
        $request->validate([
            'no_resi' => 'required'
        ]);

        $no_resi = $request->no_resi;
        $pengirimanBarang = PengirimanBarang::where('no_resi', $no_resi)->get();
        $dataPengiriman = PengirimanBarang::where('no_resi', $no_resi)->firstOrFail();
        $hari = Carbon::parse($dataPengiriman->tanggal_terima)->isoFormat('dddd');
        $tanggal = Carbon::parse($dataPengiriman->tanggal_terima)->isoFormat('D');
        $bulan = Carbon::parse($dataPengiriman->tanggal_terima)->isoFormat('MMMM');
        $tahun = Carbon::parse($dataPengiriman->tanggal_terima)->isoFormat('Y');

        $pdf = Pdf::loadView('pages.barang.transaksi.export.bast', [
            'pengirimanBarang' => $pengirimanBarang,
            'dataPengiriman' => $dataPengiriman,
            'hari' => $hari,
            'tanggal' => $tanggal,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]);

        return $pdf->stream(Carbon::now()->format('Ymd_') . 'Surat BAST.pdf');
    }

    public function generateNoResi()
    {
        $timestamp = now()->format('YmdHis');
        $randomNumber = mt_rand(1000, 9999);

        $no_resi = $timestamp . $randomNumber;

        return $no_resi;
    }

    public function filter_pengiriman(Request $request)
    {
        $seksi_id = auth()->user()->struktur->seksi->id;
        $gudang_id = $request->gudang_id;
        $status = $request->status;
        $start_date = $request->start_date;
        $end_date = $request->end_date ?? $start_date;
        $sort = $request->sort;

        $pengiriman_barang = PengirimanBarang::query();

        // Filter by seksi_id
        $pengiriman_barang->whereRelation('barang.kontrak', 'seksi_id', '=', $seksi_id);

        // Filter by gudang_id
        $pengiriman_barang->when($gudang_id, function ($query) use ($request) {
            return $query->where('gudang_id', $request->gudang_id);
        });

        // Filter by status
        $pengiriman_barang->when($status, function ($query) use ($request) {
            return $query->where('status', $request->status);
        });

        // Filter by tanggal
        if ($start_date != null and $end_date != null) {
            $pengiriman_barang->whereBetween('tanggal_kirim', [$start_date, $end_date]);
        }

        $pengiriman_barang->select(
            'no_resi',
            'submitter_id',
            'receiver_id',
            'gudang_id',
            'tanggal_kirim',
            'tanggal_terima',
            'catatan',
            'status',
        );

        // Order By
        $pengiriman_barang = $pengiriman_barang->distinct()
                        ->orderBy('tanggal_kirim', $sort)
                        ->paginate();

        $gudang_pulau = Gudang::orderBy('name', 'ASC')->get();

        return view('user.aset.kasi.pengiriman.index', [
            'pengiriman_barang' => $pengiriman_barang,
            'gudang_pulau' => $gudang_pulau,
            'sort' => $sort,
            'gudang_id' => $gudang_id,
            'status' => $status,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
    }




    // KOORDINATOR
    public function index_penerimaan(Request $request)
    {
        $pulau_id = $request->pulau_id;
        $this_year = Carbon::now()->year;
        $user_id = auth()->user()->id;
        $pulau_ids = FormasiTim::where('periode', $this_year)
                            ->where('koordinator_id', $user_id)
                            ->join('area', 'formasi_tim.area_id', '=', 'area.id')
                            ->pluck('area.pulau_id')
                            ->toArray();
        $pulau = Pulau::whereIn('id', $pulau_ids)->get();
        $seksi_id = auth()->user()->struktur->seksi->id;
        $nama_pulau = Pulau::find($pulau_id)->name ?? '#';

        $penerimaan_barang = PengirimanBarang::select(
                            'no_resi',
                            'submitter_id',
                            'receiver_id',
                            'gudang_id',
                            'tanggal_kirim',
                            'tanggal_terima',
                            'catatan',
                            'status')
                            ->whereRelation('barang.kontrak.seksi', 'id', '=', $seksi_id)
                            ->whereRelation('gudang.pulau', 'id', '=', $pulau_id)
                            ->distinct()
                            ->orderBy('tanggal_kirim', 'DESC')
                            ->get();

            return view('user.aset.koordinator.penerimaan.index', compact([
                'penerimaan_barang',
                'nama_pulau',
                'pulau',
                'pulau_id',
            ]));
    }

    public function show_penerimaan($no_resi)
    {
        $pengiriman_barang = PengirimanBarang::where('no_resi', $no_resi)->get();
        $validasiBAST = PengirimanBarang::where('no_resi', $no_resi)->where('status', 'Dikirim')->count();
        $validasiCheckbox = PengirimanBarang::where('no_resi', $no_resi)->where('status', 'Dikirim')->where('photo_terima', '!=', null)->count();
        $nomor_resi = $no_resi;
        return view('user.aset.koordinator.penerimaan.detail_penerimaan', compact([
            'pengiriman_barang',
            'validasiBAST',
            'validasiCheckbox',
            'nomor_resi',
        ]));
    }

    public function terima_barang(Request $request)
    {
        $request->validate([
            'ids.*' => 'required',
            'tanggal_terima' => 'required',
        ]);

        $ids = $request->ids;
        $tanggal_terima = $request->tanggal_terima;
        $gudang = '';

        foreach ($ids as $key => $id) {
            $pengiriman_barang = PengirimanBarang::findOrFail($id);

            $pengiriman_barang->update([
                'receiver_id' => auth()->user()->id,
                'tanggal_terima' => $tanggal_terima,
                'status' => 'Diterima',
            ]);

            BarangPulau::create([
                'barang_id' => $pengiriman_barang->barang->id,
                'gudang_id' => $pengiriman_barang->gudang->id,
                'stock_awal' => $pengiriman_barang->qty,
                'stock_aktual' => $pengiriman_barang->qty,
                'tanggal_terima' => $tanggal_terima,
                'no_resi' => $pengiriman_barang->no_resi,
            ]);
            $gudang = $pengiriman_barang->gudang->name;
        }

        return back()->withNotify('Data pengiriman barang berhasil diterima di ' . $gudang);
    }
}
