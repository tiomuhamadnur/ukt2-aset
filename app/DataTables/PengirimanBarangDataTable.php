<?php

namespace App\DataTables;

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

class PengirimanBarangDataTable extends DataTable
{
    protected $seksi_id;
    protected $gudang_id;
    protected $status;
    protected $start_date;
    protected $end_date;

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
                $route = route('admin.pengiriman.show', $item->no_resi);

                return '
                    <a href="' . $route . '" class="btn btn-outline-primary" title="Lihat Detail">
                        <i class="fa fa-eye"></i>
                    </a>
                ';
            })
            ->addColumn('status', function ($item) {
                $btnClass = $item->status == 'Dikirim' ? 'btn-warning' : 'btn-primary';

                return '
                    <span class="btn ' . $btnClass . ' btn-sm text-white">
                        ' . e($item->status) . '
                    </span>
                ';
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
            ->rawColumns(['status', '#']);
    }

    public function query(PengirimanBarang $model): QueryBuilder
    {
        $query = $model->with([
            'barang.kontrak.seksi',
            'gudang',
            'submitter',
            'receiver',
        ])->newQuery();

        // Filter seksi
        if ($this->seksi_id != null) {
            $query->whereRelation('barang.kontrak', 'seksi_id', '=', $this->seksi_id);
        }

        // Filter gudang
        if ($this->gudang_id != null) {
            $query->where('gudang_id', $this->gudang_id);
        }

        // Filter status
        if ($this->status != null) {
            $query->where('status', $this->status);
        }

        // Filter tanggal kirim
        if ($this->start_date != null && $this->end_date != null) {
            $clean_start_date = explode('?', $this->start_date)[0];
            $clean_end_date = explode('?', $this->end_date)[0];

            $start = Carbon::parse($clean_start_date)->startOfDay()->format('Y-m-d H:i:s');
            $end = Carbon::parse($clean_end_date)->endOfDay()->format('Y-m-d H:i:s');

            $query->whereBetween('tanggal_kirim', [$start, $end]);
        }

        // Ambil hanya satu record per no_resi
        $sub = $query->selectRaw('MIN(id) as id')->groupBy('no_resi');

        // Gunakan whereIn agar seluruh kolom tetap bisa diambil
        $query = $model->with([
            'barang.kontrak.seksi',
            'gudang',
            'submitter',
            'receiver',
        ])->whereIn('id', $sub);

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('pengirimanbarang-table')
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
            Column::make('no_resi')->title('No. Resi')->sortable(false)->addClass('text-center'),
            Column::make('asal')->title('Asal')->sortable(false),
            Column::make('gudang.name')->title('Tujuan')->sortable(false),
            Column::make('tanggal_kirim')->title('Tanggal Kirim')->sortable(true)->addClass('text-center'),
            Column::make('submitter.name')->title('Dikirim Oleh')->sortable(false),
            Column::make('tanggal_terima')->title('Tanggal Terima')->sortable(true)->addClass('text-center'),
            Column::make('receiver.name')->title('Diterima Oleh')->sortable(false),
            Column::make('status')->title('status')->sortable(false)->addClass('text-center'),
            Column::computed('#')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center text-nowrap'),
        ];
    }

    protected function filename(): string
    {
        return 'PengirimanBarang_' . date('YmdHis');
    }
}
