<?php

namespace App\Http\Controllers\superadmin;

use App\Models\Seksi;
use App\Models\Kontrak;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class KontrakController extends Controller
{
    public function index(Request $request, string $uuid)
    {
        $sort       = $request->get('sort', 'ASC');
        $tahun      = now()->year;
        $perPage    = $request->integer('perPage', 50);

        $start_date = $request->get('start_date');
        $end_date   = $request->get('end_date');

        $seksi = Seksi::where('uuid', $uuid)->firstOrFail();

        $kontrak = Kontrak::where('seksi_id', $seksi->id)
            ->when($start_date && $end_date, function ($query) use ($start_date, $end_date) {
                $query->whereBetween('tanggal', [$start_date, $end_date]);
            })
            ->when($start_date && !$end_date, function ($query) use ($start_date) {
                $query->whereDate('tanggal', '>=', $start_date);
            })
            ->when($end_date && !$start_date, function ($query) use ($end_date) {
                $query->whereDate('tanggal', '<=', $end_date);
            })
            ->orderBy('tanggal', $sort)
            ->paginate($perPage)
            ->appends($request->all());

        return view('superadmin.kontrak.index', [
            'kontrak'    => $kontrak,
            'sort'       => $sort,
            'tahun'      => $tahun,
            'seksi'      => $seksi,
            'start_date' => $start_date,
            'end_date'   => $end_date,
        ]);
    }

    public function create(string $uuid)
    {
        $seksi = Seksi::where('uuid', $uuid)->firstOrFail();

        return view('superadmin.kontrak.create', compact('seksi'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name'         => 'required',
                'no_kontrak'   => 'required',
                'nilai_kontrak' => 'required',
                'tanggal'      => 'required|date',
                'seksi_id'     => 'required|exists:seksi,id',
                'lampiran'     => 'nullable|file|mimes:pdf|max:1024',
            ],
            [
                'lampiran.max' => 'Ukuran file lampiran maksimal 1 MB',
            ],
        );

        $kontrak = Kontrak::create([
            'name'          => $request->name,
            'no_kontrak'    => $request->no_kontrak,
            'nilai_kontrak' => $request->nilai_kontrak,
            'tanggal'       => $request->tanggal,
            'seksi_id'      => $request->seksi_id,
        ]);

        if ($request->hasFile('lampiran')) {
            $lampiran = $request->file('lampiran')->store('kontrak/lampiran', 'public');
            $kontrak->update(['lampiran' => $lampiran]);
        }

        $seksi = Seksi::findOrFail($request->seksi_id);

        return redirect()
            ->route('admin-kontrak.index', $seksi->uuid)
            ->withNotify('Data berhasil ditambah!');
    }

    public function edit($uuid)
    {
        $kontrak = Kontrak::where('uuid', $uuid)->firstOrFail();
        $seksi   = $kontrak->seksi;

        return view('superadmin.kontrak.edit', compact('kontrak', 'seksi'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'no_kontrak'    => 'required|string|max:255',
            'nilai_kontrak' => 'required|numeric',
            'tanggal'       => 'required|date',
        ]);

        $kontrak = Kontrak::findOrFail($id);
        $kontrak->update([
            'name'          => $request->name,
            'no_kontrak'    => $request->no_kontrak,
            'nilai_kontrak' => $request->nilai_kontrak,
            'tanggal'       => $request->tanggal,
        ]);

        $seksi = $kontrak->seksi;

        return redirect()
            ->route('admin-kontrak.index', $seksi->uuid)
            ->withNotify('Data berhasil diubah!');
    }

    public function filter(Request $request, string $uuid)
    {
        $seksi      = Seksi::where('uuid', $uuid)->firstOrFail();
        $seksi_id   = $seksi->id;

        $tahun      = $request->input('periode');
        $start_date = $request->input('start_date');
        $end_date   = $request->input('end_date') ?? $start_date;
        $sort       = $request->input('sort', 'ASC');
        $perPage    = $request->input('perPage', 50);

        $kontrak = Kontrak::query()
            ->where('seksi_id', $seksi_id)
            ->when($tahun, function ($query, $tahun) {
                return $query->whereYear('tanggal', $tahun);
            })
            ->when($start_date && $end_date, function ($query) use ($start_date, $end_date) {
                return $query->whereBetween('tanggal', [$start_date, $end_date]);
            })
            ->orderBy('tanggal', $sort)
            ->orderBy('created_at', $sort)
            ->paginate($perPage)
            ->appends($request->all());

        return view('superadmin.kontrak.index', [
            'kontrak'    => $kontrak,
            'start_date' => $start_date,
            'end_date'   => $end_date,
            'sort'       => $sort,
            'tahun'      => $tahun ? Carbon::parse($tahun)->format('Y') : now()->year,
            'seksi'      => $seksi,
        ]);
    }

    public function destroy(Request $request)
    {
        $kontrak = Kontrak::findOrFail($request->id);

        $seksi = $kontrak->seksi;

        if (!is_null($kontrak->lampiran)) {
            Storage::disk('public')->delete($kontrak->lampiran);
        }

        if ($kontrak->canBeDeleted()) {
            $kontrak->delete();
            return redirect()
                ->route('admin-kontrak.index', $seksi->uuid)
                ->withNotify('Data berhasil dihapus!');
        }

        return redirect()
            ->route('admin-kontrak.index', $seksi->uuid)
            ->withError('Data tidak dapat dihapus karena masih terkait dengan data lain!');
    }
}
