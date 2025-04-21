@extends('layouts.master')

@section('title', __('Orders'))

@section('css')
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Orders') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Orders List Table -->
        <div class="card">
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top custom-datatables">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.') }}</th>
                            <th>{{ __('Order #ID') }}</th>
                            <th>{{ __('Order By') }}</th>
                            <th>{{ __('User Email') }}</th>
                            <th>{{ __('User Phone') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Payment Method') }}</th>
                            <th>{{ __('Payment Status') }}</th>
                            <th>{{ __('Order Status') }}</th>
                            <th>{{ __('Transaction #ID') }}</th>
                            <th>{{ __('Created Date') }}</th>
                            @canany(['view order'])<th>{{ __('Action') }}</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $index => $order)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $order->orderID }}</td>
                                <td>{{ isset($order->user) ? $order->user->name : 'No User' }}</td>
                                <td>{{ isset($order->user) ? $order->user->email : 'No Email' }}</td>
                                <td>{{ isset($order->user) ? $order->user->phone : 'No Phone' }}</td>
                                <td>{{ $order->final_amount }}</td>
                                <td>{{ Str::of($order->payment_method)->replace('_', ' ')->title() }}</td>
                                <td>
                                    @php
                                        $statusMap = [
                                            1 => ['label' => 'Pending', 'class' => 'warning'],
                                            2 => ['label' => 'Success', 'class' => 'success'],
                                            3 => ['label' => 'Failed', 'class' => 'danger'],
                                            4 => ['label' => 'Hold', 'class' => 'secondary'],
                                        ];
                                        $status = $statusMap[$order->payment_status] ?? ['label' => 'Unknown', 'class' => 'dark'];
                                    @endphp

                                    <span class="badge me-4 bg-label-{{ $status['class'] }}">
                                        {{ $status['label'] }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $orderStatusMap = [
                                            1 => ['label' => 'Under Review', 'class' => 'warning'],
                                            2 => ['label' => 'Design Approved', 'class' => 'success'],
                                            3 => ['label' => 'Waiting for Garments', 'class' => 'info'],
                                            4 => ['label' => 'Sent to Graphic Designer', 'class' => 'primary'],
                                            5 => ['label' => 'In Production', 'class' => 'secondary'],
                                        ];
                                        $orderStatus = $orderStatusMap[$order->order_status] ?? ['label' => 'Unknown', 'class' => 'dark'];
                                    @endphp

                                    <span class="badge me-4 bg-label-{{ $orderStatus['class'] }}">
                                        {{ $orderStatus['label'] }}
                                    </span>
                                </td>
                                <td>{{ $order->transaction_id ?? 'No ID' }}</td>
                                <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                @canany(['view order'])
                                    <td class="d-flex">
                                        @canany(['view order'])
                                            <span class="text-nowrap">
                                                <a href="{{route('dashboard.orders.show', $order->id)}}"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('View Order Details') }}">
                                                    <i class="ti ti-eye ti-md"></i>
                                                </a>
                                            </span>
                                            <span class="text-nowrap">
                                                <a href="{{route('dashboard.orders.invoice', $order->id)}}"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Download Invoice') }}">
                                                    <i class="ti ti-download ti-md"></i>
                                                </a>
                                            </span>
                                        @endcan
                                    </td>
                                @endcan
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
    </script>
@endsection
