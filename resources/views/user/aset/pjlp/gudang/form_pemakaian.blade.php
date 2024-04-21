@extends('layout.base_user')

@section('title-head')
    <title>
        Gudang | Form Pengambilan Barang
    </title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Gudang</li>
            <li class="breadcrumb-item">Gudang Saya</li>
            <li class="breadcrumb-item active">Form Pengambilan Barang</li>
        </ol>
    </div>
@endsection


@section('content')
    <div class="row gutters justify-content-center">
        <div class="col-xl-6 col-lg-6 col-md-7 col-sm-8 col-12">
            <form action="{{ route('aset.pjlp.transaksi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="card m-0">
                    <div class="card-header">
                        <div class="card-title text-center">Form Pengambilan Barang</div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>PIC</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
                        </div>
                        <div class="form-group">
                            <label>Jabatan</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->jabatan->name }}" disabled>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Pengambilan</label>
                            <input type="text" placeholder="Tanggal Pengambilan" onfocus="(this.type='date')"
                                onblur="(this.type='text')" class="form-control" name="tanggal" required>
                        </div>
                        <div class="form-group">
                            <label>Kegiatan</label>
                            <input type="text" placeholder="Nama Kegiatan" class="form-control" name="kegiatan" required
                                autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="list">List Barang</label>
                            <div class="card">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 5%">No</th>
                                            <th class="text-center">Nama Barang</th>
                                            <th class="text-center text-wrap">Stock Tersedia</th>
                                            <th class="text-center">Qty. Barang</th>
                                            <th class="text-center">Photo Bukti Pengambilan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($barang_pulau as $item)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="font-weight-bolder">
                                                    {{ $item->barang->name }}
                                                    <input type="text" name="barang_pulau_id[]"
                                                        value="{{ $item->id }}" hidden>
                                                </td>
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
                                class="btn btn-primary float-right ml-3">Submit Pengambilan</button>
                            <a href="{{ route('aset.pjlp.my-gudang') }}" class="btn btn-dark">Batal</a>
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
