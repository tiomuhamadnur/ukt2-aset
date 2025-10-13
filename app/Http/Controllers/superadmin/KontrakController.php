<?php

namespace App\Http\Controllers\superadmin;

use App\DataTables\KontrakDataTable;
use App\Exports\kontrak\DistribusiBarangExport;
use App\Models\Seksi;
use App\Models\Kontrak;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Pulau;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

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

    public function dokumen_distribusi_pdf(string $uuid)
    {
        // 1. Ambil Kontrak dengan Eager Loading yang lebih spesifik
        // Load hanya relasi yang benar-benar dibutuhkan dan lakukan filtering/ordering di database jika mungkin.
        $kontrak = Kontrak::where('uuid', $uuid)
            ->with([
                // Eager load barang, lalu pengiriman_barang, dan gudang.
                'barang.pengiriman_barang.gudang.pulau',
            ])
            ->firstOrFail();

        // 2. Ambil semua nama pulau (dinamis, urut A-Z)
        $semuaPulau = Pulau::orderBy('name')->pluck('name');
        $semuaPulauList = $semuaPulau->toArray(); // Konversi ke array untuk lookup cepat

        // 3. Ambil data agregat distribusi dari database
        // Menggunakan eager loading di atas sudah memuat data, kita fokus pada pemrosesan
        // Jika data PengirimanBarang sangat besar, pendekatan raw/query builder murni lebih baik,
        // tetapi untuk meminimalkan perubahan dan memanfaatkan relasi yang sudah dimuat:
        $barangData = $kontrak->barang->map(function($barang) use ($semuaPulau, $semuaPulauList) {
            // Map untuk inisialisasi distribusi dengan 0 untuk semua pulau
            $distribusiLengkap = array_fill_keys($semuaPulauList, 0);

            // Hitung distribusi per pulau dari relasi yang sudah dimuat
            $distribusi = $barang->pengiriman_barang
                ->filter(fn($p) => $p->gudang && $p->gudang->pulau)
                ->groupBy(fn($p) => $p->gudang->pulau->name)
                ->map(fn($items) => $items->sum('qty'));

            // Isi array distribusi lengkap
            foreach ($distribusi as $pulauName => $qty) {
                if (in_array($pulauName, $semuaPulauList)) {
                    $distribusiLengkap[$pulauName] = $qty;
                }
            }

            // Konversi kembali ke Collection untuk kemudahan sum (atau tetap di array, lalu array_sum)
            $distribusiLengkap = collect($distribusiLengkap);

            // Hitung gudang utama
            $totalDistribusi = $distribusiLengkap->sum();
            $stockAwal = $barang->stock_awal;
            $sisaGudangUtama = max($stockAwal - $totalDistribusi, 0);

            return [
                'nama_barang' => $barang->name,
                'jumlah_kontrak' => $stockAwal,
                'gudang_utama' => $sisaGudangUtama,
                // Kembalikan sebagai Collection agar konsisten di view
                'distribusi' => $distribusiLengkap
            ];
        });

        // ... (Bagian PDF tetap sama)

        $pdf = Pdf::loadView('superadmin.kontrak.export.pdf', [
            'kontrak' => $kontrak,
            'pulau' => $semuaPulau,
            'barangData' => $barangData
        ]);

        return $pdf->setPaper('a4', 'landscape')->stream(now()->format('Ymd_') . 'Dokumen Distribusi.pdf');
    }

    public function dokumen_distribusi_excel(string $uuid)
    {
        $kontrak = Kontrak::where('uuid', $uuid)
            ->with(['barang.pengiriman_barang.gudang.pulau'])
            ->firstOrFail();

        $waktu = Carbon::now()->format('Ymd_His');

        // Bersihkan karakter yang tidak boleh ada di nama file
        $nama_kontrak = preg_replace('/[\/\\\\]/', '-', $kontrak->name);
        $no_kontrak = preg_replace('/[\/\\\\]/', '-', $kontrak->no_kontrak);

        // Susun nama file yang rapi
        $name = "{$waktu}_Data_Distribusi_Barang_{$nama_kontrak} ({$no_kontrak}).xlsx";

        return Excel::download(new DistribusiBarangExport($uuid), $name, \Maatwebsite\Excel\Excel::XLSX);
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
