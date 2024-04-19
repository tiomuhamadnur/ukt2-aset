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
        $barang = Barang::where('stock_aktual', '>', 0)
                        ->whereRelation('kontrak.seksi', 'id', '=', $seksi_id)
                        ->orderBy('kontrak_id', 'ASC')
                        ->orderBy('name', 'ASC')
                        ->get();

        $gudang_tujuan = Gudang::all();
        $kontrak = Kontrak::where('seksi_id', $seksi_id)->orderBy('tanggal', 'DESC')->get();
        $seksi = Seksi::all();
        $tahun = Carbon::now()->format('Y');

        return view('user.aset.kasi.gudang.index', compact([
            'barang',
            'gudang_tujuan',
            'kontrak',
            'seksi',
            'tahun'
        ]));
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

    public function kasi_gudang_pulau()
    {
        $seksi_id = auth()->user()->struktur->seksi->id;
        $barang_pulau = BarangPulau::whereRelation('barang.kontrak.seksi', 'id', '=', $seksi_id)
                        ->orderBy('gudang_id')
                        ->get();
        return view('user.aset.kasi.gudang.gudang_pulau', compact([
            'barang_pulau',
        ]));
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

    public function pjlp_histori_transaksi()
    {
        $user_id = auth()->user()->id;
        $seksi_id = auth()->user()->struktur->seksi->id;
        $sort = 'DESC';
        $transaksi = TransaksiBarangPulau::where('user_id', $user_id)
                    ->orderBy('tanggal', $sort)
                    ->get();

        $kontrak = Kontrak::where('seksi_id', $seksi_id)->get();
        $tahun = Carbon::now()->format('Y');

        return view('user.aset.pjlp.gudang.my_transaksi', compact([
            'transaksi',
            'sort',
            'kontrak',
            'tahun'
        ]));
    }
}
