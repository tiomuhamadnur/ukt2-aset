<?php

namespace App\Http\Controllers\user\aset;

use App\Models\Seksi;
use App\Models\Barang;
use App\Models\Gudang;
use App\Models\Kontrak;
use Illuminate\Http\Request;
use App\Imports\BarangImport;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\BarangPulau;
use App\Models\FormasiTim;
use App\Models\TransaksiBarangPulau;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;

class GudangBarangController extends Controller
{
    // KASI
    public function kasi_index()
    {
        $seksi_id = auth()->user()->struktur->seksi->id;
        $barang = Barang::whereRelation('kontrak.seksi', 'id', '=', $seksi_id)
                        // ->where('stock_aktual', '>', 0)
                        // ->whereRelation('kontrak.seksi', 'id', '=', $seksi_id)
                        ->orderBy('stock_aktual', 'DESC')
                        ->orderBy('kontrak_id', 'ASC')
                        ->orderBy('name', 'ASC')
                        ->get();

        $sort = 'DESC';

        $gudang_tujuan = Gudang::orderBy('name', 'ASC')->get();
        $kontrak = Kontrak::where('seksi_id', $seksi_id)->orderBy('tanggal', $sort)->get();
        $tahun = Carbon::now()->format('Y');

        $jenis = '';
        $kontrak_id = '';
        $stock = '';

        $validasiKirimBarangCheckbox = Barang::where('stock_aktual', '>', 0)
                                        ->whereRelation('kontrak.seksi', 'id', '=', $seksi_id)
                                        ->count();

        return view('user.aset.kasi.gudang.index', [
            'barang' => $barang,
            'gudang_tujuan' => $gudang_tujuan,
            'kontrak' => $kontrak,
            'tahun' => $tahun,
            'jenis' => $jenis,
            'stock' => $stock,
            'sort' => $sort,
            'kontrak_id' => $kontrak_id,
            'validasiKirimBarangCheckbox' => $validasiKirimBarangCheckbox,
        ]);
    }

    public function kasi_create()
    {
        $seksi_id = auth()->user()->struktur->seksi->id;
        $kontrak = Kontrak::where('seksi_id', $seksi_id)->orderBy('tanggal', 'DESC')->get();
        $kontrak->map(function ($item, $key) {
            $item->periode = Carbon::parse($item->tanggal)->format('Y');
            return $item;
        });

        return view('user.aset.kasi.gudang.create', compact(['kontrak']));
    }

    public function kasi_store(Request $request)
    {
        $request->validate(
            [
                'kontrak_id' => 'required',
                'file' => 'required|file|mimes:xlsx,xls',
            ],
            [
                'file.mimes' => 'file harus dalam format .xlsx/.xls',
            ],
        );

        $kontrak_id = $request->kontrak_id;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            Excel::import(new BarangImport($kontrak_id), $file);
        }

        return redirect()->route('aset.gudang-utama')->withNotify('Data berhasil di-import!');
    }

    public function kasi_edit($uuid)
    {
        $barang = Barang::where('uuid', $uuid)->firstOrFail();

        return view('user.aset.kasi.gudang.edit', compact(['barang']));
    }

    public function kasi_update(Request $request, $id)
    {
        $request->validate([
            'photo.*' => 'required|file|image',
            'photo' => 'max:3',
        ], [
            'photo.max' => '*Photo yang dilampirkan maksimal 3.'
        ]);

        $barang = Barang::findOrFail($id);

        $barang->update([
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'merk' => $request->input('merk'),
            'jenis' => $request->input('jenis'),
            'stock_awal' => $request->input('stock_awal'),
            'stock_aktual' => $request->input('stock_aktual'),
            'satuan' => $request->input('satuan'),
            'harga' => $request->input('harga'),
            'spesifikasi' => $request->input('spesifikasi'),
        ]);

        if ($request->hasFile('photo'))
        {
            if($barang->photo != null)
            {
                foreach(json_decode($barang->photo) as $photo)
                {
                    Storage::delete($photo);
                }
            }

            $lampiranPaths = [];
            $detailPath = 'asset/photo_awal/';
            foreach ($request->file('photo') as $file) {
                $image = Image::make($file);

                $imageName = time().'-'.$file->getClientOriginalName();
                $destinationPath = public_path('storage/'. $detailPath);

                $image->resize(null, 500, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $image->save($destinationPath.$imageName);
                $lampiranPaths[] = $detailPath . $imageName;
            }

            $barang->photo = json_encode($lampiranPaths);
            $barang->save();
        }

        return redirect()->route('aset.gudang-utama')->withNotify('Data berhasil diubah!');
    }

    public function kasi_filter(Request $request)
    {
        $seksi_id = auth()->user()->struktur->seksi->id;
        $kontrak_id = $request->kontrak_id;
        $sort = $request->sort;
        $tahun = $request->periode ?? Carbon::now()->year;
        $jenis = $request->jenis;
        $stock = $request->stock;

        $barang = Barang::query();

        // Filter seksi_id
        $barang->whereRelation('kontrak.seksi', 'id', '=', $seksi_id);

        // Filter by kontrak_id
        $barang->when($kontrak_id, function ($query) use ($request) {
            return $query->whereRelation('kontrak', 'id', '=', $request->kontrak_id);
        });

        // Filter by periode
        $barang->when($tahun, function ($query) use ($tahun) {
            return $query->whereHas('kontrak', function ($query) use ($tahun) {
                $query->whereYear('tanggal', $tahun);
            });
        });

        // Filter by jenis
        $barang->when($jenis, function ($query) use ($request) {
            return $query->where('jenis', $request->jenis);
        });

        // Filter by stock
        $barang->when($stock, function ($query) use ($request) {
            return $query->where('stock_aktual', $request->stock, 0);
        });

        // Order By
        $barang = $barang->orderBy('name', $sort)->get();

        $gudang_tujuan = Gudang::orderBy('name', 'ASC')->get();
        $kontrak = Kontrak::where('seksi_id', $seksi_id)->orderBy('tanggal', $sort)->get();

        return view('user.aset.kasi.gudang.index', [
            'barang' => $barang,
            'gudang_tujuan' => $gudang_tujuan,
            'kontrak' => $kontrak,
            'tahun' => $tahun,
            'jenis' => $jenis,
            'stock' => $stock,
            'sort' => $sort,
            'kontrak_id' => $kontrak_id,
        ]);
    }

    public function kasi_gudang_pulau(Request $request)
    {
        $gudang_id = '';
        $kontrak_id = '';
        $stock = '';
        $jenis = '';
        $tahun = Carbon::now()->year;

        $seksi_id = auth()->user()->struktur->seksi->id;
        $gudang_pulau = Gudang::orderBy('name', 'ASC')->get();
        $kontrak = Kontrak::where('seksi_id', $seksi_id)->orderBy('tanggal', 'ASC')->get();

        $barang_pulau = BarangPulau::where('id', 'xxx')->get();

        return view('user.aset.kasi.gudang.gudang_pulau', [
            'barang_pulau' => $barang_pulau,
            'gudang_pulau' => $gudang_pulau,
            'kontrak' => $kontrak,
            'tahun' => $tahun,
            'jenis' => $jenis,
            'stock' => $stock,
            'kontrak_id' => $kontrak_id,
            'gudang_id' => $gudang_id,
        ]);
    }

    public function kasi_gudang_pulau_filter(Request $request)
    {
        $gudang_id = $request->gudang_id;
        $kontrak_id = $request->kontrak_id;
        $stock = $request->stock;
        $jenis = $request->jenis;
        $tahun = $request->periode ?? Carbon::now()->year;

        $seksi_id = auth()->user()->struktur->seksi->id;
        $gudang_pulau = Gudang::orderBy('name', 'ASC')->get();
        $kontrak = Kontrak::where('seksi_id', $seksi_id)->orderBy('tanggal', 'ASC')->get();

        $barang_pulau = BarangPulau::query();

        // Filter by seksi_id
        $barang_pulau->whereRelation('barang.kontrak.seksi', 'id', '=', $seksi_id);

        // Filter by gudang_id
        $barang_pulau->when($gudang_id, function ($query) use ($request) {
            return $query->where('gudang_id', $request->gudang_id);
        });

        // Filter by stock
        $barang_pulau->when($stock, function ($query) use ($request) {
            return $query->where('stock_aktual', $request->stock, 0);
        });

        // Filter by kontrak_id
        $barang_pulau->when($kontrak_id, function ($query) use ($request) {
            return $query->whereRelation('barang.kontrak', 'id', '=', $request->kontrak_id);
        });

        // Filter by jenis
        $barang_pulau->when($jenis, function ($query) use ($request) {
            return $query->whereRelation('barang', 'jenis', '=', $request->jenis);
        });

        // Filter by periode
        $barang_pulau->when($tahun, function ($query) use ($tahun) {
            return $query->whereHas('barang.kontrak', function ($query) use ($tahun) {
                $query->whereYear('tanggal', $tahun);
            });
        });

        $barang_pulau = $barang_pulau->orderBy('barang_id', 'ASC')->orderBy('tanggal_terima', 'ASC')->get();

        return view('user.aset.kasi.gudang.gudang_pulau', [
            'barang_pulau' => $barang_pulau,
            'gudang_pulau' => $gudang_pulau,
            'kontrak' => $kontrak,
            'tahun' => $tahun,
            'jenis' => $jenis,
            'stock' => $stock,
            'kontrak_id' => $kontrak_id,
            'gudang_id' => $gudang_id,
        ]);
    }

    public function kasi_gudang_pulau_trans()
    {
        $seksi_id = auth()->user()->struktur->seksi->id;
        $transaksi = TransaksiBarangPulau::whereRelation('barang_pulau.barang.kontrak.seksi', 'id', '=', $seksi_id)
                    ->orderBy('tanggal', 'DESC')
                    ->get();

        return view('user.aset.kasi.gudang.transaksi_pulau', compact([
            'transaksi'
        ]));
    }

    public function kasi_destroy(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $id = $request->id;
        $barang = Barang::findOrFail($id);
        if($barang->photo != null)
        {
            foreach(json_decode($barang->photo) as $photo)
            {
                Storage::delete($photo);
            }
        }
        $barang->forceDelete();
        return redirect()->route('aset.gudang-utama')->withNotify('Data berhasil dihapus!');
    }







    // KOORDINATOR
    public function koordinator_index()
    {
        $user_id = auth()->user()->id;
        $formasi_tim = FormasiTim::where('periode', Carbon::now()->year)
                                ->where('anggota_id', $user_id)
                                ->orWhere('koordinator_id', $user_id)
                                ->firstOrFail();

        $pulau_id = $formasi_tim->area->pulau->id;
        $seksi_id = $formasi_tim->struktur->seksi->id;

        $barang_pulau = BarangPulau::where('stock_aktual', '>', 0)
                        ->whereRelation('gudang.pulau', 'id', '=', $pulau_id)
                        ->whereRelation('barang.kontrak.seksi', 'id', '=', $seksi_id)
                        ->get();

        return view('user.aset.koordinator.gudang.my_gudang', compact([
            'formasi_tim',
            'barang_pulau',
        ]));
    }

    public function koordinator_form_pemakaian(Request $request)
    {
        $barang_pulau_id = $request->barang_pulau_id;

        $barang_pulau = BarangPulau::whereIn('id', $barang_pulau_id)->get();

        return view('user.aset.koordinator.gudang.form_pemakaian', compact([
            'barang_pulau',
        ]));
    }

    public function koordinator_store(Request $request)
    {
        $request->validate([
            'photo.*' => 'required|image',
            'tanggal' => 'required',
            'barang_pulau_id.*' => 'required',
            'kegiatan' => 'required',
            'qty.*' => 'required|numeric',
        ]);

        $user_id = auth()->user()->id;
        $tanggal = $request->tanggal;
        $barang_pulau_ids = $request->barang_pulau_id;
        $qty = $request->qty;
        $photo = $request->file('photo');
        $kegiatan = $request->kegiatan;
        $catatan = $request->catatan;

        foreach ($barang_pulau_ids as $key => $barang_pulau_id) {
            $image = Image::make($photo[$key]);

            $imageName = time() . '-' . $photo[$key]->getClientOriginalName();
            $detailPath = 'asset/photo_transaksi/';
            $destinationPath = public_path('storage/'. $detailPath);

            $image->resize(null, 500, function ($constraint) {
                $constraint->aspectRatio();
            });

            $image->save($destinationPath.$imageName);
            $photo_transaksi = $detailPath.$imageName;

            TransaksiBarangPulau::create([
                'user_id' => $user_id,
                'barang_pulau_id' => $barang_pulau_id,
                'qty' => $qty[$key],
                'photo' => $photo_transaksi,
                'tanggal' => $tanggal,
                'kegiatan' => $kegiatan,
                'catatan' => $catatan,
            ]);

            $barang_pulau = BarangPulau::findOrFail($barang_pulau_id);
            $barang_pulau->update([
                'stock_aktual' => $barang_pulau->stock_aktual - $qty[$key],
            ]);
        }

        return redirect()->route('aset.koordinator.my-gudang')->withNotify('Data transaksi berhasil disimpan!');
    }

    public function koordinator_histori_transaksi()
    {
        $user_id = auth()->user()->id;
        $seksi_id = auth()->user()->struktur->seksi->id;
        $sort = 'DESC';
        $transaksi = TransaksiBarangPulau::where('user_id', $user_id)
                    ->orderBy('tanggal', $sort)
                    ->get();


        $kontrak = Kontrak::where('seksi_id', $seksi_id)->get();
        $tahun = Carbon::now()->format('Y');

        return view('user.aset.koordinator.gudang.my_transaksi', compact([
            'transaksi',
            'sort',
            'kontrak',
            'tahun'
        ]));
    }

    public function koordinator_histori_transaksi_tim()
    {
        $user_id = auth()->user()->id;
        $anggota_id = FormasiTim::where('periode', Carbon::now()->year)
                                ->where('anggota_id', $user_id)
                                ->pluck('anggota_id')
                                ->toArray();
        $koordinator_id = FormasiTim::where('periode', Carbon::now()->year)
                                ->where('koordinator_id', $user_id)
                                ->pluck('koordinator_id')
                                ->toArray();
        $user_ids = array_unique(array_merge($anggota_id, $koordinator_id));

        $sort = 'DESC';
        $transaksi = TransaksiBarangPulau::whereIn('user_id', $user_ids)
                    ->orderBy('tanggal', $sort)
                    ->get();

        return view('user.aset.koordinator.gudang.tim_transaksi', compact([
            'transaksi',
        ]));
    }







    // PJLP
    public function pjlp_index()
    {
        $user_id = auth()->user()->id;
        $seksi_id = auth()->user()->struktur->seksi->id;
        $formasi_tim = FormasiTim::where('periode', Carbon::now()->year)
                                ->where('anggota_id', $user_id)
                                ->orWhere('koordinator_id', $user_id)
                                ->firstOrFail();

        $pulau_id = $formasi_tim->area->pulau->id;
        $seksi_id = $formasi_tim->struktur->seksi->id;

        $barang_pulau = BarangPulau::where('stock_aktual', '>', 0)
                        ->whereRelation('gudang.pulau', 'id', '=', $pulau_id)
                        ->whereRelation('barang.kontrak.seksi', 'id', '=', $seksi_id)
                        ->get();

        $kontrak = Kontrak::where('seksi_id', $seksi_id)->get();
        $tahun = Carbon::now()->format('Y');

        return view('user.aset.pjlp.gudang.my_gudang', compact([
            'barang_pulau',
            'formasi_tim',
            'kontrak',
            'tahun',
        ]));
    }

    public function pjlp_form_pemakaian(Request $request)
    {
        $barang_pulau_id = $request->barang_pulau_id;
        $barang_pulau = BarangPulau::whereIn('id', $barang_pulau_id)->get();

        return view('user.aset.pjlp.gudang.form_pemakaian', compact([
            'barang_pulau',
        ]));
    }

    public function pjlp_store(Request $request)
    {
        $request->validate([
            'photo.*' => 'required|image',
            'tanggal' => 'required',
            'barang_pulau_id.*' => 'required',
            'kegiatan' => 'required',
            'qty.*' => 'required|numeric',
        ]);

        $user_id = auth()->user()->id;
        $tanggal = $request->tanggal;
        $barang_pulau_ids = $request->barang_pulau_id;
        $qty = $request->qty;
        $photo = $request->file('photo');
        $kegiatan = $request->kegiatan;
        $catatan = $request->catatan;

        foreach ($barang_pulau_ids as $key => $barang_pulau_id) {
            $image = Image::make($photo[$key]);

            $imageName = time() . '-' . $photo[$key]->getClientOriginalName();
            $detailPath = 'asset/photo_transaksi/';
            $destinationPath = public_path('storage/'. $detailPath);

            $image->resize(null, 500, function ($constraint) {
                $constraint->aspectRatio();
            });

            $image->save($destinationPath.$imageName);
            $photo_transaksi = $detailPath.$imageName;

            TransaksiBarangPulau::create([
                'user_id' => $user_id,
                'barang_pulau_id' => $barang_pulau_id,
                'qty' => $qty[$key],
                'photo' => $photo_transaksi,
                'tanggal' => $tanggal,
                'kegiatan' => $kegiatan,
                'catatan' => $catatan,
            ]);

            $barang_pulau = BarangPulau::findOrFail($barang_pulau_id);
            $barang_pulau->update([
                'stock_aktual' => $barang_pulau->stock_aktual - $qty[$key],
            ]);
        }

        return redirect()->route('aset.pjlp.my-gudang')->withNotify('Data transaksi berhasil disimpan!');
    }

    public function pjlp_histori_transaksi(Request $request)
    {
        $user_id = auth()->user()->id;
        $sort = $request->sort ?? 'DESC';
        $perPage = $request->perPage ?? 50;

        $transaksi = TransaksiBarangPulau::where('user_id', $user_id)
                    ->orderBy('tanggal', $sort)
                    ->paginate($perPage);

        $jenis = '';
        $start_date = '';
        $end_date = '';

        return view('user.aset.pjlp.gudang.my_transaksi', [
            'transaksi' => $transaksi,
            'sort' => $sort,
            'jenis' => $jenis,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
    }

    public function pjlp_histori_transaksi_filter(Request $request)
    {
        $user_id = auth()->user()->id;
        $sort = $request->sort ?? 'DESC';
        $jenis = $request->jenis;
        $start_date = $request->start_date;
        $end_date = $request->end_date ?? $start_date;

        $transaksi = TransaksiBarangPulau::query();

        // Filter user_id
        $transaksi->where('user_id', $user_id);

        // Filter by jenis
        $transaksi->when($jenis, function ($query) use ($request) {
            return $query->whereRelation('barang_pulau.barang', 'jenis', '=', $request->jenis);
        });

        // Filter by tanggal
        if ($start_date != null and $end_date != null) {
            $transaksi->whereBetween('tanggal', [$start_date, $end_date]);
        }

        // Order By
        $transaksi = $transaksi->orderBy('tanggal', $sort)
                            ->orderBy('created_at', $sort)
                            ->paginate();

        return view('user.aset.pjlp.gudang.my_transaksi', [
            'transaksi' => $transaksi,
            'sort' => $sort,
            'jenis' => $jenis,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
    }
}
