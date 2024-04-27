<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BAST Penerimaan Barang</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @page {
            margin: 5mm 20mm 5mm 20mm;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
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
                                    {{ $dari->name ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Jabatan</td>
                                <td>:</td>
                                <td>
                                    {{ $dari->jabatan->name ?? '-' }}
                                    {{ $dari->struktur->seksi->name ?? '-' }}
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
                                    {{ $kepada->name ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Jabatan</td>
                                <td>:</td>
                                <td>
                                    {{ $kepada->jabatan->name ?? '-' }}
                                    Pulau {{ $kepada->area->pulau->name ?? '-' }}
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
                            <th class="bg-warning text-center">
                                No
                            </th>
                            <th class="bg-warning text-center">
                                Nama Barang
                            </th>
                            <th class="bg-warning text-center">
                                Spesifikasi
                            </th>
                            <th class="bg-warning text-center">
                                Jumlah
                            </th>
                            <th class="bg-warning text-center">
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
                        {{ $kepada->name ?? '-' }}
                    </td>
                    <td></td>
                    <td class="text-center text-uppercase font-weight-bold p-0" style="border-bottom:1pt solid black;">
                        {{ $dari->name ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <td class="text-center p-0">
                        ID.{{ $kepada->nip ?? '-' }}
                    </td>
                    <td></td>
                    <td class="text-center p-0">
                        NIP.{{ $dari->nip ?? '-' }}
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
                    <th class="text-center align-middle bg-warning" style="width: 5mm">
                        No.
                    </th>
                    <th class="text-center align-middle bg-warning">
                        Nama Barang
                    </th>
                    <th class="text-center align-middle bg-warning">
                        Jumlah
                    </th>
                    <th class="text-center align-middle bg-warning">
                        Foto Kirim
                    </th>
                    <th class="text-center align-middle bg-warning">
                        Foto Terima
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pengirimanBarang as $item)
                    <tr>
                        <td class="text-center">
                            {{ $loop->iteration }}
                        </td>
                        <td class="text-wrap">
                            {{ $item->barang->name ?? '-' }}
                        </td>
                        <td class="text-center">
                            {{ $item->qty }} {{ $item->barang->satuan }}
                        </td>
                        <td class="text-center">
                            {{-- <img class="img-thumbnail" style="height: 150mm"
                                        src="{{ public_path('storage/' . $cuti->lampiran) }}" alt="Lampiran"> --}}
                            <img class="img-thumbnail" style="max-width: 200px;"
                                src="{{ public_path('storage/' . $item->photo_kirim) }}" alt="Lampiran">
                        </td>
                        <td class="text-center">
                            @foreach (json_decode($item->photo_terima) as $photo)
                                <img class="img-thumbnail" style="max-width: 200px;"
                                    src="{{ public_path('storage/' . $photo) }}" alt="Lampiran">
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
