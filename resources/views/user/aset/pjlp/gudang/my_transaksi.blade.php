@extends('layout.base_user')

@section('title-head')
    <title>
        Gudang | Histori Pemakaian Saya
    </title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Gudang</li>
            <li class="breadcrumb-item">Gudang Saya</li>
            <li class="breadcrumb-item active">Histori Pemakaian Saya</li>
        </ol>
    </div>
@endsection


@section('content')
    <div class="row gutters">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="d-flex justify-content-center mb-3 text-center" style="text-decoration: underline">Data
                        Transaksi Penggunaan Barang - {{ auth()->user()->name }}</h4>
                    <div class="row d-flex justify-content-between align-items-center">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 mb-3 text-left">
                            <div class="d-flex justify-content-start align-items-center flex-wrap">
                                <a href="{{ route('aset.pjlp.index') }}"
                                    class="btn btn-outline-primary mr-2 mb-2 mb-sm-0"><i class="fa fa-arrow-left"></i>
                                    Kembali</a>
                                <a href="{{ route('aset.pjlp.my-gudang') }}" class="btn btn-primary mr-2 mb-2 mb-sm-0"><i
                                        class="fa fa-building"></i> Ke Gudang</a>
                                {{-- <button class="btn btn-primary mr-2 mb-2 mb-sm-0">Export to Excel</button> --}}
                                <a href="" class="btn btn-primary mr-2 mb-2 mb-sm-0" data-toggle="modal"
                                    data-target="#modalFilter"><i class="fa fa-filter"></i></a>
                                <a href="{{ route('aset.pjlp.my-transaction') }}"
                                    class="btn btn-primary mr-2 mb-2 mb-sm-0"><i class="fa fa-refresh"></i>
                                </a>
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
                    <div class="paginate-style">
                        <div class="d-flex justify-content-center mb-2">
                            <nav aria-label="Pagination">
                                <ul class="pagination">
                                    {{ $transaksi->links('vendor.pagination.bootstrap-4') }}
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th class="text-center">PIC</th>
                                    <th class="text-center">Gudang</th>
                                    <th class="text-center">Nama Barang</th>
                                    <th class="text-center">Spesifikasi Barang</th>
                                    <th class="text-center">Jenis Barang</th>
                                    <th class="text-center text-wrap">Tanggal Pengambilan</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-center">Kegiatan</th>
                                    <th class="text-center">Catatan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaksi as $item)
                                    <tr>
                                        <td class="text-center">
                                            {{ ($transaksi->currentPage() - 1) * $transaksi->perPage() + $loop->index + 1 }}
                                        </td>
                                        <td class="text-center font-weight-bold">{{ $item->user->name }}</td>
                                        <td class="text-center">{{ $item->barang_pulau->gudang->name }}</td>
                                        <td class="text-left font-weight-bold">{{ $item->barang_pulau->barang->name }}
                                        </td>
                                        <td class="text-left text-wrap">{{ $item->barang_pulau->barang->spesifikasi }}
                                        </td>
                                        <td class="text-center">
                                            @if ($item->barang_pulau->barang->jenis == 'consumable')
                                                Barang Persediaan
                                            @elseif ($item->barang_pulau->barang->jenis == 'tools')
                                                Barang Modal/Aset
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center">{{ date('d-m-Y', strtotime($item->tanggal)) }}</td>
                                        <td class="text-center font-weight-bold">{{ $item->qty }}
                                            {{ $item->barang_pulau->barang->satuan ?? '-' }}
                                        </td>
                                        <td class="text-left text-wrap">{{ $item->kegiatan ?? '-' }}</td>
                                        <td class="text-left text-wrap">{{ $item->catatan ?? '-' }}</td>
                                        <td class="text-center">
                                            <a href="#" class="btn btn-outline-primary" title="Lihat Photo"
                                                data-toggle="modal" data-target="#modalLampiran"
                                                data-photo="{{ asset('storage/' . $item->photo) }}"><i
                                                    class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($transaksi->count() == 0)
                                    <tr>
                                        <td class="text-center" colspan="9">
                                            Data transaksi barang atas nama <span
                                                class="font-weight-bold">{{ auth()->user()->name }}</span> tidak ditemukan.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- BEGIN: Filter Modal --}}
    <div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="modalFilter" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filter Data Transaksi Barang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formFilter" action="{{ route('aset.pjlp.my-transaction.filter') }}" method="GET">
                        @csrf
                        @method('GET')
                        <div class="form-row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <label for="">Jenis Barang</label>
                                    <select name="jenis" class="form-control">
                                        <option value="" selected disabled>- pilih jenis barang -</option>
                                        <option value="consumable" @if ($jenis == 'consumable') selected @endif>
                                            Barang Persediaan
                                        </option>
                                        <option value="tools" @if ($jenis == 'tools') selected @endif>Barang
                                            Modal/Aset
                                        </option>

                                    </select>
                                </div>
                                <label for="periode">Periode</label>
                                <div class="form-row gutters">
                                    <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="form-group">
                                            <input type="date" class="form-control" value="{{ $start_date }}"
                                                name="start_date">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="form-group">
                                            <input type="date" class="form-control" value="{{ $end_date }}"
                                                name="end_date">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row gutters">
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="form-group">
                                            <label for="">Urutan</label>
                                            <select name="sort" class="form-control">
                                                <option value="ASC" @if ($sort == 'ASC') selected @endif>A
                                                    to Z
                                                </option>
                                                <option value="DESC" @if ($sort == 'DESC') selected @endif>Z
                                                    to A
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Tutup</button>
                    <button type="submit" form="formFilter" class="btn btn-primary">Filter Data</button>
                </div>
            </div>
        </div>
    </div>
    {{-- END: Filter Modal --}}

    {{-- BEGIN: Lampiran Modal --}}
    <div class="modal fade" id="modalLampiran" tabindex="-1" role="dialog" aria-labelledby="modalLampiran"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lampiran Dokumentasi Pengambilan Barang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body d-flex justify-content-center">
                    <div class="row gutters">
                        <div class="container">
                            <img src="#" id="photo_modal" alt="Photo Bukti Ambil" class="img-thumbnail"
                                style="width: 400px">
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

    {{-- BEGIN: Konfirmasi Excel --}}
    <div id="modalDownloadExcel" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-2">
                    <div class="p-2 text-center">
                        <div class="mt-2 fw-bolder">Apakah anda yakin?</div>
                        <div class="mt-2">
                            <img style="height: 100px;"
                                src="https://i.pinimg.com/originals/1b/db/8a/1bdb8ac897512116cbac58ffe7560d82.png"
                                alt="PDF">
                        </div>
                        <div class="text-slate-500 mt-2">
                            <p>
                                Data ini akan di-generate dalam format Excel!
                            </p>
                        </div>
                        <form id="exportExcel" action="{{ route('transaksi.barang.excel') }}" method="GET" hidden>
                            @csrf
                            @method('GET')
                            {{-- <input type="text" name="pulau_id" value="{{ $pulau_id ?? '' }}">
                            <input type="text" name="seksi_id" value="{{ $seksi_id ?? '' }}">
                            <input type="text" name="koordinator_id" value="{{ $koordinator_id ?? '' }}">
                            <input type="text" name="tim_id" value="{{ $tim_id ?? '' }}">
                            <input type="text" name="start_date" value="{{ $start_date ?? '' }}">
                            <input type="text" name="end_date" value="{{ $end_date ?? '' }}"> --}}
                        </form>
                    </div>
                    <div class="px-5 pb-8 text-center mt-3">
                        <button type="submit" form="exportExcel" formtarget="_blank"
                            class="btn btn-primary w-24 mr-1 me-2">Download</button>
                        <button type="button" data-dismiss="modal" class="btn btn-dark w-24 mr-1 me-2">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- END: Konfirmasi Excel --}}
@endsection


@section('javascript')
    <script type="text/javascript">
        function toggleModal(id) {
            $('#id').val(id);
        }

        $(document).ready(function() {
            $('#modalLampiran').on('show.bs.modal', function(e) {
                var photoPath = $(e.relatedTarget).data('photo');

                document.getElementById("photo_modal").src = photoPath;
            });
        });
    </script>
@endsection
