@extends('superadmin.layout.base')

@section('title-head')
    <title>
        Admin Dashboard | UKT2.ORG Kep. Seribu
    </title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Superadmin</li>
            <li class="breadcrumb-item active">Dashboard Tahun {{ $year }}</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row gutters">
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="info-stats4">
                <div class="info-icon">
                    <i class="fa fa-file-text"></i>
                </div>
                <div class="sale-num">
                    <h4>{{ number_format($jumlah_kontrak, 0, ',', '.') }}</h4>
                    <p>Total Kontrak</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="info-stats4">
                <div class="info-icon">
                    <i class="fa fa-truck"></i>
                </div>
                <div class="sale-num">
                    <h4>{{ number_format($pengiriman_hari_ini, 0, ',', '.') }}</h4>
                    <p>Pengiriman Hari Ini</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="info-stats4">
                <div class="info-icon">
                    <i class="fa fa-line-chart"></i>
                </div>
                <div class="sale-num">
                    <h4>{{ number_format($stock_gudang_utama, 0, ',', '.') }}</h4>
                    <p>Barang Stock di Gudang Utama</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="info-stats4">
                <div class="info-icon">
                    <i class="fa fa-arrow-down"></i>
                </div>
                <div class="sale-num">
                    <h4>{{ number_format($stock_habis_gudang_utama, 0, ',', '.') }}</h4>
                    <p>Barang Habis di Gudang Utama</p>
                </div>
            </div>
        </div>
    </div>
@endsection
