<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>BAST Penerimaan Barang</title>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <style>
            @page {
                margin: 35mm 20mm 20mm 20mm;
            }

            body {
                font-size: 10pt;
                font-family: Arial, Helvetica, sans-serif;
            }

            /* ======================================= */
            /* ========= MODIFIKASI DIMULAI ========== */
            /* ======================================= */
            .header {
                position: fixed;
                top: -32mm;
                /* Ditarik ke atas agar masuk ke area margin */
                left: 0px;
                right: 0px;
                /* Tinggi header akan otomatis */
            }

            main {
                margin-top: 10mm;
                /* Memberi ruang agar konten tidak tertutup header */
            }

            /* ======================================= */
            /* ========= MODIFIKASI SELESAI ========== */
            /* ======================================= */

            h1,
            h2,
            h3,
            .title {
                font-size: 14pt;
                font-weight: bold;
            }

            table {
                font-size: 10pt;
            }

            table th {
                font-size: 11pt;
            }

            footer {
                position: fixed;
                bottom: -20mm;
                left: 0;
                right: 0;
                height: 15mm;
                font-size: 10pt;
                color: #666;
                text-align: center;
            }

            .dokumentasi-cell p {
                margin: 0 0 2px 0;
                /* font-weight: bold; */
                display: block;
            }

            .dokumentasi-photo {
                display: inline-block;
                max-height: 55px;
                margin: 2px 2px 0 0;
                border: 1px solid #aaa;
                padding: 1px;
                vertical-align: top;
            }

            .dokumentasi-photo:last-child {
                margin-right: 0;
            }

            .dokumentasi-cell {
                vertical-align: top;
                line-height: 1.2;
                overflow: hidden;
            }

            .bg-warm-yellow {
                background-color: #959595 !important;
            }

            .page-break {
                page-break-after: always;
            }

            .pagenum:before {
                content: counter(page);
            }
        </style>

    </head>

    <body>
        <div class="header">
            <table style="width: 100%;">
                <tbody>
                    <tr>
                        <td style="width: 15%; text-align: center; vertical-align: top;">
                            <img src="{{ public_path('assets/img/logo_dki_jakarta.png') }}" style="width: 95px;" alt="Logo DKI">
                        </td>
                        <td style="width: 85%; text-align: center; line-height: 1.2; vertical-align: top;">
                            <div style="font-size: 11pt;">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</div>
                            <div style="font-size: 11pt;">KABUPATEN ADMINISTRASI KEPULAUAN SERIBU</div>
                            <div style="font-size: 13pt; font-weight: bold;">SEKRETARIAT KABUPATEN ADMINISTRASI</div>
                            <div style="font-size: 8pt;">Jalan Ikan Barakuda No. 14 Pulau Pramuka Telepon 021-65308229
                                Fax 021-6408452</div>
                            <div style="font-size: 8pt;">E-mail bupati_ps@jakarta.go.id dan bupati.kep1000@gmail.com
                            </div>
                            <div style="font-size: 11pt; font-weight: bold; letter-spacing: 1px;">J A K A R T A</div>
                            <div style="text-align: right; font-size: 8pt; padding-right: 0px;">Kode Pos 14530</div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <hr style="border: none; border-top: 1px solid black; margin: 1px 0;">
        </div>
        <footer>
            <hr style="margin-bottom: 2mm;">
            <span>BAST Penerimaan Barang - Halaman <span class="pagenum"></span></span>
        </footer>

        <main>
            <div>
                <div class="text-center">
                    <p class="mt-0 mb-1 text-uppercase font-weight-bold">
                        <u>
                            BERITA ACARA SERAH TERIMA
                        </u>
                    </p>
                </div>
                <div class="mt-4">
                    <div>
                        <div class="text-justify mb-1">
                            <p>
                                Pada hari ini <span class="font-weight-bold">{{ $hari }}</span>, tanggal <span
                                    class="font-weight-bold">{{ $tanggal }}</span>, Bulan <span
                                    class="font-weight-bold">{{ $bulan }}</span>, tahun <span
                                    class="font-weight-bold">{{ $tahun }}</span>, yang bertanda tangan di
                                bawah ini:
                            </p>
                            <table class="ml-3 mt-3">
                                <tbody>
                                    <tr>
                                        <td style="width: 25mm">Nama</td>
                                        <td style="width: 3mm">:</td>
                                        <td class="font-weight-bold">
                                            {{ $dataPengiriman->submitter->name ?? '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Jabatan</td>
                                        <td>:</td>
                                        <td>
                                            @if ($dataPengiriman->submitter->is_plt == true)
                                                Plt.
                                            @endif
                                            {{ $dataPengiriman->submitter->jabatan->name ?? '-' }}
                                            {{ $dataPengiriman->submitter->struktur->seksi->name ?? '-' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <p class="mt-3 mb-1 text-justify">
                                Selanjutnya disebut PIHAK PERTAMA
                            </p>

                            <table class="ml-3 mt-2">
                                <tbody>
                                    <tr>
                                        <td style="width: 25mm">Nama</td>
                                        <td style="width: 3mm">:</td>
                                        <td class="font-weight-bold">
                                            {{ $dataPengiriman->receiver->name ?? '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Jabatan</td>
                                        <td>:</td>
                                        <td>
                                            {{ $dataPengiriman->receiver->jabatan->name ?? '-' }}
                                            Pulau {{ $dataPengiriman->receiver->area->pulau->name ?? '-' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <p class="mt-3 mb-1 text-justify">
                                Selanjutnya disebut PIHAK KEDUA
                            </p>

                            <p class="mt-3 mb-1 text-justify">
                                PIHAK PERTAMA menyerahkan barang kepada PIHAK KEDUA, dan PIHAK KEDUA menyatakan telah
                                menerima
                                barang dari PIHAK PERTAMA berupa:
                            </p>
                        </div>

                        <table border="1" class="table table-bordered mt-3">
                            <tbody>
                                <tr>
                                    <th class="bg-warm-yellow text-center">
                                        No
                                    </th>
                                    <th class="bg-warm-yellow text-center">
                                        Nama Barang
                                    </th>
                                    <th class="bg-warm-yellow text-center">
                                        Spesifikasi
                                    </th>
                                    <th class="bg-warm-yellow text-center">
                                        Jumlah
                                    </th>
                                    <th class="bg-warm-yellow text-center">
                                        Satuan
                                    </th>
                                </tr>
                            </tbody>
                            <tbody>
                                @foreach ($pengirimanBarang as $item)
                                    <tr>
                                        <td class="text-center py-0">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="py-0">
                                            {{ $item->barang->name ?? '-' }}
                                        </td>
                                        <td class="py-0">
                                            {{ $item->barang->spesifikasi ?? '-' }}
                                        </td>
                                        <td class="text-center py-0">
                                            {{ $item->qty }}
                                        </td>
                                        <td class="py-0">
                                            {{ $item->barang->satuan ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-3 text-justify">
                            Demikian berita acara serah terima barang ini dibuat dan ditandatangani oleh kedua belah
                            pihak
                            untuk
                            dipergunakan sebagaimana mestinya.
                        </div>
                    </div>
                </div>
                {{-- @if ($pengirimanBarang->count() >= 19 && $pengirimanBarang->count() <= 22)
                    <div class="page-break"></div>
                @endif --}}
                <div class="mt-4 text-center">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-center p-0">PIHAK KEDUA</td>
                            <td style="width: 4cm"></td>
                            <td class="text-center p-0">PIHAK PERTAMA</td>
                        </tr>
                        <tr>
                            <td class="text-center p-0">Yang Menerima</td>
                            <td></td>
                            <td class="text-center p-0">Yang Menyerahkan</td>
                        </tr>
                        <tr>
                            <td style="height: 27mm;"></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="text-center text-uppercase font-weight-bold p-0"
                                style="border-bottom:1pt solid black;">
                                {{ $dataPengiriman->receiver->name ?? '-' }}
                            </td>
                            <td></td>
                            <td class="text-center text-uppercase font-weight-bold p-0"
                                style="border-bottom:1pt solid black;">
                                {{ $dataPengiriman->submitter->name ?? '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center p-0">
                                NIP.{{ $dataPengiriman->receiver->nip ?? '-' }}
                            </td>
                            <td></td>
                            <td class="text-center p-0">
                                NIP.{{ $dataPengiriman->submitter->nip ?? '-' }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="page-break"></div>

            <div class="text-center">
                <p class="mt-1 mb-1 text-uppercase font-weight-bold">
                    <u>Lampiran</u>
                </p>
                <table class="table table-bordered mt-2 mb-0" style="font-size: 11px; border-collapse: collapse;">
                    <thead>
                        <tr class="bg-warm-yellow text-center" style="font-weight: bold;">
                            <th style="width: 5%; padding: 4px;">No.</th>
                            <th style="width: 25%; padding: 4px;">Nama Barang</th>
                            <th style="width: 10%; padding: 4px;">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengirimanBarang as $index => $item)
                            <tr>
                                <td class="text-center align-middle" rowspan="3" style="padding: 2px;">{{ $index + 1 }}</td>
                                <td class="font-weight-bold" style="padding: 2px;">{{ $item->barang->name ?? '-' }}</td>
                                <td style="padding: 2px;">{{ $item->qty }} {{ $item->barang->satuan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-left dokumentasi-cell" style="padding: 1px 2px;">
                                    <p style="margin: 0 0 1px 0; font-size: 9px;">Dokumentasi Pengiriman:</p>
                                    @if ($item->photo_kirim)
                                        <img class="img-thumbnail dokumentasi-photo"
                                            src="{{ public_path('storage/' . $item->photo_kirim) }}"
                                            alt="Foto Kirim"
                                            style="max-height: 55px; margin: 1px; border: 1px solid #999; padding: 1px;">
                                    @else
                                        <span class="text-muted" style="font-size: 9px;">N/A</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-left dokumentasi-cell" style="padding: 1px 2px;">
                                    <p style="margin: 0 0 1px 0; font-size: 9px;">Dokumentasi Penerimaan:</p>
                                    @if ($item->photo_terima)
                                        @foreach (json_decode($item->photo_terima, true) as $photo)
                                            <img class="img-thumbnail dokumentasi-photo"
                                                src="{{ public_path('storage/' . $photo) }}"
                                                alt="Foto Terima"
                                                style="max-height: 55px; margin: 1px; border: 1px solid #999; padding: 1px;">
                                        @endforeach
                                    @else
                                        <span class="text-muted" style="font-size: 9px;">N/A</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    </body>

</html>
