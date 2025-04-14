<?php

namespace App\DataTables;

use App\Models\EmailTemplate;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class EmailTemplateDataTable extends DataTable
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
            ->addColumn('action', 'admin.email_template.datatables_actions')
            ->editColumn('status', fn($query)=> EmailTemplate::$statusCodes[$query->status] ?? "")
            ->rawColumns(['action','status']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\EmailTemplate $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(EmailTemplate $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
        ->columns($this->getColumns())
        ->minifiedAjax()
        ->addAction(['width' => '120px', 'printable' => false])
        ->parameters([
           // 'dom'       => 'Bfrtip',
            'stateSave' => true,
            "ordering" => false,
            "searching" => true,
            'order'     => [[0, 'desc']],
            'buttons'   => [
                // Enable Buttons as per your need
                //                    ['extend' => 'create', 'className' => 'btn btn-default btn-sm no-corner',],
                //                    ['extend' => 'export', 'className' => 'btn btn-default btn-sm no-corner',],
                //                    ['extend' => 'print', 'className' => 'btn btn-default btn-sm no-corner',],
                //                    ['extend' => 'reset', 'className' => 'btn btn-default btn-sm no-corner',],
                //                    ['extend' => 'reload', 'className' => 'btn btn-default btn-sm no-corner',],
            ],
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
          
        
            Column::make('subject'),
            //Column::make('content'),
            //Column::make('status'),
           
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'EmailTemplate_' . date('YmdHis');
    }
}
