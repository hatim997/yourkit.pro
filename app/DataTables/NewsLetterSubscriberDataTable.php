<?php

namespace App\DataTables;

use App\Models\NewsletterSubscriber;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class NewsLetterSubscriberDataTable extends DataTable
{
    
    public function dataTable(QueryBuilder $query): EloquentDataTable
{
    return (new EloquentDataTable($query))
        ->editColumn('created_at', fn($query) => $query->created_at ? $query->created_at->format('h:i a, d F, Y') : "")
        ->rawColumns(['created_at']);
}

    

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\NewsLetterSubscriber $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(NewsLetterSubscriber $model): QueryBuilder
    {
       // dd($model);
        return $model->newQuery()->latest();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('newsletter-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
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
            Column::make('email'),
            Column::make('created_at')->addClass('text-center')->title('Created on'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'NewsLetterSubscriber_' . date('YmdHis');
    }
}
