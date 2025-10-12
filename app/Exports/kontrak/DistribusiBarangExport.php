<?php

namespace App\Exports\kontrak;

use App\Models\Kontrak;
use App\Models\Pulau;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DistribusiBarangExport implements FromView, ShouldAutoSize
{
    public $uuid;

    public function __construct(?string $uuid = null)
    {
        $this->uuid = $uuid;
    }

    public function view(): View
    {
        $kontrak = Kontrak::where('uuid', $this->uuid)
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

        return view('superadmin.kontrak.export.excel', [
            'kontrak' => $kontrak,
            'pulau' => $semuaPulau,
            'barangData' => $barangData
        ]);
    }
}
