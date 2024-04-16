<?php

namespace App\Http\Controllers\user\aset;

use App\Models\Kontrak;
use App\Models\Seksi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class KontrakController extends Controller
{
    public function index()
    {
        $seksi_id = auth()->user()->struktur->seksi->id;
        $start_date = null;
        $end_date = null;
        $sort = 'DESC';

        $kontrak = Kontrak::where('seksi_id', $seksi_id)->orderBy('tanggal', $sort)->get();

        return view('user.aset.kasi.kontrak.index', [
            'kontrak' => $kontrak,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'sort' => $sort,
        ]);
    }

    public function create()
    {
        return view('user.aset.kasi.kontrak.create');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'no_kontrak' => 'required',
                'nilai_kontrak' => 'required',
                'tanggal' => 'required',
                'seksi_id' => 'required',
                'lampiran' => 'file|mimes:pdf|max:1024',
            ],
            [
                'lampiran.max' => 'ukuran file lampiran maksimal 1 MB',
            ],
        );

        $kontrak = Kontrak::create([
            'name' => $request->name,
            'no_kontrak' => $request->no_kontrak,
            'nilai_kontrak' => $request->nilai_kontrak,
            'tanggal' => $request->tanggal,
            'seksi_id' => $request->seksi_id,
        ]);

        if ($request->hasFile('lampiran') && $request->lampiran != '') {
            $kontrak = Kontrak::findOrFail($kontrak->id);
            $lampiran = $request->file('lampiran')->store('kontrak/lampiran');
            $kontrak->update([
                'lampiran' => $lampiran,
            ]);
        }

        return redirect()->route('aset.kasi.kontrak-index')->withNotify('Data berhasil ditambah!');
    }

    public function edit($uuid)
    {
        $kontrak = Kontrak::where('uuid', $uuid)->firstOrFail();

        return view('user.aset.kasi.kontrak.edit', compact(['kontrak']));
    }

    public function update(Request $request, $id)
    {
        $kontrak = Kontrak::findOrFail($id);

        $kontrak->update([
            'name' => $request->name,
            'no_kontrak' => $request->no_kontrak,
            'nilai_kontrak' => $request->nilai_kontrak,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('aset.kasi.kontrak-index')->withNotify('Data berhasil diubah!');
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $kontrak = Kontrak::findOrFail($id);

        if (!is_null($kontrak->lampiran)) {
            Storage::delete($kontrak->lampiran);
        }

        if ($kontrak->canBeDeleted()) {
            $kontrak->delete();

            return redirect()->route('aset.kasi.kontrak-index')->withNotify('Data berhasil dihapus!');
        } else {
            return redirect()->route('aset.kasi.kontrak-index')->withError('Data tidak dapat dihapus karena masih terkait dengan data lain!');
        }
    }
}
