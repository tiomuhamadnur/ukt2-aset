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
    public function index_pengiriman()
    {
        $seksi_id = auth()->user()->struktur->seksi->id;
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
                            ->orderBy('tanggal_kirim', 'DESC')
                            ->get();

        $pulau = Pulau::all();
        $kontrak = Kontrak::where('seksi_id', $seksi_id)->get();

        return view('user.aset.kasi.pengiriman.index', compact(['pengiriman_barang', 'pulau', 'kontrak']));
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




    // KOORDINATOR
    public function index_penerimaan()
    {
        $this_year = Carbon::now()->format('Y');
        $pulau_id = auth()->user()->area->pulau->id;
        $seksi_id = auth()->user()->struktur->seksi->id;
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

            return view('user.aset.koordinator.penerimaan.index', compact(['penerimaan_barang']));
    }

    public function show_penerimaan($no_resi)
    {
        $pengiriman_barang = PengirimanBarang::where('no_resi', $no_resi)->get();
        $validasiBAST = PengirimanBarang::where('no_resi', $no_resi)->where('status', 'Dikirim')->count();
        $nomor_resi = $no_resi;
        return view('user.aset.koordinator.penerimaan.detail_penerimaan', compact(['pengiriman_barang', 'validasiBAST', 'nomor_resi']));
    }

    public function terima_barang(Request $request)
    {
        $request->validate([
            'ids.*' => 'required',
        ]);

        $ids = $request->ids;
        $tanggal_terima = Carbon::now();
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
