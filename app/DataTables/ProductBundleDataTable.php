<?php

namespace App\DataTables;

use App\Models\Bundle;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductBundleDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'admin.productbundle.action')
            ->editColumn('image', function($bundle){
                return !is_null($bundle) ? '<img src="'. url('storage/'. $bundle->image) .'" alt="">' : '';
            })
            ->editColumn('status', function($query){
                return $query->status == 1 ? '<span class="bg badge-success">Active</span>' : '<span class="bg badge-danger">Inactive</span>' ;
            })
            ->setRowId('id')
            ->rawColumns(['action', 'image','status']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ProductBundle $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Bundle $model): QueryBuilder
    {
        return $model->orderBy('updated_at', 'DESC')->newQuery();
        //return $model->with('products')->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('bundle-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->ordering(false)
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::make('name'),
            Column::make('price'),
            Column::make('image'),
            Column::make('status'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Bundle_' . date('YmdHis');
    }
}
