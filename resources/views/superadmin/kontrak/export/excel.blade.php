<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Distribusi Barang</title>
    </head>

    <body>
        <table class="table table-bordered mt-2" style="width: 100%;">
            <thead>
                <tr>
                    <th style="background-color: #959595; font-weight: bold;">No</th>
                    <th style="background-color: #959595; font-weight: bold;">Nama Barang</th>
                    <th style="background-color: #959595; font-weight: bold;">Jumlah</th>
                    <th style="background-color: #959595; font-weight: bold;">Gudang Utama</th>
                    @foreach ($pulau as $p)
                        <th style="background-color: #959595; font-weight: bold;">{{ $p }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($barangData as $index => $barang)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $barang['nama_barang'] }}</td>
                        <td>{{ $barang['jumlah_kontrak'] }}</td>
                        <td>{{ $barang['gudang_utama'] }}</td>
                        @foreach ($pulau as $p)
                            <td>{{ $barang['distribusi'][$p] }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>

</html>
