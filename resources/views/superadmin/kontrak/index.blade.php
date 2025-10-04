@extends('superadmin.layout.base')

@section('title-head')
    <title>
        Superadmin | Kontrak
    </title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Superadmin</li>
            <li class="breadcrumb-item">Kontrak</li>
            <li class="breadcrumb-item active">Daftar Kontrak</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row gutters">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="d-flex justify-content-center mb-3 text-center" style="text-decoration: underline">
                        Daftar Kontrak UKT 2 - Seksi {{ $seksi->name ?? '-' }}
                    </h4>
                    <div class="row d-flex justify-content-between align-items-center">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 mb-3 text-left">
                            <div class="d-flex justify-content-start align-items-center flex-wrap">
                                <a href="{{ route('admin-kontrak.index', $seksi->uuid) }}"
                                    class="btn btn-outline-primary mr-2 mb-2 mb-sm-0"><i class="fa fa-arrow-left"></i>
                                    Kembali</a>
                                <a href="{{ route('admin-kontrak.create', $seksi->uuid) }}"
                                    class="btn btn-primary mr-2 mb-2 mb-sm-0">Tambah Data</a>
                                <a href="javascript:;" class="btn btn-primary mr-2 mb-2 mb-sm-0" data-toggle="modal"
                                    data-target="#modalFilter" title="Filter"><i class="fa fa-filter"></i></a>
                                <a href="{{ route('admin-kontrak.index', $seksi->uuid) }}" class="btn btn-primary mr-2 mb-2 mb-sm-0"
                                    title="Reset Filter">
                                    <i class="fa fa-refresh"></i>
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
                                    {{ $kontrak->links('vendor.pagination.bootstrap-4') }}
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <div class="table-responsive mt-2">
                            <table class="table table-bordered table-striped" id="dataTable">
                                <thead>
                                    <tr>
                                        <th class="text-center">No.</th>
                                        <th class="text-center">Nama Kontrak</th>
                                        <th class="text-center">No. Kontrak</th>
                                        <th class="text-center">Nilai Kontrak</th>
                                        <th class="text-center">Seksi</th>
                                        <th class="text-center">Tanggal Pengadaan</th>
                                        <th class="text-center">Dokumen</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kontrak as $item)
                                        <tr>
                                            <td class="text-center">
                                                {{ ($kontrak->currentPage() - 1) * $kontrak->perPage() + $loop->index + 1 }}
                                            </td>
                                            <td>{{ $item->name }}</td>
                                            <td class="text-center">{{ $item->no_kontrak }}</td>
                                            <td class="text-center">{{ formatRupiah($item->nilai_kontrak, true) }}</td>
                                            <td class="text-center">{{ $item->seksi->name }}</td>
                                            <td class="text-center">{{ $item->tanggal }}</td>
                                            <td class="text-center">
                                                <a href="{{ asset('storage/' . $item->lampiran) }}" target="_blank">
                                                    <button class="btn btn-outline-primary">
                                                        <i class="fa fa-file"></i>
                                                    </button>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin-kontrak.edit', $item->uuid) }}"><button
                                                        class="btn btn-outline-primary"><i
                                                            class="fa fa-edit"></i></button></a>
                                                <a href="#" href="javascript:;" data-toggle="modal"
                                                    data-target="#delete-confirmation-modal"
                                                    onclick="toggleModal('{{ $item->id }}')"><button
                                                        class="btn btn-outline-primary"><i
                                                            class="fa fa-trash"></i></button></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if ($kontrak->count() == 0)
                                        <tr>
                                            <td class="text-center" colspan="8">
                                                Tidak ada data.
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
    </div>


    {{-- START: FILTER --}}
    <div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="modalFilter" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filter Data Kontrak</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formFilter" action="{{ route('admin-kontrak.filter', $seksi->uuid) }}" method="GET">
                        @csrf
                        @method('GET')
                        <div class="form-group">
                            <label for="periode">Tahun Pengadaan</label>
                            <select name="periode" class="form-control">
                                <option value="" selected disabled>- pilih periode pengadaan -</option>
                                <option value="{{ $tahun - 1 }}">{{ $tahun - 1 }}</option>
                                <option value="{{ $tahun }}">{{ $tahun }}</option>
                                <option value="{{ $tahun + 1 }}">{{ $tahun + 1 }}</option>
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
                                        <option value="ASC" @if ($sort == 'ASC') selected @endif>A to Z
                                        </option>
                                        <option value="DESC" @if ($sort == 'DESC') selected @endif>Z to A
                                        </option>
                                    </select>
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
    {{-- END: FILTER --}}

    <!-- BEGIN: Delete Confirmation Modal -->
    <div id="delete-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-2">
                    <div class="p-2 text-center">
                        <div class="text-3xl mt-2">Apakah anda yakin?</div>
                        <div class="text-slate-500 mt-2">Peringatan: Data ini akan dihapus secara permanent</div>
                    </div>
                    <div class="px-5 pb-8 text-center mt-3">
                        <form action="{{ route('admin-kontrak.delete') }}" method="POST">
                            @csrf
                            @method('delete')
                            <input type="text" name="id" id="id" hidden>
                            <button type="button" data-dismiss="modal"
                                class="btn btn-dark w-24 mr-1 me-2">Batal</button>
                            <button type="submit" class="btn btn-primary w-24">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Delete Confirmation Modal -->
@endsection


@section('javascript')
    <script type="text/javascript">
        function toggleModal(id) {
            $('#id').val(id);
        }
    </script>
@endsection
