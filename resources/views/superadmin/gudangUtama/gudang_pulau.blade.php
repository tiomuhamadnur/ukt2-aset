@extends('layout.base_user')

@section('title-head')
    <title>
        Gudang | Monitoring Stock Gudang
    </title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Gudang</li>
            <li class="breadcrumb-item active">Monitoring Stock Gudang Pulau</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row gutters">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="d-flex justify-content-center mb-3 text-center" style="text-decoration: underline">Daftar Stock
                        Barang Gudang Pulau - Seksi {{ auth()->user()->struktur->seksi->name ?? '-' }}</h4>
                    <div class="row d-flex justify-content-between align-items-center">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 mb-3 text-left">
                            <div class="d-flex justify-content-start align-items-center flex-wrap">
                                <a href="{{ route('aset.kasi.index') }}"
                                    class="btn btn-outline-primary mr-2 mb-2 mb-sm-0"><i class="fa fa-arrow-left"></i>
                                    Kembali</a>
                                {{-- <a href="{{ route('aset.gudang-pulau-trans') }}"
                                    class="btn btn-primary mr-2 mb-2 mb-sm-0"></i>
                                    Lihat Histori Pemakaian</a> --}}
                                <a href="" class="btn btn-primary mr-2 mb-2 mb-sm-0" data-toggle="modal"
                                    data-target="#modalFilter"><i class="fa fa-filter"></i> - pilih gudang pulau -</a>
                                <a href="{{ route('aset.gudang-pulau') }}" class="btn btn-primary mr-2 mb-2 mb-sm-0"
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
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th class="text-center">No. Kontrak</th>
                                    <th class="text-center">Seksi</th>
                                    <th class="text-center">Pulau</th>
                                    <th class="text-center">Nama Barang</th>
                                    <th class="text-center">Merk Barang</th>
                                    <th class="text-center">Jenis Barang</th>
                                    <th class="text-center">Stock Awal</th>
                                    <th class="text-center">Stock Aktual</th>
                                    <th class="text-center">Ketersediaan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($barang_pulau as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $item->barang->kontrak->no_kontrak ?? '-' }}</td>
                                        <td class="text-center">{{ $item->barang->kontrak->seksi->name }}</td>
                                        <td class="text-center">Pulau {{ $item->gudang->pulau->name ?? '-' }}</td>
                                        <td class="text-center font-weight-bold">{{ $item->barang->name }}</td>
                                        <td class="text-center">{{ $item->barang->merk ?? '-' }}</td>
                                        <td class="text-center">{{ $item->barang->jenis ?? '-' }}</td>
                                        <td class="text-center">{{ $item->stock_awal }} {{ $item->barang->satuan }}</td>
                                        <td class="text-center font-weight-bold">{{ $item->stock_aktual }}
                                            {{ $item->barang->satuan }}</td>
                                        <td class="text-center">
                                            @if ($item->stock_aktual == 0)
                                                <span class="btn btn-danger">Habis</span>
                                            @else
                                                <span class="btn btn-primary">Ada</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($barang_pulau->count() == 0)
                                    <tr>
                                        <td class="text-center" colspan="10">
                                            Tidak ada data yang bisa ditampilkan.
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
    <div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="modalFilter" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('aset.gudang-pulau.filter') }}" method="GET">
                @csrf
                @method('GET')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Filter Data Stock Barang</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <label for="">Gudang Pulau</label>
                                    <select name="gudang_id" class="form-control" required>
                                        <option value="" selected disabled>- pilih gudang pulau -</option>
                                        @foreach ($gudang_pulau as $item)
                                            <option value="{{ $item->id }}"
                                                @if ($item->id == $gudang_id) selected @endif>{{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Ketersediaan Barang</label>
                                    <select name="stock" class="form-control">
                                        <option value="" selected disabled>- pilih ketersediaan -</option>
                                        <option value=">" @if ($stock == '>')
                                            selected
                                            @endif>Ada</option>
                                        <option value="=" @if ($stock == '=') selected @endif>Habis
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Kontrak</label>
                                    <select name="kontrak_id" class="form-control">
                                        <option value="" selected disabled>- pilih kontrak -</option>
                                        @foreach ($kontrak as $item)
                                            <option value="{{ $item->id }}"
                                                @if ($item->id == $kontrak_id) selected @endif>{{ $item->no_kontrak }}
                                                - {{ $item->name }}
                                                - {{ $item->seksi->name }} -
                                                ({{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="periode">Tahun Pengadaan</label>
                                    <select name="periode" class="form-control">
                                        <option value="" selected disabled>- pilih periode pengadaan -</option>
                                        <option value="{{ $tahun - 3 }}">{{ $tahun - 3 }}</option>
                                        <option value="{{ $tahun - 2 }}">{{ $tahun - 2 }}</option>
                                        <option value="{{ $tahun - 1 }}">{{ $tahun - 1 }}</option>
                                        <option value="{{ $tahun }}">{{ $tahun }}</option>
                                        <option value="{{ $tahun + 1 }}">{{ $tahun + 1 }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Jenis Barang</label>
                                    <select name="jenis" class="form-control">
                                        <option value="" selected disabled>- pilih jenis barang -</option>
                                        <option value="consumable" @if ($jenis == 'consumable') selected @endif>
                                            Consumable</option>
                                        <option value="tools" @if ($jenis == 'tools') selected @endif>Tools
                                        </option>

                                    </select>
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
