<?php

namespace App\DataTables;

use App\Models\Barang;
use App\Models\Gudang;
use App\Models\GudangUtama;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class GudangUtamaDataTable extends DataTable
{
    protected $seksi_id;
    protected $kontrak_id;
    protected $stock;
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
                $editRoute = route('admin.gudang-utama.edit', $item->uuid);

                // Tombol Edit
                $editButton = '
                    <a href="' . $editRoute . '" class="btn btn-outline-primary" title="Edit">
                        <i class="fa fa-edit"></i>
                    </a>
                ';

                // Tombol Lihat Photo kalau photo ada
                $photoButton = '';
                if ($item->photo != null) {
                    $photoData = htmlspecialchars(json_encode(json_decode($item->photo)), ENT_QUOTES, 'UTF-8');
                    $photoButton = '
                        <a href="#" class="btn btn-outline-primary" title="Lihat Photo"
                            data-toggle="modal" data-target="#modalLampiran"
                            data-photo="' . $photoData . '">
                            <i class="fa fa-eye"></i>
                        </a>
                    ';
                }

                // Tombol Hapus kalau photo tidak ada
                $deleteButton = '';
                if ($item->photo == null) {
                    $deleteButton = '
                        <a href="javascript:;" title="Hapus" data-toggle="modal"
                            data-target="#delete-confirmation-modal"
                            onclick="toggleModal(\'' . $item->id . '\')"
                            class="btn btn-outline-danger">
                            <i class="fa fa-trash"></i>
                        </a>
                    ';
                }

                return $editButton . $photoButton . $deleteButton;
            })
            ->addColumn('pilih', function ($item) {
                $checkbox = '';

                if ($item->photo != null) {
                    if ($item->stock_aktual > 0) {
                        $checkbox = '
                            <input type="checkbox" class="barang-checkbox" name="barang_id[]" value="' . $item->id . '" style="transform: scale(1.5); margin: 5px;">
                        ';
                    }
                }

                return $checkbox;
            })
            ->editColumn('harga', function ($item) {
                return $item->harga !== null
                    ? 'Rp.' . number_format($item->harga, 0, ',', '.')
                    : null;
            })
            ->editColumn('kontrak.tanggal', function ($item) {
                return Carbon::parse($item->kontrak->tanggal)->format('d-m-Y');
            })
            ->rawColumns(['pilih', '#']);
    }

    public function query(Barang $model): QueryBuilder
    {
        $query = $model->select('barang.*')->with([
            'kontrak.seksi',
        ])->newQuery();

        // Filter seksi
        if ($this->seksi_id != null) {
            $query->whereRelation('kontrak', 'seksi_id', '=', $this->seksi_id);
        }

        // Filter kontrak
        if ($this->kontrak_id != null) {
            $query->where('kontrak_id', $this->kontrak_id);
        }

        // Filter jenis
        if ($this->jenis != null) {
            $query->where('jenis', $this->jenis);
        }

        // Filter stock
        if ($this->stock != null) {
            $query->where('stock_aktual', $this->stock, 0);
        }

        // Filter tanggal dari relasi kontrak
        if ($this->start_date != null && $this->end_date != null) {
            $clean_start_date = explode('?', $this->start_date)[0];
            $clean_end_date   = explode('?', $this->end_date)[0];

            $start = Carbon::parse($clean_start_date)->startOfDay()->format('Y-m-d H:i:s');
            $end   = Carbon::parse($clean_end_date)->endOfDay()->format('Y-m-d H:i:s');

            $query->whereHas('kontrak', function ($q) use ($start, $end) {
                $q->whereBetween('tanggal', [$start, $end]);
            });
        }

        // Urutkan agar yang punya checkbox (photo ≠ null dan stock_aktual > 0) muncul di atas
        $query->orderByRaw('
            CASE
                WHEN photo IS NOT NULL AND stock_aktual > 0 THEN 0
                ELSE 1
            END
        ');

        // (opsional) Tambahan urutan kedua, misal berdasarkan tanggal kontrak atau ID terbaru
        $query->orderByDesc('id');

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('gudangutama-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->pageLength(-1)
                    ->lengthMenu([[-1], ['Semua']])
                    ->paging(false)
                    // ->orderBy([1, 'desc'])
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
            Column::computed('pilih')
                    // ->title('Pilih <br><input style="transform: scale(1.5); margin: 5px;" type="checkbox" id="checkAll">')
                    ->title('
                        <input type="checkbox" id="checkAllGlobal" style="transform: scale(1.5); margin: 5px;"> Pilih Semua
                    ')
                    ->sortable(false)
                    ->addClass('text-center text-nowrap'),
            Column::make('kontrak.tanggal')->title('Tanggal')->sortable(true)->addClass('text-center'),
            Column::make('kontrak.no_kontrak')->title('No. Kontrak')->sortable(false)->addClass('text-center'),
            // Column::make('kontrak.seksi.name')->title('Seksi')->sortable(false),
            Column::make('name')->title('Nama Barang')->sortable(false)->addClass('font-weight-bold'),
            Column::make('merk')->title('Merk Barang')->sortable(false),
            Column::make('jenis')->title('Jenis Barang')->sortable(false),
            Column::make('harga')->title('Harga (Termasuk PPN)')->sortable(false)->addClass('text-right text-nowrap'),
            Column::make('stock_awal')->title('Stock Awal')->sortable(false)->addClass('text-center'),
            Column::make('stock_aktual')->title('Stock Aktual')->sortable(false)->addClass('text-center'),
            Column::make('spesifikasi')->title('Spesifikasi')->sortable(false),
            Column::computed('#')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center text-nowrap'),
        ];
    }

    protected function filename(): string
    {
        return 'GudangUtama_' . date('YmdHis');
    }
}
