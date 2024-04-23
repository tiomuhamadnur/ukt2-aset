@extends('layout.base_user')

@section('title-head')
    <title>
        Dashboard Aset | Kasi
    </title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Dashboard Aset Kepala Seksi</li>
    </div>
@endsection


@section('content')
    <div class="row gutters d-flex justify-content-center align-item-center">
        <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <a href="{{ route('aset.kasi.kontrak-index') }}">
                                <div class="launch-box h-180">
                                    <h3>Kontrak</h3>
                                    <i class="fa fa-file-archive"></i>
                                    <p>Daftar Kontrak Pengadaan</p>
                                    <h3>Lihat Daftar</h3>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <a href="{{ route('aset.gudang-utama') }}">
                                <div class="launch-box h-180">
                                    <h3>Gudang Utama</h3>
                                    <i class="fa fa-building"></i>
                                    <p>Data Aset Gudang Utama</p>
                                    <h3>Lihat Daftar</h3>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <a href="{{ route('aset.pengiriman.index') }}">
                                <div class="launch-box h-180">
                                    <h3>Pengiriman</h3>
                                    <i class="fa fa-ship"></i>
                                    <p>{{ $tanggal }}</p>
                                    <h3>Lihat Data Pengiriman</h3>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <a href="{{ route('aset.gudang-pulau') }}">
                                <div class="launch-box h-180">
                                    <h3>Monitoring Stock</h3>
                                    <i class="fa fa-line-chart"></i>
                                    <p>Monitoring Stock Barang Gudang Pulau</p>
                                    <h3>Lihat Daftar</h3>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        @media (max-width: 1100px) {
            h3 {
                font-size: 15px;
            }
        }
    </style>
@endsection


@section('javascript')
    <script type="text/javascript">
        function toggleModal(id) {
            $('#id').val(id);
        }

        function startTime() {
            const today = new Date();
            let h = today.getHours();
            let m = today.getMinutes();
            let s = today.getSeconds();
            m = checkTime(m);
            s = checkTime(s);

            document.querySelectorAll('.jam').forEach(function(element) {
                element.innerHTML = h + ":" + m + ":" + s;
            });

            setTimeout(startTime, 1000);
        }


        function checkTime(i) {
            if (i < 10) {
                i = "0" + i
            };
            return i;
        }

        $(document).ready(function() {
            startTime();
        });
    </script>
@endsection
