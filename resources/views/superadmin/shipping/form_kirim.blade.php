@extends('superadmin.layout.base')

@section('title-head')
    <title>
        Superadmin | Form Pengiriman Barang
    </title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Pengiriman</li>
            <li class="breadcrumb-item active">Form Pengiriman Barang</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row gutters justify-content-center">
        <div class="col-xl-6 col-lg-6 col-md-7 col-sm-8 col-12">
            <form action="{{ route('admin.pengiriman.store', $seksi->uuid) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="card m-0">
                    <div class="card-header">
                        <div class="card-title text-center">Form Pengiriman Barang</div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Submitter</label>
                            <input type="hidden" name="submitter_id" value="{{ $submitter->id ?? '' }}">
                            <input type="text" class="form-control" value="{{ $submitter->name ?? '-' }}" disabled>
                        </div>

                        <div class="form-group">
                            <label>Jabatan</label>
                            <input type="text" class="form-control" value="{{ $submitter->jabatan->name ?? '-' }} ({{$seksi->name }})"
                                disabled>
                        </div>

                        <div class="form-group">
                            <label>Tanggal Pengiriman</label>
                            <input type="text" placeholder="Tanggal Pengiriman" onfocus="(this.type='date')"
                                onblur="(this.type='text')" class="form-control" name="tanggal_kirim" required>
                        </div>
                        <div class="form-group">
                            <label>Gudang Pulau Tujuan</label>
                            <select name="gudang_id" class="form-control" required>
                                <option value="" selected disabled>- pilih gudang pulau tujuan -</option>
                                @foreach ($gudang as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="list">List Barang</label>
                            <div class="card">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 5%">No</th>
                                            <th class="text-center text-wrap">Nama Barang</th>
                                            <th class="text-center text-wrap">Spesifikasi</th>
                                            <th class="text-center text-wrap">Stock Tersedia</th>
                                            <th class="text-center">Qty. Barang</th>
                                            <th class="text-center">Photo Bukti Kirim</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($barang as $item)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="font-weight-bolder text-wrap">
                                                    {{ $item->name }}
                                                    <input type="text" name="barang_id[]" value="{{ $item->id }}"
                                                        hidden>
                                                </td>
                                                <td class="text-center text-wrap">{{ $item->spesifikasi }}</td>
                                                <td class="text-center">{{ $item->stock_aktual }} {{ $item->satuan }}</td>
                                                <td class="text-center">
                                                    <input type="number" name="qty[]" class="form-control" min="1"
                                                        max="{{ $item->stock_aktual }}" placeholder="jumlah" required>
                                                </td>
                                                <td class="text-center">
                                                    <div class="preview">
                                                    </div>
                                                    <input type="file" name="photo[]" class="form-control"
                                                        accept="image/*" required>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="gudang">Catatan <span class="text-primary">(opsional)</span></label>
                            <textarea name="catatan" placeholder="Catatan tambahan" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="btn group-button">
                            <button type="submit" id="submit" name="submit"
                                class="btn btn-primary float-right ml-3">Submit Pengiriman</button>
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
        document.addEventListener("DOMContentLoaded", function() {
            const fileInputs = document.querySelectorAll('input[type="file"]');

            fileInputs.forEach(function(input) {
                input.addEventListener("change", function(event) {
                    const file = event.target.files[0];
                    const preview = event.target.closest("td").querySelector(".preview");

                    if (file) {
                        const reader = new FileReader();

                        reader.onload = function() {
                            const img = document.createElement("img");
                            img.src = reader.result;
                            img.classList.add("img-thumbnail");
                            img.classList.add("my-1");
                            img.style.height = "100px";
                            preview.innerHTML = "";
                            preview.appendChild(img);
                        };

                        reader.readAsDataURL(file);
                    } else {
                        preview.innerHTML = "";
                    }
                });
            });
        });
    </script>
@endsection
