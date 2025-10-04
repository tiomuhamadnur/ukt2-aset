<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BAST Penerimaan Barang</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @page {
            margin: 5mm 20mm 20mm 20mm;
        }

        .page-break {
            page-break-after: always;
        }

        .bg-warm-yellow {
            background-color: #FFD966 !important;
        }

        footer {
            position: fixed;
            bottom: -15mm;
            left: 0;
            right: 0;
            height: 15mm;
            font-size: 10pt;
            color: #666;
            text-align: center;
        }

        .pagenum:before {
            content: counter(page);
        }
    </style>
</head>

<body>

    <footer>
        <hr style="margin-bottom: 2mm;">
        <span>BAST Penerimaan Barang - Halaman <span class="pagenum"></span></span>
    </footer>

    <div>
        <div class="text-center">
            <p class="mt-5 mb-1 text-uppercase font-weight-bold">
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
                        PIHAK PERTAMA menyerahkan barang kepada PIHAK KEDUA, dan PIHAK KEDUA menyatakan telah menerima
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
                    Demikian berita acara serah terima barang ini dibuat dan ditandatangani oleh kedua belah pihak untuk
                    dipergunakan sebagaimana mestinya.
                </div>
            </div>
        </div>
        <div class="mt-5 text-center">
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
                    <td class="text-center text-uppercase font-weight-bold p-0" style="border-bottom:1pt solid black;">
                        {{ $dataPengiriman->receiver->name ?? '-' }}
                    </td>
                    <td></td>
                    <td class="text-center text-uppercase font-weight-bold p-0" style="border-bottom:1pt solid black;">
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
        <p class="mt-3 mb-2 text-uppercase font-weight-bold">
            <u>Lampiran</u>
        </p>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th class="text-center align-middle bg-warm-yellow" style="width: 5%">
                        No.
                    </th>
                    <th class="text-center align-middle bg-warm-yellow" style="width: 25%">
                        Nama Barang
                    </th>
                    <th class="text-center align-middle bg-warm-yellow" style="width: 10%">
                        Jumlah
                    </th>
                    <th class="text-center align-middle bg-warm-yellow" style="width: 30%">
                        Foto Kirim
                    </th>
                    <th class="text-center align-middle bg-warm-yellow" style="width: 30%">
                        Foto Terima
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pengirimanBarang as $item)
                    <tr>
                        <td class="text-center align-middle">
                            {{ $loop->iteration }}
                        </td>
                        <td class="text-wrap align-middle">
                            {{ $item->barang->name ?? '-' }}
                        </td>
                        <td class="text-center align-middle">
                            {{ $item->qty }} {{ $item->barang->satuan }}
                        </td>
                        <td class="text-center">
                            @if ($item->photo_kirim)
                                <img class="img-thumbnail mb-2" style="max-width: 180px; max-height: 180px;"
                                    src="{{ public_path('storage/' . $item->photo_kirim) }}" alt="Foto Kirim">
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($item->photo_terima)
                                @foreach (json_decode($item->photo_terima, true) as $photo)
                                    <img class="img-thumbnail mb-2" style="max-width: 180px; max-height: 180px;"
                                        src="{{ public_path('storage/' . $photo) }}" alt="Foto Terima">
                                @endforeach
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
