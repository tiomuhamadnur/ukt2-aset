@extends('layout.base_user')

@section('title-head')
    <title>
        Gudang | Gudang Saya
    </title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Gudang</li>
            <li class="breadcrumb-item active">List Barang Gudang Saya</li>
        </ol>
    </div>
@endsection


@section('content')
    <div class="row gutters">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="d-flex justify-content-center mb-3 text-center" style="text-decoration: underline">
                        Gudang Seksi
                        {{ $formasi_tim->struktur->seksi->name ?? '-' }}
                        - Pulau
                        {{ $formasi_tim->area->pulau->name ?? '-' }}
                    </h4>
                    <div class="row d-flex justify-content-between align-items-center">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 mb-3 text-left">
                            <div class="d-flex justify-content-start align-items-center flex-wrap">
                                <a href="{{ route('aset.pjlp.index') }}"
                                    class="btn btn-outline-primary mr-2 mb-2 mb-sm-0"><i class="fa fa-arrow-left"></i>
                                    Kembali</a>
                                <a href="{{ route('aset.pjlp.form-pemakaian') }}"><button type="submit"
                                        class="btn btn-warning mr-2 mb-2 mb-sm-0" form="ambil-barang-form"
                                        id="ambilBarangButton" style="display: none;"> <i class="fa fa-hand"></i> Ambil
                                        Barang
                                    </button></a>
                                {{-- <a href="" class="btn btn-primary mr-2 mb-2 mb-sm-0" data-toggle="modal"
                                    data-target="#modalFilter"><i class="fa fa-filter"></i></a> --}}
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
                    <div>
                        <p>NOTE: Jika barang yang anda cari tidak ada, maka stock barang sudah habis. Silahkan hubungi PIC
                            terkait!</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th class="text-center">
                                        Pilih <br>
                                        <input type="checkbox" id="checkAll">
                                    </th>
                                    <th class="text-center">Nama Barang</th>
                                    <th class="text-center">Spesifikasi Barang</th>
                                    <th class="text-center">Jenis Barang</th>
                                    <th class="text-center">Tahun Pengadaan</th>
                                    <th class="text-center">Stock Awal</th>
                                    <th class="text-center">Stock Saat Ini</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <form id="ambil-barang-form" action="{{ route('aset.pjlp.form-pemakaian') }}"
                                    method="GET">
                                    @csrf
                                    @method('GET')
                                    @foreach ($barang_pulau as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">
                                                <input type="checkbox" class="barang-checkbox" name="barang_pulau_id[]"
                                                    value="{{ $item->id }}">
                                            </td>
                                            <td class="text-left font-weight-bold">{{ $item->barang->name }}</td>
                                            <td class="text-left text-wrap">{{ $item->barang->spesifikasi }}</td>
                                            <td class="text-center">{{ $item->barang->jenis }}</td>
                                            <td class="text-center">
                                                {{ date('d-m-Y', strtotime($item->barang->kontrak->tanggal)) }} <br>
                                                ({{ $item->barang->kontrak->no_kontrak }})
                                            </td>
                                            <td class="text-center">{{ $item->stock_awal }} {{ $item->barang->satuan }}
                                            </td>
                                            <td class="text-center font-weight-bold">{{ $item->stock_aktual }}
                                                {{ $item->barang->satuan }}</td>
                                            <td class="text-center">
                                                <a href="#" class="btn btn-outline-primary" title="Lihat Photo"
                                                    data-toggle="modal" data-target="#modalLampiran"
                                                    data-photo="{{ $item->barang->photo }}"><i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if ($barang_pulau->count() == 0)
                                        <tr>
                                            <td class="text-center" colspan="9">
                                                Data barang tidak ditemukan, kemungkinan barang belum dikirim atau stock
                                                sudah habis.
                                            </td>
                                        </tr>
                                    @endif
                                </form>
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
                    <h5 class="modal-title">Filter Data Barang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row gutters">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <label for="">Kontrak</label>
                                <select name="kontrak_id" class="form-control">
                                    <option value="" selected disabled>- pilih kontrak -</option>
                                    @foreach ($kontrak as $item)
                                        <option value="{{ $item->id }}">{{ $item->no_kontrak }}
                                            - {{ $item->name }}
                                            - {{ $item->seksi->name }} - ({{ $item->tanggal }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Jenis Barang</label>
                                <select name="jenis_barang" class="form-control" required>
                                    <option value="" selected disabled>- pilih jenis barang -</option>
                                    <option value="consumable">Consumable</option>
                                    <option value="tools">Tools</option>

                                </select>
                            </div>
                            <div class="form-group">
                                <label for="periode">Tahun Pengadaan</label>
                                <select name="periode" class="form-control">
                                    <option value="" selected disabled>- pilih periode pengadaan -</option>
                                    <option value="{{ $tahun - 1 }}">{{ $tahun - 1 }}</option>
                                    <option value="{{ $tahun }}">{{ $tahun }}</option>
                                    <option value="{{ $tahun + 1 }}">{{ $tahun + 1 }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary">Filter Data</button>
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
                    <h5 class="modal-title">Lampiran Dokumentasi Barang</h5>
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
@endsection


@section('javascript')
    <script type="text/javascript">
        function toggleModal(id) {
            $('#id').val(id);
        }

        $(document).ready(function() {
            document.getElementById('checkAll').addEventListener('change', function() {
                var checkboxes = document.querySelectorAll('.barang-checkbox');
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = this.checked;
                }, this);
            });

            var checkboxes = document.querySelectorAll('.barang-checkbox');
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    var allChecked = true;
                    checkboxes.forEach(function(cb) {
                        if (!cb.checked) {
                            allChecked = false;
                        }
                    });
                    document.getElementById('checkAll').checked = allChecked;
                });
            });

            $('input[type="checkbox"]').change(function() {
                var diceklis = $('input[type="checkbox"]:checked').length > 0;

                if (diceklis) {
                    $('#ambilBarangButton').show();
                } else {
                    $('#ambilBarangButton').hide();
                }
            });

            $('#modalLampiran').on('show.bs.modal', function(e) {
                var photoArray = $(e.relatedTarget).data('photo');
                var photoHTML = '';

                photoArray.forEach(function(item) {
                    var photoPath = "{{ asset('storage/') }}" + '/' + item;
                    photoHTML +=
                        '<div class""><img class="img-thumbnail img-fluid" style="width: 400px;" src="' +
                        photoPath + '" alt="photo"></div>';
                });

                document.getElementById("photo_modal").innerHTML = photoHTML;
            });
        });
    </script>
@endsection
