@extends('layout.base_user')

@section('title-head')
    <title>
        Dashboard Simoja | PJLP
    </title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Dashboard Simoja PJLP</li>
    </div>
@endsection


@section('content')
    <div class="row gutters d-flex justify-content-center align-item-center">
        <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
                            <a href="{{ route('simoja.pjlp.absensi-create') }}">
                                <div class="launch-box h-180">
                                    <h3>Absensi Saya</h3>
                                    <i class="fa fa-id-card"></i>
                                    <p>{{ $tanggal }}</p>
                                    <h5 id="jam"></h5>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
                            <a href="{{ route('simoja.pjlp.kinerja-create') }}">
                                <div class="launch-box h-180">
                                    <h3>Kinerja</h3>
                                    <i class="fa fa-suitcase"></i>
                                    <p>{{ $tanggal }}</p>
                                    <h5>Lihat Data Kinerja</h5>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
                            <a href="{{ route('simoja.pjlp.cuti-create') }}">
                                <div class="launch-box h-180">
                                    <h3>Cuti Saya</h3>
                                    <i class="fa fa-calendar-times"></i>
                                    <p>{{ $tanggal }}</p>
                                    @if ($sisa_cuti > 0)
                                        <h5>Sisa Cuti: {{ $sisa_cuti }}</h5>
                                    @else
                                        <h5>Kuota Cuti Habis</h5>
                                    @endif
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
            document.getElementById('jam').innerHTML = h + ":" + m + ":" + s + " WIB";
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
