@extends('layout.base_user')

@section('title-head')
    <title>
        Gudang | Data Penerimaan Barang
    </title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Penerimaan</li>
            <li class="breadcrumb-item active">Data Penerimaan Barang</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row gutters">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="d-flex justify-content-center mb-3 text-center" style="text-decoration: underline">Data
                        Penerimaan Barang - Seksi {{ auth()->user()->struktur->seksi->name ?? '-' }} -
                        Pulau {{ $nama_pulau ?? '' }}
                    </h4>
                    <div class="row d-flex justify-content-between align-items-center">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 mb-3 text-left">
                            <div class="d-flex justify-content-start align-items-center flex-wrap">
                                <a href="{{ route('aset.koordinator.index') }}"
                                    class="btn btn-outline-primary mr-2 mb-2 mb-sm-0"><i class="fa fa-arrow-left"></i>
                                    Kembali</a>
                                <a href="{{ route('aset.penerimaan.index') }}" class="btn btn-primary mr-2 mb-2 mb-sm-0"
                                    title="Reset Filter">
                                    <i class="fa fa-refresh"></i>
                                </a>
                                <div class="form-group  mr-2 mb-2 mb-sm-0">
                                    <form id="formSelect" action="{{ route('aset.penerimaan.index') }}" method="GET">
                                        @csrf
                                        @method('GET')
                                        <select name="pulau_id" id="pulau_id" class="form-control">
                                            <option value="" disabled selected>- pilih pulau -</option>
                                            @foreach ($pulau as $item)
                                                <option value="{{ $item->id }}"
                                                    @if ($item->id == $pulau_id) selected @endif>Pulau
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <form class="form-inline mb-2 d-flex justify-content-end">
                                <input class="form-control mr-sm-2" type="search" placeholder="Cari sesuatu di sini..."
                                    aria-label="Search" id="search-bar">
                                <button class="btn btn-dark my-2 my-sm-0" type="submit">Pencarian</button>
                            </form>
                        </div>
                    </div>
                    {{-- PAGINASI BELUM --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th class="text-center">No. Resi</th>
                                    <th class="text-center">Asal</th>
                                    <th class="text-center">Tujuan</th>
                                    <th class="text-center">Pengirim</th>
                                    <th class="text-center">Tanggal Kirim</th>
                                    <th class="text-center">Penerima</th>
                                    <th class="text-center">Tanggal Terima</th>
                                    <th class="text-center">Catatan</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <form id="formTerima" action="{{ route('pengiriman.barang.terima') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    @foreach ($penerimaan_barang as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $item->no_resi }}</td>
                                            <td class="text-center">Gudang Utama</td>
                                            <td class="text-center">{{ $item->gudang->name }}</td>
                                            <td class="text-center text-wrap">{{ $item->submitter->name }}</td>
                                            <td class="text-center">
                                                {{ $item->tanggal_kirim ?? '-' }}
                                            </td>
                                            <td class="text-center text-wrap">{{ $item->receiver->name ?? '-' }}</td>
                                            <td class="text-center">
                                                {{ $item->tanggal_terima ?? '-' }}
                                            </td>
                                            <td class="text-center">
                                                {{ $item->catatan }}
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="btn @if ($item->status == 'Dikirim') btn-warning @else btn-primary @endif">
                                                    {{ $item->status }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('aset.penerimaan.show', $item->no_resi) }}"
                                                    class="btn btn-outline-primary" title="Lihat Detail">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if ($penerimaan_barang->count() == 0)
                                        <tr>
                                            <td class="text-center" colspan="11">
                                                Tidak ada data.
                                            </td>
                                        </tr>
                                    @endif
                                </form>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-3 text-right">
                        <button style="display: none;" id="konfirmasiPenerimaanButton" class="btn btn-primary"
                            data-toggle="modal" data-target="#konfirmasiPenerimaanBarangModal"
                            title="Konfirmasi Penerimaan Barang">
                            <i class="fas fa-check"></i>
                            Konfirmasi Penerimaan Barang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- BEGIN: Konfirmasi Penerimaan Barang --}}
    <div id="konfirmasiPenerimaanBarangModal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-2">
                    <div class="p-2 text-center">
                        <h4 class="text-3xl mt-2">Apakah anda yakin?</h4>
                        <div class="text-slate-500 mt-2">Semua barang yang dipilih, akan dikonfirmasi telah diterima di
                            gudang pulau tujuan.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-dark w-24 mr-1 me-2">Batal</button>
                    <button type="submit" form="formTerima" class="btn btn-primary w-24">Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>
    {{-- END: Konfirmasi Penerimaan Barang --}}

    {{-- BEGIN: Lampiran Modal --}}
    <div class="modal fade" id="modalLampiran" tabindex="-1" role="dialog" aria-labelledby="modalLampiran"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Dokumentasi Bukti Penerimaan Barang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body d-flex justify-content-center">
                    <div class="row gutters">
                        <div id="photo_modal" class="container">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    {{-- END: Lampiran Modal --}}

    {{-- BEGIN: Photo Terima Modal --}}
    <div class="modal fade" id="modalPhotoTerima" tabindex="-1" role="dialog" aria-labelledby="modalLampiran"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Photo Barang Bukti Terima</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body d-flex justify-content-center">
                    <form id="formPhotoTerima" action="#" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Photo Barang <span class="text-secondary">(*max: 3
                                    photo)</span></label>
                            <div class="row-group d-flex preview-container my-2">
                            </div>
                            <input type="file" class="form-control image-input" name="photo[]" accept="image/*"
                                required multiple>
                        </div>
                        @error('photo')
                            <p class="text-danger">
                                {{ $message }}
                            </p>
                        @enderror
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="formPhotoTerima" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    {{-- END: Photo Terima Modal --}}
@endsection

@section('javascript')
    <script>
        $(document).ready(function() {
            document.getElementById('pulau_id').addEventListener('change', function() {
                document.getElementById('formSelect').submit();
            });
        });
    </script>
@endsection
