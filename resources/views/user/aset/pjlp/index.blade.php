@extends('layout.base_user')

@section('title-head')
    <title>
        Dashboard Aset | PJLP
    </title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Dashboard Aset PJLP</li>
    </div>
@endsection


@section('content')
    <div class="row gutters d-flex justify-content-center align-item-center">
        <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12">
            {{-- <div class="d-flex justify content-center">
                <a href="{{ route('dashboard.index') }}" class="btn btn-primary">DUMMY BACK TO DASHBOARD</a>
            </div> --}}
            <div class="card">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <a href="{{ route('aset.pjlp.my-gudang') }}">
                                <div class="launch-box h-180">
                                    <h3>Gudang Barang</h3>
                                    <i class="fa fa-building"></i>
                                    <p>Daftar Barang di Gudang Saya</p>
                                    <h3>Lihat Daftar</h3>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <a href="{{ route('aset.pjlp.my-transaction') }}">
                                <div class="launch-box h-180">
                                    <h3>Histori Transaksi</h3>
                                    <i class="fa fa-list-ol"></i>
                                    <p>Lihat Transaski Barang Saya</p>
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
        @media (max-width: 576px) {
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
            document.getElementById('jam').innerHTML = h + ":" + m + ":" + s;
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
