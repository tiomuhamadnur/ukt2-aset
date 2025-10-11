@extends('superadmin.layout.base')

@section('title-head')
    <title>
        Superadmin | Distribusi Barang
    </title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Superadmin</li>
            <li class="breadcrumb-item">Kontrak</li>
            <li class="breadcrumb-item active">Distribusi Barang</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row gutters">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    {{-- <h4 class="d-flex justify-content-center mb-3 text-center" style="text-decoration: underline">
                        Distribusi Barang
                    </h4> --}}
                    <h3>Distribusi Barang - {{ $kontrak->name }} ({{ $kontrak->no_kontrak }})</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Gudang Utama</th>
                                    @foreach ($pulau as $p)
                                        <th>{{ $p }}</th>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
