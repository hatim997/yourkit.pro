@extends('admin.master')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">

            <div class="mb-3 d-flex justify-content-between">
                <h1 class="h3 d-inline align-middle">Order-{{ $odr->orderID }}</h1>



            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="card" style="min-height: 285px;">
                        <div class="card-body">

                            <form action="{{ route('admin.orders.update', $odr->id) }}" class="post-job-form" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="product_photos" class="form-label">Upload image
                                        with logo:</label>
                                    <input type="file" name="image[]" multiple
                                        class="form-control @error('image') is-invalid @enderror" id="id_product_photos"
                                        accept="image/png, image/jpg, image/jpeg">
                                    <small class="form-text text-muted">You can upload multiple images (PNG, JPG,
                                        JPEG).</small>

                                    @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <div class="d-flex justify-content-end">
                                    <button type="submit" id="jobPostSubmitBtn" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>

                    </div>

                </div>


                <div class="col-md-3">
                    <div class="card" style="min-height: 285px;">
                        <div class="card-body">
                            <h2>Shipping Information</h2>
                            @if ($odr->shipping != 2)
                                <p>Name : {{ $odr->billing_name ?? '' }}</p>
                                <p>Company Name : {{ $odr->company_name ?? '' }}</p>
                                <p>Email : {{ $odr->billing_email }}</p>
                                <p>Phone : {{ $odr->billing_mobile }}</p>
                                <p>Address : @if (isset($odr->address))
                                        {{ $odr->address }} , {{ $odr->town }} ,{{ $odr->country }} ,
                                        {{ $odr->pincode }}
                                    @endif
                                </p>
                            @else
                                <p>Name : {{ $odr->shipping_name ?? '' }}</p>
                                <p>Company Name : {{ $odr->shipping_company ?? '' }}</p>
                                <p>Email : {{ $odr->shipping_email }}</p>
                                <p>Phone : {{ $odr->shipping_mobile }}</p>
                                <p>Address : @if (isset($odr->shipping_address))
                                        {{ $odr->shipping_address }} , {{ $odr->shipping_town }}
                                        ,{{ $odr->shipping_country }} , {{ $odr->shipping_pincode }}
                                    @endif
                                </p>
                            @endif
                        </div>

                    </div>

                </div>

                <div class="col-md-3">
                    <div class="card" style="min-height: 285px;">
                        <div class="card-body">
                            <h2>Order Status:</h2>
                            <div class="mb-3">
                                <select class="form-select" id="orderStatus" data-order-id="{{ $odr->id }}">
                                    <option value="" disabled>Select Status</option>

                                    @foreach ($status as $key => $st)
                                        <option value="{{ $key }}"
                                            {{ isset($odr) && $odr->order_status == $key ? 'selected' : '' }}>
                                            {{ $st }}</option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="d-flex justify-content text-center orderStatusTxt">
                                <h3>{{ $status[$odr->order_status] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card" style="min-height: 285px;">
                        <div class="card-body">
                            <h2>Order Date and Time:</h2>
                            <div class="d-flex justify-content text-center">
                                <h3 style="color: orange;">
                                    {{ \Carbon\Carbon::parse($odr->created_at)->format('jS F, Y g:i a') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <div class="card">
            <div class="card-body">

                <ul class="nav nav-tabs" id="orderDetailTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="order-detail-tab" data-bs-toggle="tab" href="#order-detail"
                            role="tab" aria-controls="order-detail" aria-selected="true"><strong>Order
                                Details</strong></a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="logo-tab" data-bs-toggle="tab" href="#logo" role="tab"
                            aria-controls="logo" aria-selected="true"><strong>Logo</strong></a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="products-tab" data-bs-toggle="tab" href="#products" role="tab"
                            aria-controls="products" aria-selected="false"><strong> Display product with
                                logo</strong></a>
                    </li>

                </ul>


                <div class="tab-content" id="orderDetailTabsContent">
                    <!-- Order Details Tab -->
                    <div class="tab-pane fade show active" id="order-detail" role="tabpanel"
                        aria-labelledby="order-detail-tab">
                        <div class="row mt-3">
                            <div class="col-12">
                                <table class="table table-bordered" width="100%" cellspacing="1" cellpadding="2"
                                    border="1">
                                    <thead>
                                        <tr class="primary">
                                            <th scope="col">Sr. No.</th>
                                            <th scope="col">Item Name & Order Detail</th>
                                            <th scope="col">Product Images</th>
                                            {{-- <th scope="col"></th> --}}
                                            <th scope="col" class="text-center">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @php
                                            $i = 0;
                                        @endphp
                                        @if ($groupedOrders->isNotEmpty())
                                            @foreach ($groupedOrders as $cartId => $bundles)
                                                @foreach ($bundles as $bundleId => $orders)
                                                    @php
                                                        $k = 0;
                                                        $bundle = App\Models\Bundle::find($bundleId);
                                                    @endphp

                                                    <tr>
                                                        <td>{{ ++$i }}</td>
                                                        <td><strong>{{ $bundle->name }}</strong></td>
                                                        <td class="text-center"> <img
                                                                src="{{ url(asset('storage/' . $bundle->image)) }}"
                                                                alt="{{ $bundle->image }}" width="50"
                                                                height="50">
                                                        </td>
                                                        <td><strong>Bundle Price: ${{ $bundle->price }}</strong></td>
                                                    </tr>
                                                    @foreach ($orders as $order)
                                                        @php

                                                            //$bundle = App\Models\Bundle::find($order->bundle_id);

                                                            $pr_aatr = '';
                                                            $attrContents = json_decode($order->attributes);
                                                            if (!empty($order->bundle->products[$k]['id'])) {
                                                                $pr_aatr = App\Models\ProductAttribute::where(
                                                                    'product_id',
                                                                    $order->bundle->products[$k]['id'],
                                                                )
                                                                    ->where('value', $attrContents->attr_id)
                                                                    ->first();
                                                            }
                                                        @endphp

                                                        <tr>
                                                            <td></td>
                                                            <td>
                                                                <h4>{{ $order->bundle->products[$k]['name'] ?? '' }}</h4>
                                                                <ul>
                                                                    <li><strong>Colour:</strong>

                                                                        <span
                                                                            style="background:{{ $attrContents->color }}; color:#ccc; padding:10px; display:inline-block; border:1px solid #ccc; width:20px; height:20px;">
                                                                        </span>
                                                                    </li>
                                                                    <li><strong>Quantities:</strong>
                                                                        @foreach ($attrContents->size as $size)
                                                                            @if (isset($size->quantity) && $size->quantity > 0)
                                                                                {{ $size->attribute_value . '-' . $size->quantity . ', ' }}
                                                                            @endif
                                                                        @endforeach
                                                                    </li>
                                                                    <li><strong>Locations:</strong>
                                                                        @php
                                                                            $positions = json_decode(
                                                                                $order->positions,
                                                                                true,
                                                                            );
                                                                            $position_image = [];
                                                                            if (is_array($positions)) {
                                                                                foreach ($positions as $position) {
                                                                                    if (is_array($position)) {
                                                                                        foreach ($position as $pos) {
                                                                                            $image = App\Models\PositionImage::where(
                                                                                                'id',
                                                                                                $pos,
                                                                                            )
                                                                                                ->pluck('image')
                                                                                                ->first();
                                                                                            $position_image[] = !is_null(
                                                                                                $image,
                                                                                            )
                                                                                                ? url(
                                                                                                    'assets/frontend/' .
                                                                                                        $image,
                                                                                                )
                                                                                                : '';
                                                                                        }
                                                                                    } else {
                                                                                        $image = App\Models\PositionImage::where(
                                                                                            'id',
                                                                                            $position,
                                                                                        )
                                                                                            ->pluck('image')
                                                                                            ->first();
                                                                                        $position_image[] = !is_null(
                                                                                            $image,
                                                                                        )
                                                                                            ? url(
                                                                                                'assets/frontend/' .
                                                                                                    $image,
                                                                                            )
                                                                                            : '';
                                                                                    }
                                                                                }
                                                                            }
                                                                            $order->position_images = $position_image;
                                                                        @endphp
                                                                        @foreach ($order->position_images as $image)
                                                                            <span class="avater-img"><img
                                                                                    style="height: 25px; width:25px;"
                                                                                    src="{{ $image }}"
                                                                                    alt="" /></span>
                                                                        @endforeach
                                                                    </li>
                                                                </ul>
                                                                @php
                                                                    $k++;
                                                                @endphp
                                                            </td>
                                                            <td class="text-center">
                                                                @if (!empty($pr_aatr))
                                                                    <span class="avater-img"><img
                                                                            src="{{ url(asset('storage/' . $pr_aatr->image)) }}"
                                                                            class="img-fluid img-thumbnail" alt=""
                                                                            style="width: 150px; height: auto;" /></span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <p>Print Email On Back:
                                                                    {{ isset($order->is_email_checked) && $order->is_email_checked == 1 ? 'YES' : 'NO' }}
                                                                </p>
                                                                <p>Print Phone No On Back:
                                                                    {{ isset($order->is_phone_checked) && $order->is_phone_checked == 1 ? 'YES' : 'NO' }}
                                                                </p>
                                                                <p>Comment: {{ $order->comments }}</p>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            @endforeach
                                        @endif
                                        @if($ecomOrders->isNotEmpty())
                                        @foreach($ecomOrders as $odrr)
                                        @php
                                        $attrContents = json_decode($odrr->attributes);

                                    @endphp
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td><h4>{{ $odrr->product->name }}</h4>
                                                <ul>
                                                    <li><strong>Colour:</strong>

                                                        <span
                                                            style="background:{{ $attrContents->color }}; color:#ccc; padding:10px; display:inline-block; border:1px solid #ccc; width:20px; height:20px;">
                                                        </span>
                                                    </li>
                                                    <li><strong>Size:</strong>
                                               
                                                   
                                                        {{ $attrContents->size}}
                                                       
                                                   
                                                </li>
                                                    <li><strong>Quantities:</strong>
                                                        {{ $attrContents->quantity }}
                                                    </li>
                                                    @if($attrContents->cart_image)
                                                    <li><strong>Logo Image:</strong>
                                                    @foreach($attrContents->cart_image as $image)
                                                    <span class="avater-img"><img
                                                        style="height: 25px; width:25px;"
                                                        src="{{ url('storage/' . $image)  }}"
                                                        alt="" /></span>
                                                        @endforeach
                                                    </li>
                                                    
                                                  @endif
                                                  @if($attrContents->note)
                                                  <li><strong>Note:</strong>{{$attrContents->note}}</li>
                                                  @endif
                                                </ul>
                                            
                                            </td>
                                            <td class="text-center">  <span class="avater-img"><img
                                                src="{{ url(asset('storage/' . $attrContents->image)) }}"
                                                class="img-fluid img-thumbnail" alt=""
                                                style="width: 150px; height: auto;" /></span>
                                            </td>
                                            <td><strong>Product Price: ${{ $odrr->amount }}</strong></td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td class="text-center" colspan="1">
                                                <a href="{{ route('admin.orders.index') }}"
                                                    class="btn btn-outline-warning">Back To Order List</a>
                                            </td>

                                            <td class="text-center" colspan="2">
                                                <h3> Sub Total :</h3>
                                                @foreach($odr->tax as $tx)
                                                @php
                                                    $tax_t=App\Models\Tax::where('tax_type',$tx->tax_type)->first();
                                                @endphp
                                                <h3>{{$tx->tax_type}} {{number_format($tax_t->percentage, 2)}}% ({{ $tax_t->tax_code}}):</h3>
                                                @endforeach
                                                <hr>
                                                <h3> Order Total :</h3>
                                            </td>

                                            <td class="text-center">
                                                <h3> ${{ $odr->amount }} </h3>
                                                @foreach($odr->tax as $tx)
                                                <h3>+ ${{round($tx->taxable_amount)}} </h3>
                                                @endforeach
                                                <hr>
                                                <h3> ${{ round($odr->final_amount) }} </h3>
                                            </td>

                                        </tr>
                                </table>



                                {{-- <div class="d-flex justify-content-center">
                                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
                                        rel="stylesheet">
                                    {{ $orderss->links('pagination::bootstrap-5') }}
                                </div> --}}
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="logo" role="tabpanel" aria-labelledby="logo-tab">
                        <div class="row mt-3">
                            <div class="col-12">
                                <table class="table table-bordered" width="100%" cellspacing="1" cellpadding="2"
                                    border="1">
                                    <thead>
                                        <th scope="col">Comment</th>
                                        <th scope="col">Image</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                           
                                          <td> <span><strong>{{$odr->comment ??'' }}</strong></td></span>
                                            @if (!empty($odr->logo))
                                                <td>
                                                   
                                                    <div>
                                                        @foreach (json_decode($odr->logo) as $image)
                                                            <img src="{{ url('storage/' . $image) }}" alt=""
                                                                class="img-thumbnail"
                                                                style="width: 150px; height: 150px;">
                                                            <p><span><a href="{{ url('storage/' . $image) }}" download><i
                                                                            class="fa fa-download"></i></a></span></p>
                                                        @endforeach
                                                    </div>
                                                </td>
                                            @endif

                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="products" role="tabpanel" aria-labelledby="products-tab">
                        <div class="row mt-3">
                            <div class="col-12">
                                <table class="table table-bordered" width="100%" cellspacing="1" cellpadding="2"
                                    border="1">
                                    <thead>
                                        <tr class="primary">
                                            <th scope="col" class="text-center">Sr. No.</th>
                                            <th scope="col" class="text-center">Images</th>
                                            <th scope="col" class="text-center">Comments</th>
                                            <th scope="col" class="text-center">Approval Status</th>
                                            <th scope="col" class="text-center">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($logos as $key => $logo)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td class="text-center">
                                                    <span class="avater-img"><img
                                                            src="{{ url(asset('storage/' . $logo->image)) }}"
                                                            class="img-fluid img-thumbnail" alt=""
                                                            style="width: 150px; height: auto;" /></span>
                                                </td>
                                                <td>
                                                    {{ $logo->comment ?? '' }}
                                                </td>
                                                <td class="text-center">
                                                    @if ($logo->approval_status == 1)
                                                        <span style="color: green;"><strong>Approved</strong></span>
                                                    @elseif ($logo->approval_status === null)
                                                        <span style="color: rgb(55, 0, 255);"><strong>Pending
                                                                Approval</strong></span>
                                                    @else
                                                        <span style="color: red;"><strong>Not Approved</strong></span>
                                                    @endif

                                                </td>

                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($logo->created_at)->format('d/m/Y') }}
                                                </td>
                                            </tr>

                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No data found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </main>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            $('#orderStatus').on('change', function() {
                let status = $(this).val();
                let orderId = $(this).data('order-id');

                $.ajax({
                    url: '{{ route('admin.order.status') }}',
                    method: 'POST',
                    data: {
                        order_id: orderId,
                        order_status: status,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {

                        console.log(response)

                        if (response.success) {
                            $('.orderStatusTxt').text(response.status);
                            alert('Order status updated successfully!');
                        }
                    },

                    error: function(error) {
                        console.log(error);
                    }
                })
            })

        });
    </script>
@endpush
