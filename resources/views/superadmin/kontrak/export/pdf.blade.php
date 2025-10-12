<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dokumen Distribusi Barang</title>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <style>
            @page {
                margin: 35mm 3mm 10mm 3mm;
            }

            body {
                font-size: 10pt;
                font-family: Arial, Helvetica, sans-serif;
            }

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

            h1,
            h2,
            h3,
            .title {
                font-size: 14pt;
                font-weight: bold;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 8pt;
            }

            table th,
            table td {
                /* border: 1px solid #000; */
                padding: 4px 6px;
                text-align: center;
            }

            footer {
                position: fixed;
                bottom: -10mm;
                left: 0;
                right: 0;
                height: 15mm;
                font-size: 10pt;
                color: #666;
                text-align: center;
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
                            <img src="{{ public_path('assets/img/logo_dki_jakarta.png') }}" style="width: 95px;"
                                alt="Logo DKI">
                        </td>
                        <td style="width: 70%; text-align: center; line-height: 1.2; vertical-align: top;">
                            <div style="font-size: 11pt;">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</div>
                            <div style="font-size: 11pt;">KABUPATEN ADMINISTRASI KEPULAUAN SERIBU</div>
                            <div style="font-size: 13pt; font-weight: bold;">SEKRETARIAT KABUPATEN ADMINISTRASI</div>
                            <div style="font-size: 8pt;">Jalan Ikan Barakuda No. 14 Pulau Pramuka Telepon 021-65308229
                                Fax 021-6408452</div>
                            <div style="font-size: 8pt;">E-mail bupati_ps@jakarta.go.id dan bupati.kep1000@gmail.com
                            </div>
                            <div style="font-size: 11pt; font-weight: bold; letter-spacing: 1px;">J A K A R T A</div>
                        </td>
                        <td
                            style="width: 15%; vertical-align: bottom; text-align: right; font-size: 8pt; padding-right: 0;">
                            Kode Pos 14530
                        </td>
                    </tr>
                </tbody>
            </table>
            <hr style="border: none; border-top: 1px solid black; margin: 1px 0;">
        </div>
        <footer>
            <hr style="margin-bottom: 2mm;">
            <span>Dokumen Distribusi Barang - Halaman <span class="pagenum"></span></span>
        </footer>

        <main>
            <div>
                <div class="text-center">
                    <p class="mt-0 mb-1 title text-uppercase font-weight-bold">
                        <u>Dokumen Distribusi Barang</u>
                    </p>
                    <p>Kontrak: {{ $kontrak->name }} ({{ $kontrak->no_kontrak ?? 'N/A' }})</p>
                </div>
                <div class="mt-2">
                    <table class="table table-bordered"
                        style="width: 100%; border-collapse: collapse; font-size: 9pt;">
                        <thead>
                            <tr>
                                <th class="bg-warm-yellow" style="padding: 3px; text-align: center;">No</th>
                                <th class="bg-warm-yellow" style="padding: 3px;">Nama Barang</th>
                                <th class="bg-warm-yellow" style="padding: 3px; text-align: center;">Jumlah</th>
                                <th class="bg-warm-yellow" style="padding: 3px; text-align: center;">Gudang Utama</th>
                                @foreach ($pulau as $p)
                                    <th class="bg-warm-yellow" style="padding: 3px; text-align: center;">
                                        {{ $p }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barangData as $index => $barang)
                                <tr>
                                    <td style="padding: 2px; text-align: center;">{{ $index + 1 }}</td>
                                    <td style="padding: 2px; text-align: left;">{{ $barang['nama_barang'] }}</td>
                                    <td style="padding: 2px; text-align: center;">{{ $barang['jumlah_kontrak'] }}</td>
                                    <td style="padding: 2px; text-align: center;">{{ $barang['gudang_utama'] }}</td>
                                    @foreach ($pulau as $p)
                                        <td style="padding: 2px; text-align: center;">{{ $barang['distribusi'][$p] }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </body>

</html>
