<?php

namespace App\DataTables;

use App\Models\Order;
use App\Utils\Helper;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class OrderDataTable extends DataTable
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
            ->addColumn('action', 'admin.order.action')
            ->editColumn('created_at', fn($query)=> \Carbon\Carbon::parse($query->created_at)->format('h:i a, d F, Y') ?? "")
            ->editColumn('payment_status', function($order) {
                $status = Helper::PaymentStatus();
                return !is_null($order->payment_status) ? $status[$order->payment_status] : "";
            })
            ->editColumn('order_status', function($order) {
                $status = Helper::OrderStatus();
                return !is_null($order->order_status) ? $status[$order->order_status] : "";
            })
            ->editColumn('user_id', fn($query)=> $query->user->name ?? '')
            ->editColumn('user.email', fn($query)=> $query->user->email ?? '')
            ->editColumn('user.phone', fn($query)=> $query->user->phone ?? '')
            ->rawColumns(['status','payment_status','created_at','action','logo']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Order $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Order $model): QueryBuilder
    {
        return $model->with([ 'user'])->orderByDesc('created_at')->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('order-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(1)
                     ->ordering(false)
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
            Column::make('orderID')->addClass('text-center')->title('Order ID'),
            Column::make('user_id')->addClass('text-center')->title('Ordered By'),
            Column::make('user.email')->addClass('text-center')->title('User Email'),
            Column::make('user.phone')->addClass('text-center')->title('User Phone'),
            Column::make('final_amount')->addClass('text-center')->title('Order Amount'),

            Column::make('payment_method')->addClass('text-center'),
            Column::make('payment_status')->addClass('text-center'),
            Column::make('order_status')->addClass('text-center'),
            Column::make('transaction_id'),
            Column::make('created_at')->addClass('text-center')->title('Order Date'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Order_' . date('YmdHis');
    }
}
