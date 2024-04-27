@extends('layout.base_user')

@section('title-head')
    <title>
        Gudang | Tambah Data Barang Gudang Utama
    </title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Gudang</li>
            <li class="breadcrumb-item active">Tambah Data Barang</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row gutters justify-content-center">
        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
            <form action="{{ route('aset.gudang-utama.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="card m-0">
                    <div class="card-header">
                        <div class="card-title">Form Tambah Data Barang</div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="">Masukkan Nama Kontrak</label>
                            <select name="kontrak_id" class="form-control" required>
                                <option value="" selected disabled>- pilih kontrak -</option>
                                @foreach ($kontrak as $item)
                                    <option value="{{ $item->id }}">{{ $item->periode }} - {{ $item->name }} -
                                        {{ $item->no_kontrak }} (Seksi {{ $item->seksi->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Import List Data Barang Pengadaan <span class="text-danger">(Harus dalam
                                    format
                                    Excel)</span>
                            </label>
                            <div class="container my-2">
                                <a href="{{ asset('assets/format/FORMAT IMPORT BARANG.xlsx') }}"
                                    class="btn btn-outline-primary" target="_blank">
                                    <img style="height: 30px;"
                                        src="https://i.pinimg.com/originals/1b/db/8a/1bdb8ac897512116cbac58ffe7560d82.png"
                                        alt="PDF">
                                    <span class="text-dark font-weight-bolder"> Download Contoh Format File Excel</span>
                                </a>
                            </div>
                            <input type="file" class="form-control" id="file" name="file"
                                placeholder="Import file Excel" required
                                accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                            @error('file')
                                <div class="container">
                                    <p class="text-danger">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>
                        <div class="btn group-button">
                            <button type="submit" id="submit" name="submit"
                                class="btn btn-primary float-right ml-3">Submit</button>
                            <a href="{{ route('aset.gudang-utama') }}" class="btn btn-dark">Batal</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
