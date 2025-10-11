<?php

namespace App\Http\Controllers\superadmin;

use App\DataTables\KontrakDataTable;
use App\Models\Seksi;
use App\Models\Kontrak;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Pulau;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class KontrakController extends Controller
{
    public function index(KontrakDataTable $dataTable, Request $request, string $uuid)
    {
        $request->validate([
            'periode' => 'nullable|numeric',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $sekarang = Carbon::now();
        $start_date = $request->start_date ?? $sekarang->startOfYear()->format('Y-m-d');
        $end_date   = $request->end_date ?? $sekarang->endOfYear()->format('Y-m-d');

        $seksi = Seksi::where('uuid', $uuid)->firstOrFail();

        return $dataTable->with([
            'seksi_id' => $seksi->id,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ])->render('superadmin.kontrak.index', compact([
            'seksi',
            'start_date',
            'end_date',
        ]));
    }

    public function create(string $uuid)
    {
        $seksi = Seksi::where('uuid', $uuid)->firstOrFail();

        return view('superadmin.kontrak.create', compact('seksi'));
    }

    public function store(Request $request)
    {
        $rawData = $request->validate([
            'name'         => 'required|string',
            'no_kontrak'   => 'required|string',
            'nilai_kontrak' => 'required|numeric|min:0',
            'tanggal'      => 'required|date',
            'seksi_id'     => 'required|exists:seksi,id',
        ]);

        $request->validate(
            [
                'lampiran' => 'nullable|file|mimes:pdf|max:1024',
            ],
            [
                'lampiran.mimes' => 'Lampiran harus berupa file PDF',
                'lampiran.max'   => 'Ukuran file lampiran maksimal 1 MB',
            ]
        );

        $kontrak = Kontrak::updateOrCreate($rawData, $rawData);

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


    public function update(Request $request, $uuid)
    {
        $rawData = $request->validate([
            'name'          => 'required|string|max:255',
            'no_kontrak'    => 'required|string|max:255',
            'nilai_kontrak' => 'required|numeric|min:0',
            'tanggal'       => 'required|date',
        ]);

        $request->validate([
            'lampiran'     => 'nullable|file|mimes:pdf|max:1024',
        ],
        [
            'lampiran.max' => 'Ukuran file lampiran maksimal 1 MB',
        ]);

        $kontrak = Kontrak::where('uuid',$uuid)->firstOrFail();

        $kontrak->update($rawData);

        if ($request->hasFile('lampiran')) {
            if ($kontrak->lampiran && Storage::disk('public')->exists($kontrak->lampiran)) {
                Storage::disk('public')->delete($kontrak->lampiran);
            }
            $lampiran = $request->file('lampiran')->store('kontrak/lampiran', 'public');
            $kontrak->update(['lampiran' => $lampiran]);
        }

        return redirect()
            ->route('admin-kontrak.index', $kontrak->seksi->uuid)
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

    public function dokumen_distribusi(string $uuid)
    {
        $kontrak = Kontrak::where('uuid', $uuid)
            ->with(['barang.pengiriman_barang.gudang.pulau'])
            ->firstOrFail();

        // Ambil semua nama pulau (dinamis, urut A-Z)
        $semuaPulau = Pulau::orderBy('name')->pluck('name');

        $barangData = $kontrak->barang->map(function($barang) use ($semuaPulau) {
            // Hitung distribusi per pulau
            $distribusi = $barang->pengiriman_barang
                ->filter(fn($p) => $p->gudang && $p->gudang->pulau)
                ->groupBy(fn($p) => $p->gudang->pulau->name)
                ->map(fn($items) => $items->sum('qty')) ; // jumlah barang per pengiriman

            // Buat array distribusi lengkap untuk semua pulau (0 jika tidak ada)
            $distribusiLengkap = $semuaPulau->mapWithKeys(fn($pulau) => [
                $pulau => $distribusi[$pulau] ?? 0
            ]);

            // Hitung gudang utama = stock awal - total distribusi
            $totalDistribusi = $distribusiLengkap->sum();
            $stockAwal = $barang->stock_awal;
            $sisaGudangUtama = max($stockAwal - $totalDistribusi, 0);

            return [
                'nama_barang' => $barang->name,
                'jumlah_kontrak' => $stockAwal,
                'gudang_utama' => $sisaGudangUtama,
                'distribusi' => $distribusiLengkap
            ];
        });

        $pdf = Pdf::loadView('superadmin.kontrak.export.pdf', [
            'kontrak' => $kontrak,
            'pulau' => $semuaPulau,
            'barangData' => $barangData
        ]);

        return $pdf->setPaper('a4', 'landscape')->stream(Carbon::now()->format('Ymd_') . 'Dokumen Distribusi.pdf');
    }

    public function destroy(Request $request)
    {
        $kontrak = Kontrak::findOrFail($request->id);

        if ($kontrak->canBeDeleted()) {
            if (!is_null($kontrak->lampiran && Storage::disk('public')->exists($kontrak->lampiran))) {
                Storage::disk('public')->delete($kontrak->lampiran);
            }

            $kontrak->delete();
            return redirect()
                ->route('admin-kontrak.index', $kontrak->seksi->uuid)
                ->withNotify('Data berhasil dihapus!');
        }

        return redirect()
            ->route('admin-kontrak.index', $kontrak->seksi->uuid)
            ->withError('Data tidak dapat dihapus karena masih terkait dengan data lain!');
    }
}
