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
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row gutters">
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="info-stats4">
                <div class="info-icon">
                    <i class="icon-user"></i>
                </div>
                <div class="sale-num">
                    <h4>250</h4>
                    <p>Total Kontrak</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="info-stats4">
                <div class="info-icon">
                    <i class="icon-user-minus"></i>
                </div>
                <div class="sale-num">
                    <h4>0</h4>
                    <p>Transaksi Hari Ini</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="info-stats4">
                <div class="info-icon">
                    <i class="icon-shopping-bag1"></i>
                </div>
                <div class="sale-num">
                    <h4>250</h4>
                    <p>Gudang Utama</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="info-stats4">
                <div class="info-icon">
                    <i class="icon-activity"></i>
                </div>
                <div class="sale-num">
                    <h4>12</h4>
                    <p>Monitoring Stock</p>
                </div>
            </div>
        </div>
    </div>
@endsection