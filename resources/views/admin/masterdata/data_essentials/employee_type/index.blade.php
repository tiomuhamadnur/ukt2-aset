@extends('layout.base')

@section('title-head')
    <title>
        Masterdata | Daftar Employee Type
    </title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Masterdata</li>
            <li class="breadcrumb-item">Data Essentials</li>
            <li class="breadcrumb-item">Employee Type</li>
            <li class="breadcrumb-item active">Daftar Employee Type</li>
        </ol>
    </div>
@endsection


@section('content')
    <div class="row gutters">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="btn-group">
                                <a class="btn btn-outline-primary mb-3" href="{{ route('data_essentials.index') }}">
                                    <i class="fa fa-arrow-left"></i>
                                    Kembali
                                </a>
                                <a class="btn btn-primary mb-3" href="{{ route('employee-type.create') }}">
                                    Tambah Data
                                </a>
                            </div>
                        </div>
                        <form class="form-inline mb-2">
                            <input class="form-control mr-sm-2" type="search" placeholder="Cari sesuatu di sini..."
                                aria-label="Search" id="search-bar">
                            <button class="btn btn-dark my-2 my-sm-0" type="submit">Pencarian</button>
                        </form>
                        <table class="table table-bordered table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Kode</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employee_type as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $item->name }}</td>
                                        <td class="text-center">{{ $item->code }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('employee-type.show', $item->uuid) }}"><button
                                                    class="btn btn-outline-primary"><i class="fa fa-edit"></i></button></a>
                                            <a href="#" href="javascript:;" data-toggle="modal"
                                                data-target="#delete-confirmation-modal"
                                                onclick="toggleModal('{{ $item->id }}')"><button
                                                    class="btn btn-outline-danger"><i class="fa fa-trash"></i></button></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                        <form action="{{ route('employee-type.destroy') }}" method="POST">
                            @csrf
                            @method('delete')
                            <input type="text" name="id" id="id" hidden>
                            <button type="button" data-dismiss="modal" class="btn btn-dark w-24 mr-1 me-2">Batal</button>
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
