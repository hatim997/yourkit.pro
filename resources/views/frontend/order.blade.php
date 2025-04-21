@extends('frontend.layouts')

@section('title', 'Dashboard')
@section('content')

    <section class="main-wrap">
        <div class="container-xxl">

            <div class="row">
                @include('frontend.includes.authenticate-menu')

                <div class="col-md-9">
                    <div class="rt-sec">
                        <div class="details-hdr">
                            <h2>My Orders</h2>
                        </div>

                        <div class="table-responsive mt-4">
                            <table class="table table-bordered" width="100%" cellspacing="1" cellpadding="2" border="1">
                                <thead>
                                    <tr class="primary">
                                        <th scope="col">Order ID</th>
                                        <th scope="col">Order Total</th>
                                        <th scope="col">Order Date</th>
                                        <th scope="col">Order Status</th>
                                        <th scope="col">Payment Status</th>
                                        <th scope="col">Order Address</th>
                                        <th scope="col">Order Detail</th>
                                    </tr>
                                </thead>


                                <tbody>

                                    @if (!$orders->isEmpty())

                                        @foreach ($orders as $order)
                                            <tr>
                                                <td>{{ $order->orderID }}</td>
                                                <td>${{ $order->final_amount }}</td>
                                                <td> {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') ?? '' }}
                                                </td>
                                                <td>{{ $status[$order->order_status] }}</td>
                                                <td>
                                                    @if ($order->payment_status == 1)
                                                        <span class="bg badge-warning">Pending</span>
                                                    @elseif ($order->payment_status == 2)
                                                        <span class="bg badge-success">Paid</span>
                                                    @elseif ($order->payment_status == 3)
                                                        <span class="bg badge-danger">Failed</span>
                                                    @endif
                                                </td>
                                                <td>{{ $order->address ?? '' }} <br> {{ $order->pincode ?? '' }}<br>
                                                    {{ $order->country ?? '' }}<br> </td>
                                                <td>
                                                    <div class='d-flex justify-content-center align-items-center'>

                                                        <a href="{{ route('frontend.order.detail', $order->id) }}">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="text-center"><td colspan="8">No Order Found</td></tr>

                                    @endif

                                </tbody>
                            </table>
                            <!-- Pagination links -->
                            <div class="d-flex justify-content-center">
                                {{ $orders->links('pagination::bootstrap-5') }} <!-- This will generate pagination links -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection

@push('styles')
@endpush
