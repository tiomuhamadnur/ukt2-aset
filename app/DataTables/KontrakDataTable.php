<?php

namespace App\DataTables;

use App\Models\Kontrak;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class KontrakDataTable extends DataTable
{
    protected $seksi_id;
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
                $editRoute = route('admin-kontrak.edit', $item->uuid);
                $editButton = '
                    <a href="' . $editRoute . '" title="Edit">
                        <button class="btn btn-outline-primary">
                            <i class="fa fa-edit"></i>
                        </button>
                    </a>
                ';
                $deleteButton = '
                    <a href="javascript:;" data-toggle="modal" data-target="#delete-confirmation-modal" title="Hapus"
                        onclick="toggleModal(\'' . $item->id . '\')">
                        <button class="btn btn-outline-danger">
                            <i class="fa fa-trash"></i>
                        </button>
                    </a>
                ';

                return $editButton . $deleteButton;
            })
            ->addColumn('dokumen', function ($item) {
                if ($item->lampiran) {
                    $fileUrl = asset('storage/' . $item->lampiran);
                    return '
                        <a href="' . $fileUrl . '" target="_blank" title="Lihat Dokumen Kontrak">
                            <button class="btn btn-outline-primary">
                                <i class="fa fa-file"></i> Kontrak
                            </button>
                        </a>
                    ';
                }

                return null;
            })
            ->addColumn('dokumen_distribusi', function ($item) {
                $routePDF = route('admin-kontrak.dokumen-distribusi.pdf', $item->uuid);
                $routeExcel = route('admin-kontrak.dokumen-distribusi.excel', $item->uuid);
                $button = '
                    <div class="dropdown">
                        <button class="btn btn-outline-info"
                            id="exportDropdown" role="button" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false" title="Lihat Distribusi Barang">
                            <i class="fa fa-file-text"></i> Distribusi
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                            <li>
                                <a class="dropdown-item text-dark" href="' . $routeExcel . '" target="_blank" title="Export Excel">
                                    <i class="fa fa-file-excel text-primary"></i> Export Excel
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-dark" href="' . $routePDF . '" target="_blank" title="Export PDF">
                                    <i class="fa fa-file-pdf text-danger"></i> Export PDF
                                </a>
                            </li>
                        </ul>
                    </div>
                ';

                return $button;
            })
            ->editColumn('nilai_kontrak', function ($item) {
                return $item->nilai_kontrak !== null
                    ? 'Rp.' . number_format($item->nilai_kontrak, 0, ',', '.')
                    : null;
            })
            ->editColumn('tanggal', function ($item) {
                return Carbon::parse($item->tanggal)->format('d-m-Y');
            })
            ->rawColumns(['dokumen', 'dokumen_distribusi', '#']);
    }

    public function query(Kontrak $model): QueryBuilder
    {
        $query = $model->with([
            'seksi',
        ])->newQuery();

        // Filter
        if($this->seksi_id != null)
        {
            $query->where('seksi_id', $this->seksi_id);
        }

        if ($this->start_date != null && $this->end_date != null) {
            $clean_start_date = explode('?', $this->start_date)[0];
            $clean_end_date = explode('?', $this->end_date)[0];

            $start = Carbon::parse($clean_start_date)->startOfDay()->format('Y-m-d H:i:s');
            $end = Carbon::parse($clean_end_date)->endOfDay()->format('Y-m-d H:i:s');

            $query->whereBetween('tanggal', [$start, $end]);
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('kontrak-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->pageLength(50)
                    ->lengthMenu([10, 50, 100, 250, 500, 1000])
                    ->orderBy([4, 'desc'])
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
            Column::make('name')->title('Nama Kontrak')->sortable(false),
            Column::make('no_kontrak')->title('No. Kontrak')->sortable(false)->addClass('text-center'),
            Column::make('nilai_kontrak')->title('Nilai Kontrak')->sortable(false)->addClass('text-right'),
            Column::make('seksi.name')->title('Seksi')->sortable(false),
            Column::make('tanggal')->title('Tanggal Pengadaan')->sortable(true)->addClass('text-center'),
            Column::computed('dokumen')->title('Dokumen Kontrak')->sortable(false)->addClass('text-center'),
            Column::computed('dokumen_distribusi')->title('Dokumen Distribusi')->sortable(false)->addClass('text-center'),
            Column::computed('#')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center text-nowrap'),
        ];
    }

    protected function filename(): string
    {
        return 'Kontrak_' . date('YmdHis');
    }
}
