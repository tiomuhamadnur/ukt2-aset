<?php

namespace App\DataTables;

use App\Models\DetailPengirimanBarang;
use App\Models\PengirimanBarang;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class DetailPengirimanBarangDataTable extends DataTable
{
    protected $no_resi;

    public function with(array|string $key, mixed $value = null): static
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->{$k} = $v;
            }
        } else {
            $this->{$key} = $value;
        }

        return $this;
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('#', function ($item) {
                $photoKirim = $item->photo_kirim ? e($item->photo_kirim) : '';
                $photoTerima = $item->photo_terima ? e($item->photo_terima) : '';
                $uploadUrl = route('admin-barang.photo.terima', $item->id);

                $viewButton = '<a href="javascript:;" class="btn btn-outline-primary"
                                    title="Lihat Dokumentasi" data-toggle="modal"
                                    data-target="#modalLampiran"
                                    data-kirim="' . $photoKirim . '"
                                    data-terima="' . $photoTerima . '">
                                    <i class="fa fa-eye"></i>
                                </a>';

                $uploadButton = '<a href="javascript:;" data-url="' . $uploadUrl . '"
                                    data-toggle="modal" data-target="#modalPhotoTerima"
                                    class="btn btn-outline-primary" title="Upload Photo Bukti Terima">
                                    <i class="fa fa-edit"></i>
                                </a>';

                return $viewButton . ' ' . $uploadButton;
            })
            ->addColumn('status', function ($item) {
                $btnClass = $item->status == 'Dikirim' ? 'btn-warning' : 'btn-primary';

                return '
                    <span class="btn ' . $btnClass . ' btn-sm text-white">
                        ' . e($item->status) . '
                    </span>
                ';
            })
            ->addColumn('photo_kirim', function ($item) {
                $photoUrl = $item->photo_kirim ? asset('storage/' . $item->photo_kirim) : null;

                $img = $photoUrl
                    ? '<a href="' . $photoUrl . '" target="_blank">
                                <img src="' . $photoUrl . '" class="img-thumbnail" style="max-width:70px;" alt="photo_kirim">
                            </a>'
                    : '-';

                return $img;
            })
            ->addColumn('photo_terima', function ($item) {
                if (!$item->photo_terima) return '-';

                $photos = json_decode($item->photo_terima, true);

                return implode('', array_map(function ($photo) {
                    $url = asset('storage/' . $photo);
                    return '<a href="' . $url . '" target="_blank">
                                <img src="' . $url . '" class="img-thumbnail" style="max-width:70px;" alt="photo_terima">
                            </a>';
                }, $photos));
            })
            ->editColumn('asal', function ($item) {
                return 'Gudang Utama';
            })
            ->editColumn('tanggal_kirim', function ($item) {
                return Carbon::parse($item->tanggal_kirim)->format('d-m-Y');
            })
            ->editColumn('tanggal_terima', function ($item) {
                return $item->status === 'Dikirim' || !$item->tanggal_terima
                    ? null
                    : Carbon::parse($item->tanggal_terima)->format('d-m-Y');
            })
            ->rawColumns(['photo_kirim', 'photo_terima', 'status', '#']);
    }

    public function query(PengirimanBarang $model): QueryBuilder
    {
        $query = $model->with([
            'barang.kontrak.seksi',
            'gudang',
            'submitter',
            'receiver',
        ])->newQuery();

        // Filter nomor resi
        if ($this->no_resi != null) {
            $query->where('no_resi', $this->no_resi);
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('detailpengirimanbarang-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->pageLength(50)
                    ->lengthMenu([10, 50, 100, 250, 500, 1000])
                    ->orderBy([3, 'desc'])
                    ->selectStyleSingle()
                    ->buttons([
                        [
                            'extend' => 'excel',
                            'text' => 'Export to Excel',
                            'attr' => [
                                'id' => 'datatable-excel',
                                'style' => 'display: none;',
                            ],
                        ],
                    ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('barang.name')->title('Nama Barang')->sortable(false),
            Column::make('asal')->title('Asal')->sortable(false),
            Column::make('gudang.name')->title('Tujuan')->sortable(false),
            Column::make('tanggal_kirim')->title('Tanggal Kirim')->sortable(true)->addClass('text-center'),
            Column::make('submitter.name')->title('Dikirim Oleh')->sortable(false),
            Column::computed('photo_kirim')->title('Photo Kirim')->sortable(false)->addClass('text-center text-nowrap'),
            Column::make('tanggal_terima')->title('Tanggal Terima')->sortable(true)->addClass('text-center'),
            Column::make('receiver.name')->title('Diterima Oleh')->sortable(false),
            Column::computed('photo_terima')->title('Photo Terima')->sortable(false)->addClass('text-center text-nowrap'),
            Column::make('status')->title('status')->sortable(false)->addClass('text-center'),
            Column::computed('#')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center text-nowrap'),
        ];
    }

    protected function filename(): string
    {
        return 'DetailPengirimanBarang_' . date('YmdHis');
    }
}
