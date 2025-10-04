@extends('superadmin.layout.base')

@section('title-head')
    <title>
        Superadmin | Ubah Kontrak
    </title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Kontrak</li>
            <li class="breadcrumb-item active">Ubah Data Kontrak</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row gutters justify-content-center">
        <div class="col-xl-4 col-lg-4 col-md-5 col-sm-6 col-12">
            <form action="{{ route('admin-kontrak.update', $kontrak->uuid) }}" method="POST">
                @csrf
                @method('put')
                <div class="card m-0">
                    <div class="card-header">
                        <div class="card-title">Form Ubah Data Kontrak</div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="">Seksi</label>
                            <input type="text" class="form-control" value="{{ $kontrak->seksi->name }}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="">Nama Kontrak</label>
                            <input type="text" class="form-control" name="name" value="{{ $kontrak->name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="">Nomor Kontrak</label>
                            <input type="text" class="form-control" name="no_kontrak" value="{{ $kontrak->no_kontrak }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="">Nilai Kontrak (Rp.)</label>
                            <input type="text" class="form-control" name="nilai_kontrak"
                                value="{{ $kontrak->nilai_kontrak }}" required>
                        </div>
                        <div class="form-group">
                            <label for="">Tanggal</label>
                            <input type="date" class="form-control" name="tanggal" value="{{ $kontrak->tanggal }}"
                                required>
                        </div>
                        <div class="btn group-button">
                            <button type="submit" class="btn btn-primary float-right ml-3">Ubah Data</button>
                            <a href="{{ route('admin-kontrak.index', $seksi->uuid) }}" class="btn btn-dark">Batal</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
