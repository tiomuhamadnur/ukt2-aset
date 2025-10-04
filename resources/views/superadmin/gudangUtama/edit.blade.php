@extends('superadmin.layout.base')

@section('title-head')
    <title>
        Gudang | Ubah Data Barang Gudang Utama
    </title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Gudang</li>
            <li class="breadcrumb-item active">Ubah Data Barang</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row gutters justify-content-center">
        <div class="col-xl-6 col-lg-8 col-md-12 col-sm-12 col-12">
            <form action="{{ route('admin.gudang-utama.update', $barang->uuid) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card m-0">
                    <div class="card-header">
                        <div class="card-title">Form Edit Data Barang</div>
                    </div>
                    <div class="card-body">

                        <div class="form-group">
                            <label for="no_kontrak">Kontrak Pengadaan</label>
                            <input type="text" class="form-control"
                                value="{{ $barang->kontrak->name }} ({{ $barang->kontrak->tanggal }})" disabled>
                        </div>

                        <div class="form-row gutters">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nama Barang</label>
                                    <input type="text" name="name" class="form-control" value="{{ $barang->name }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jenis">Jenis Barang</label>
                                    <select class="form-control" name="jenis" required>
                                        <option value="">- pilih jenis barang -</option>
                                        <option value="consumable"
                                            {{ strcasecmp($barang->jenis, 'consumable') === 0 ? 'selected' : '' }}>
                                            Consumable</option>
                                        <option value="tools"
                                            {{ strcasecmp($barang->jenis, 'tools') === 0 ? 'selected' : '' }}>Tools</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-row gutters">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code">Kode Barang</label>
                                    <input type="text" class="form-control" name="code" value="{{ $barang->code }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="merk">Merk Barang</label>
                                    <input type="text" class="form-control" name="merk" value="{{ $barang->merk }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-row gutters">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="stock_awal">Stock Awal</label>
                                    <input type="text" class="form-control" name="stock_awal"
                                        value="{{ $barang->stock_awal }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="stock_aktual">Stock Aktual</label>
                                    <input type="text" class="form-control" name="stock_aktual"
                                        value="{{ $barang->stock_aktual }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="satuan">Satuan Barang</label>
                                    <input type="text" class="form-control" name="satuan"
                                        value="{{ $barang->satuan }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-row gutters">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="harga">Harga Barang (Rp.)</label>
                                    <input type="text" class="form-control" name="harga" value="{{ $barang->harga }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="spesifikasi">Spesifikasi</label>
                                    <input type="text" class="form-control" name="spesifikasi"
                                        value="{{ $barang->spesifikasi }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Photo Barang <span class="text-secondary">(*max: 3 photo)</span></label>

                            <!-- Preview foto lama -->
                            @if ($barang->photo)
                                <div class="d-flex flex-wrap mb-2">
                                    @foreach (json_decode($barang->photo, true) as $foto)
                                        <img src="{{ asset('storage/' . $foto) }}" width="100" class="mr-2 mb-2 border">
                                    @endforeach
                                </div>
                            @endif

                            <!-- Input foto baru -->
                            <input type="file" class="form-control image-input" name="photo[]" accept="image/*" multiple>
                            @error('photo')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="btn group-button">
                            <button type="submit" class="btn btn-primary float-right ml-3">Update</button>
                            <a href="{{ route('admin.gudang-utama', $seksi->uuid) }}" class="btn btn-dark">Batal</a>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('javascript')
    <script>
        const imageInputs = document.querySelectorAll('.image-input');
        const previewContainer = document.querySelector('.preview-container');

        imageInputs.forEach(input => {
            input.addEventListener('change', function(event) {
                previewContainer.innerHTML = '';

                const files = event.target.files;
                for (const file of files) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const previewImage = document.createElement('img');
                        previewImage.className = 'preview-image';
                        previewImage.src = e.target.result;
                        previewImage.style = 'width: 120px;';
                        previewImage.className = 'img-thumbnail btn-group mt-2 me-2 d-inline-flex';

                        previewContainer.appendChild(previewImage);
                    }

                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endsection
