<?php

namespace App\Http\Controllers\superadmin;

use App\Models\User;
use App\Models\Seksi;
use App\Models\Barang;
use App\Models\Gudang;
use App\Models\Jabatan;
use App\Models\FormasiTim;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PengirimanBarang;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ShippingController extends Controller
{
    // SUPERADMIN
    public function index_pengiriman(Request $request, string $uuid)
    {
        $seksi   = Seksi::where('uuid', $uuid)->firstOrFail();
        $seksi_id = $seksi->id;

        $perPage = $request->perPage ?? 50;
        $sort    = $request->sort ?? 'DESC';

        $pengiriman_barang = PengirimanBarang::select(
            'no_resi',
            'submitter_id',
            'receiver_id',
            'gudang_id',
            'tanggal_kirim',
            'tanggal_terima',
            'catatan',
            'status'
        )
            ->whereRelation('barang.kontrak', 'seksi_id', '=', $seksi_id)
            ->distinct()
            ->orderBy('tanggal_kirim', $sort)
            ->paginate($perPage)
            ->appends($request->all());

        $gudang_pulau = Gudang::orderBy('name', 'ASC')->get();
        $gudang_id    = '';
        $status       = '';
        $start_date   = '';
        $end_date     = '';

        return view('superadmin.shipping.index', [
            'pengiriman_barang' => $pengiriman_barang,
            'gudang_pulau'      => $gudang_pulau,
            'sort'              => $sort,
            'gudang_id'         => $gudang_id,
            'status'            => $status,
            'start_date'        => $start_date,
            'end_date'          => $end_date,
            'seksi'             => $seksi,
        ]);
    }


    public function create_pengiriman(Request $request)
    {
        $request->validate([
            'barang_id' => 'array|required'
        ]);

        $barangAll = $request->barang_id;

        $barang = Barang::with('kontrak.seksi')
            ->whereIn('id', $barangAll)
            ->where('stock_aktual', '>', 0)
            ->get();

        if ($barang->isEmpty()) {
            return back()->withError('Tidak ada barang valid yang dipilih.');
        }

        $seksiAll = $barang->pluck('kontrak.seksi_id')->filter()->unique();
        if ($seksiAll->count() > 1) {
            return back()->withError('Semua barang yang dipilih harus berasal dari seksi yang sama.');
        }

        $seksi_id = $seksiAll->first();
        $seksi = Seksi::find($seksi_id);

        $submitter = User::where('jabatan_id', 2)
            ->whereHas('struktur', function ($q) use ($seksi_id) {
                $q->where('seksi_id', $seksi_id);
            })
            ->first();

        if (! $submitter) {
            return back()->withError('Belum ada Kepala Seksi (jabatan_id = 2) untuk seksi ini. Silakan tambahkan terlebih dahulu.');
        }

        $gudang = Gudang::all();

        return view('superadmin.shipping.form_kirim', compact(
            'barang',
            'gudang',
            'seksi',
            'submitter'
        ));
    }

    public function store_pengiriman(Request $request, string $uuid)
    {
        $request->validate([
            'photo.*'       => 'required|image|max:2048',
            'submitter_id'  => 'required|exists:users,id',
            'tanggal_kirim' => 'required|date',
            'gudang_id'     => 'required|exists:gudang,id',
            'barang_id.*'   => 'required|exists:barang,id',
            'qty.*'         => 'required|numeric|min:1',
        ]);

        $submitter_id  = $request->submitter_id;
        $tanggal_kirim = $request->tanggal_kirim;
        $gudang_id     = $request->gudang_id;
        $barang_ids    = $request->barang_id;
        $qty           = $request->qty;
        $photos        = $request->file('photo');
        $catatan       = $request->catatan;
        $no_resi       = $this->generateNoResi();

        // jalankan transaksi
        DB::transaction(function () use ($barang_ids, $qty, $photos, $submitter_id, $tanggal_kirim, $gudang_id, $catatan, $no_resi) {
            foreach ($barang_ids as $key => $barang_id) {
                $file = $photos[$key];
                $image = Image::make($file);

                $imageName = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
                $detailPath = 'asset/photo_pengiriman/';
                $destinationPath = storage_path('app/public/' . $detailPath);

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }

                $image->resize(null, 500, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $image->save($destinationPath . $imageName);
                $photo_kirim = $detailPath . $imageName;

                PengirimanBarang::create([
                    'no_resi'       => $no_resi,
                    'submitter_id'  => $submitter_id,
                    'gudang_id'     => $gudang_id,
                    'barang_id'     => $barang_id,
                    'qty'           => $qty[$key],
                    'photo_kirim'   => $photo_kirim,
                    'tanggal_kirim' => $tanggal_kirim,
                    'catatan'       => $catatan,
                    'status'        => 'Dikirim',
                ]);

                $barang = Barang::findOrFail($barang_id);
                $barang->decrement('stock_aktual', $qty[$key]);
            }
        });

        $seksi = Seksi::where('uuid', $uuid)->firstOrFail();

        return redirect()->route('admin.pengiriman.index', $seksi->uuid)
            ->withNotify('Data pengiriman barang berhasil disimpan');
    }


    public function show_pengiriman($no_resi)
    {
        $pengiriman_barang = PengirimanBarang::where('no_resi', $no_resi)->get();
        $validasiBAST = PengirimanBarang::where('no_resi', $no_resi)->where('status', 'Dikirim')->count();
        $nomor_resi = $no_resi;

        $seksi = optional($pengiriman_barang->first()->barang->kontrak->seksi);

        return view('superadmin.shipping.detail_shipping', compact([
            'pengiriman_barang',
            'validasiBAST',
            'nomor_resi',
            'seksi',
        ]));
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

        $pdf = Pdf::loadView('superadmin.shipping.bast', [
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

    public function photoTerima(Request $request, $id)
    {
        $request->validate([
            'tanggal_terima' => 'required|date',
            'photo'   => 'nullable|array|max:3',
            'photo.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $pengirimanBarang = PengirimanBarang::with('barang.kontrak', 'gudang')->findOrFail($id);

        $seksi_id   = $pengirimanBarang->barang->kontrak->seksi_id;
        $pulau_id   = $pengirimanBarang->gudang->pulau_id;

        $koordinator = FormasiTim::whereHas('struktur', function ($q) use ($seksi_id) {
            $q->where('seksi_id', $seksi_id);
        })
            ->whereHas('area', function ($q) use ($pulau_id) {
                $q->where('pulau_id', $pulau_id);
            })
            ->first();

        if (!$koordinator) {
            return back()->withError('Koordinator tidak ditemukan.');
        }

        $lampiranPaths = [];

        if ($request->hasFile('photo')) {
            if ($pengirimanBarang->photo_terima) {
                foreach (json_decode($pengirimanBarang->photo_terima, true) as $oldPhoto) {
                    Storage::disk('public')->delete($oldPhoto);
                }
            }

            foreach ($request->file('photo') as $file) {
                $imageName = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('asset/photo_penerimaan', $imageName, 'public');
                $lampiranPaths[] = $path;
            }
        }

        $pengirimanBarang->update([
            'photo_terima'   => json_encode($lampiranPaths),
            'status'         => 'Diterima',
            'tanggal_terima' => $request->tanggal_terima,
            'receiver_id'    => $koordinator->koordinator_id,
        ]);

        return back()->withNotify('Data photo penerimaan barang berhasil disimpan');
    }
}
