@extends('layout.base_user')

@section('title-head')
    <title>
        Gudang | Gudang Utama
    </title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Gudang</li>
            <li class="breadcrumb-item active">Daftar Barang Gudang Utama</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row gutters">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="d-flex justify-content-center mb-3 text-center" style="text-decoration: underline">Daftar
                        Barang
                        Gudang Utama - Seksi {{ auth()->user()->struktur->seksi->name ?? '-' }}</h4>
                    <div class="row d-flex justify-content-between align-items-center">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 mb-3 text-left">
                            <div class="d-flex justify-content-start align-items-center flex-wrap">
                                <a href="{{ route('aset.kasi.index') }}"
                                    class="btn btn-outline-primary mr-2 mb-2 mb-sm-0"><i class="fa fa-arrow-left"></i>
                                    Kembali</a>
                                <a href="{{ route('aset.gudang-utama.create') }}"
                                    class="btn btn-primary mr-2 mb-2 mb-sm-0">Tambah Barang</a>
                                <button type="submit" form="form-kirim" id="kirimBarangButton"
                                    class="btn btn-warning mr-2 mb-2 mb-sm-0" style="display: none;">
                                    <i class="fa fa-paper-plane"></i>
                                    Kirim Barang
                                </button>
                                <a href="javascript:;" class="btn btn-primary mr-2 mb-2 mb-sm-0" data-toggle="modal"
                                    data-target="#modalFilter" title="Filter"><i class="fa fa-filter"></i></a>
                                <a href="{{ route('aset.gudang-utama') }}" class="btn btn-primary mr-2 mb-2 mb-sm-0"
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
                    <div class="row container">
                        <p>Note: Untuk bisa memilih barang, wajib mengupload photo barang terlebih dahulu dan juga ketika
                            stock
                            aktual barang tersedia.</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th class="text-center">
                                        Pilih <br>
                                        @if ($validasiKirimBarangCheckbox > 0)
                                            <input type="checkbox" class="barang-checkbox" id="checkAll">
                                        @endif
                                    </th>
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center">No. Kontrak</th>
                                    <th class="text-center">Seksi</th>
                                    <th class="text-center">Nama Barang</th>
                                    <th class="text-center text-wrap">Spesifikasi Barang</th>
                                    <th class="text-center">Merk Barang</th>
                                    <th class="text-center">Jenis Barang</th>
                                    <th class="text-center">Harga <br> (Termasuk PPN)</th>
                                    {{-- <th class="text-center">Kode Barang</th> --}}
                                    <th class="text-center">Stock Awal</th>
                                    <th class="text-center">Stock Akhir</th>
                                    {{-- <th class="text-center">Harga Barang</th> --}}
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <form id="form-kirim" action="{{ route('aset.pengiriman.create') }}" method="GET">
                                    @csrf
                                    @method('GET')
                                    @foreach ($barang as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center checkbox">
                                                @if ($item->photo != null)
                                                    @if ($item->stock_aktual > 0)
                                                        <input type="checkbox" class="barang-checkbox" name="barang_id[]"
                                                            value="{{ $item->id }}">
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-center text-nowrap">
                                                {{ isset($item->kontrak->tanggal) ? \Carbon\Carbon::parse($item->kontrak->tanggal)->format('d-m-Y') : '-' }}
                                            </td>
                                            <td class="text-center">{{ $item->kontrak->no_kontrak }}</td>
                                            <td class="text-center">{{ $item->kontrak->seksi->name }}</td>
                                            <td class="text-left font-weight-bolder">{{ $item->name }}</td>
                                            <td class="text-left text-wrap">{{ $item->spesifikasi }}</td>
                                            <td class="text-left">{{ $item->merk }}</td>
                                            <td class="text-center">
                                                @if ($item->jenis == 'consumable')
                                                    Barang Persediaan
                                                @elseif ($item->jenis == 'tools')
                                                    Barang Modal/Aset
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-center">{{ formatRupiah($item->harga, true) }}</td>
                                            {{-- <td class="text-center">{{ $item->code }}</td> --}}
                                            <td class="text-center">{{ $item->stock_awal }} {{ $item->satuan }}</td>
                                            <td class="text-center font-weight-bolder">{{ $item->stock_aktual }}
                                                {{ $item->satuan }}</td>
                                            {{-- <td class="text-center">Rp.{{ $item->harga }}</td> --}}
                                            <td class="text-center">
                                                <a href="{{ route('aset.gudang-utama.edit', $item->uuid) }}"
                                                    class="btn btn-outline-primary" title="Edit"><i
                                                        class="fa fa-edit"></i>
                                                </a>
                                                @if ($item->photo != null)
                                                    <a href="#" class="btn btn-outline-primary" title="Lihat Photo"
                                                        data-toggle="modal" data-target="#modalLampiran"
                                                        data-photo="{{ $item->photo }}"><i class="fa fa-eye"></i>
                                                    </a>
                                                @endif
                                                @if ($item->photo == null)
                                                    <a href="#" href="javascript:;" title="Hapus" data-toggle="modal"
                                                        data-target="#delete-confirmation-modal"
                                                        onclick="toggleModal('{{ $item->id }}')"
                                                        class="btn btn-outline-danger"><i class="fa fa-trash"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if ($barang->count() == 0)
                                        <tr>
                                            <td class="text-center" colspan="12">
                                                Data barang tidak ditemukan, kemungkinan stock barang sudah habis silahkan
                                                hubungi
                                                PIC terkait.
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
                        <form action="{{ route('aset.gudang-utama.delete') }}" method="POST">
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


    {{-- BEGIN: Filter Modal --}}
    <div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="modalFilter"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('aset.gudang-utama.filter') }}" method="GET">
                @csrf
                @method('GET')
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
                                            <option value="{{ $item->id }}"
                                                @if ($item->id == $kontrak_id) selected @endif>{{ $item->no_kontrak }}
                                                - {{ $item->name }}
                                                - {{ $item->seksi->name }} - ({{ $item->tanggal }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="periode">Tahun Pengadaan</label>
                                    <select name="periode" class="form-control">
                                        <option value="" selected disabled>- pilih periode pengadaan -</option>
                                        <option value="{{ $tahun - 4 }}">{{ $tahun - 4 }}</option>
                                        <option value="{{ $tahun - 3 }}">{{ $tahun - 3 }}</option>
                                        <option value="{{ $tahun - 2 }}">{{ $tahun - 2 }}</option>
                                        <option value="{{ $tahun - 1 }}">{{ $tahun - 1 }}</option>
                                        <option value="{{ $tahun }}">{{ $tahun }}</option>
                                        <option value="{{ $tahun + 1 }}">{{ $tahun + 1 }}</option>
                                        <option value="{{ $tahun + 2 }}">{{ $tahun + 2 }}</option>
                                        <option value="{{ $tahun + 3 }}">{{ $tahun + 3 }}</option>
                                        <option value="{{ $tahun + 4 }}">{{ $tahun + 4 }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Jenis Barang</label>
                                    <select name="jenis" class="form-control">
                                        <option value="" selected disabled>- pilih jenis barang -</option>
                                        <option value="consumable" @if ($jenis == 'consumable') selected @endif>
                                            Barang Persediaan</option>
                                        <option value="tools" @if ($jenis == 'tools') selected @endif>Barang
                                            Modal/Aset
                                        </option>
                                    </select>
                                </div>
                                <div class="form-row gutters">
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="form-group">
                                            <label for="">Ketersediaan</label>
                                            <select name="stock" class="form-control">
                                                <option value="">- pilih ketersediaan stock -</option>
                                                <option value=">" @if ($stock == '>')
                                                    selected
                                                    @endif>Ada
                                                </option>
                                                <option value="=" @if ($stock == '=') selected @endif>
                                                    Habis
                                                </option>
                                            </select>
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Filter Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- END: Filter Modal --}}
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
                    $('#kirimBarangButton').show();
                } else {
                    $('#kirimBarangButton').hide();
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
