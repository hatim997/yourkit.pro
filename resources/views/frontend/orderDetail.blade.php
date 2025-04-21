@extends('frontend.layouts')

@section('title', 'Order Details')

@section('content')

    <section class="main-wrap">
        <div class="container-xxl">
            <ul class="nav nav-tabs" id="orderDetailTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="order-detail-tab" data-bs-toggle="tab" href="#order-detail" role="tab"
                        aria-controls="order-detail" aria-selected="true"><strong>Order Details</strong></a>
                </li>

                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="logo-tab" data-bs-toggle="tab" href="#logo" role="tab"
                        aria-controls="logo" aria-selected="false"><strong> Logo</strong></a>
                </li>

                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="products-tab" data-bs-toggle="tab" href="#products" role="tab"
                        aria-controls="products" aria-selected="false"><strong> Display product with logo</strong></a>
                </li>

            </ul>
            <div class="tab-content" id="orderDetailTabsContent">
                <div class="tab-pane fade show active" id="order-detail" role="tabpanel" aria-labelledby="order-detail-tab">
                    <div class="cart-prt">
                        <div class="cart-header">
                            <h5>Description</h5>
                            <h5>Total</h5>
                        </div>

                        <div id="dynamicContent">
                            @php
                                $total = 0;
                                //$total = $total + $odr->amount;
                            @endphp
@if($groupedOrders->isNotEmpty())
                            @foreach ($groupedOrders as $cartId => $bundles)
                                @foreach ($bundles as $bundleId => $orders)
                                    @php
                                        $bundle = App\Models\Bundle::find($bundleId);

                                    @endphp
                                    <div class="mid-part py-4">
                                        <ul class="filter-lst">
                                            @foreach ($bundle->products as $product)
                                                @php
                                                    $image = isset($product->subcategory->media)
                                                        ? url(asset('storage/' . $product->subcategory->media->path))
                                                        : '';
                                                @endphp

                                                <li data-aos="zoom-in" data-aos-duration="2000">
                                                    <div class="filter-ico">
                                                        <img src="{{ $image }}" alt="" />
                                                    </div>
                                                    <h4>{{ $product->pivot->quantity }} - {{ $product->name }}</h4>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>





                                    <div class="product-price-prt py-4">
                                        <div class="product-lft-prt">
                                            @php
                                                $k = 0;
                                            @endphp
                                            @foreach ($orders as $order)
                                                @php
                                                    $attrContents = json_decode($order->attributes);

                                                @endphp
                                                {{-- @foreach ($order->bundle->products as $product) --}}
                                                <h4>{{ $order->bundle->products[$k]['name'] ?? '' }}</h4>
                                                <ul>
                                                    <li><strong>Colour:</strong> <span
                                                            style="background:{{ $attrContents->color }}; color:#ccc; padding:10px; display:inline-block; border:1px solid #ccc; width:20px; height:20px;">
                                                        </span></li>

                                                    <li><strong>Quantities:</strong>
                                                        @foreach ($attrContents->size as $size)
                                                            @if (isset($size->quantity) && $size->quantity > 0)
                                                            {{ $size->attribute_value .'-' .$size->quantity .', '}}
                                                            @endif
                                                        @endforeach
                                                    </li>

                                                    <li><strong>Locations:</strong>
                                                        @php
                                                            $positions = json_decode($order->positions, true);
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
                                                                            $position_image[] = !is_null($image)
                                                                                ? url('assets/frontend/' . $image)
                                                                                : '';
                                                                        }
                                                                    } else {
                                                                        $image = App\Models\PositionImage::where(
                                                                            'id',
                                                                            $position,
                                                                        )
                                                                            ->pluck('image')
                                                                            ->first();
                                                                        $position_image[] = !is_null($image)
                                                                            ? url('assets/frontend/' . $image)
                                                                            : '';
                                                                    }
                                                                }
                                                            }
                                                            $order->position_images = $position_image;
                                                        @endphp
                                                        @foreach ($order->position_images as $image)
                                                            <span class="avater-img"><img src="{{ $image }}"
                                                                    alt="" /></span>
                                                        @endforeach
                                                    </li>
                                                </ul>
                                                @php
                                                    $k++;
                                                @endphp
                                                {{-- @endforeach --}}
                                            @endforeach
                                        </div>

                                        <h5 class="price">{{ \App\Helpers\Helper::formatCurrency($bundle->price) }}
                                        </h5>
                                    </div>

                                    <hr>
                                @endforeach
                            @endforeach
                            @endif

                            @if($ecomOrders->isNotEmpty())
                            <div class="product-price-prt py-4">
                                <div class="product-lft-prt">
                                    @php
                                        $k = 0;
                                    @endphp
                                    @foreach ($ecomOrders as $order)
                                        @php
                                            $attrContents = json_decode($order->attributes);

                                        @endphp
                                        {{-- @foreach ($order->bundle->products as $product) --}}
                                        <h4>{{ $order->product->name ?? '' }}</h4>
                                        <ul>
                                            <li><strong>Colour:</strong> <span
                                                    style="background:{{ $attrContents->color }}; color:#ccc; padding:10px; display:inline-block; border:1px solid #ccc; width:20px; height:20px;">
                                                </span></li>
                                                <li><strong>Size:</strong>


                                                    {{ $attrContents->size}}


                                            </li>
                                            <li><strong>Quantities:</strong>


                                                    {{ $attrContents->quantity}}


                                            </li>
                                            <li><strong>Image:</strong>


                                                <span class="avater-img"><img src="{{url('storage/' . $attrContents->image)}}" alt=""></span>


                                        </li>

                                        @if($attrContents->cart_image)
                                            <li><strong>Logo Image:</strong></li>
                                            @foreach ($attrContents->cart_image as $image)
                                               <li><span class="avater-img"><img src="{{url('storage/' . $image)}}" alt=""></span></li>
                                           @endforeach


                                       @else
                                          <li><strong>Logo Image:</strong> No image available</li>
                                       @endif
                                       @if ($attrContents->note)
                                      <li><strong>Note:</strong>{{$attrContents->note ??'' }}</li>
                                       @else
                                          <li><strong>Note:</strong> No notes available</li>
                                    @endif

                                        </ul>

                                        {{-- @endforeach --}}
                                    @endforeach
                                </div>
                                    <h5 class="price">{{ \App\Helpers\Helper::formatCurrency($order->amount) }}
                                    </h5>



                            </div>

                            <hr>
                            @endif
                            <div class="print-charge">
                                <h5>Sub Total :</h5>
                                <h5><strong>{{ \App\Helpers\Helper::formatCurrency($odr->amount) }}</strong></h5>

                            </div>
                            @foreach($odr->tax as $tx)
                            @php
                            $tax_t=App\Models\Tax::where('tax_type',$tx->tax_type)->first();
                        @endphp
                            <div class="print-charge">
                                <h5>{{$tx->tax_type}} {{number_format($tax_t->percentage, 2)}}% ({{ $tax_t->tax_code}}):</h5>
                                <h5><strong>{{ \App\Helpers\Helper::formatCurrency(round($tx->taxable_amount)) }}</strong></h5>

                            </div>
@endforeach
<div class="print-charge">
    <h5>Total :</h5>
    <h5><strong>{{ \App\Helpers\Helper::formatCurrency(round($odr->final_amount)) }}</strong></h5>

</div>
                            <div class="continue-prt py-4">
                                {{-- <a href="{{ route('frontend.dashboard') }}" class="btn btn-outline-warning">Continue to Shopping</a> --}}
                                <a href="{{ route('frontend.order.view') }}" class="btn btn-outline-warning">Back To Order List</a>
                            </div>
                        </div>


                    </div>
                </div>

                <div class="tab-pane fade" id="logo" role="tabpanel" aria-labelledby="logo-tab">
                    <div class="cart-prt">
                        <div class="cart-header">
                            <h5>Logo</h5>
                        </div>

                        <div class="cart-body">

                            <div class="row">

                                <div class="col-md-4">
                                        <div> @if(!empty($odr->comment)) <span><strong>Comment:</strong>{{$odr->comment }}</span>@endif</div>

                                    @if (!empty($odr->logo))
                                        <div>
                                            @foreach (json_decode($odr->logo) as $image)
                                                <img src="{{ url('storage/' . $image) }}" alt=""
                                                    class="img-thumbnail" style="height: 150px; width: 150px;">
                                                <p><span><a href="{{ url('storage/' . $image) }}" download><i
                                                                class="fa fa-download"></i></a></span></p>
                                            @endforeach

                                        </div>
                                        @else
                                         <span class="text-center"><strong>Nothing is Found!</strong></span>

                                    @endif

                                </div>

                            </div>
                        </div>


                    </div>
                </div>

                <div class="tab-pane fade" id="products" role="tabpanel" aria-labelledby="products-tab">
                    <div class="cart-prt">
                        <div class="cart-header">
                            <h5>Products with Logo</h5>

                        </div>
                        @php
                            $i = 0;
                        @endphp
                        @if (!$logos->isEmpty())

                            <form id="logoForm">
                                @csrf
                                @foreach ($logos as $index => $logo)
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <div class="row align-items-center">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label"><strong>
                                                                {{ $index + 1 }}:</strong></label>
                                                        <span class="avater-img">
                                                            <a download href="{{ url('storage/' . $logo->image) }}"
                                                                target="_blank">
                                                                <img src="{{ url('storage/' . $logo->image) }}"
                                                                    class="img-fluid img-thumbnail" alt=""
                                                                    style="width: 150px; height: auto;" />
                                                            </a>
                                                        </span>
                                                    </div>
                                                </div>


                                                <div class="col-md-4 logo-comment" id="comment_{{ $logo->id }}">
                                                    @if (isset($logo->comment) && !is_null($logo->comment))
                                                        <p>{{ $logo->comment }}</p>
                                                    @endif
                                                </div>

                                                <div class="col-md-4" id="status_{{ $logo->id }}">
                                                    <!-- Show the approval status or accept/reject buttons -->
                                                    @if (!is_null($logo->approval_status) && $logo->approval_status == 1)
                                                        <span
                                                            class="approval-status text-success"><strong>Approved</strong></span>
                                                    @elseif (!is_null($logo->approval_status) && $logo->approval_status == 0)
                                                        <span
                                                            class="approval-status text-danger"><strong>Rejected</strong></span>
                                                    @else
                                                        <!-- If neither approved nor rejected, show Accept/Reject buttons -->
                                                        <button type="button" class="btn btn-success btn-sm accept-btn"
                                                            data-logo-id="{{ $logo->id }}"
                                                            data-status="1">Accept</button>
                                                        <button type="button" class="btn btn-danger btn-sm reject-btn"
                                                            data-logo-id="{{ $logo->id }}"
                                                            data-status="0">Reject</button>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                @endforeach

                                <!-- Modal for adding comments -->
                                <div class="modal fade" id="commentModal" tabindex="-1"
                                    aria-labelledby="commentModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="commentModalLabel">Add Comment</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" id="modalLogoId">
                                                <div class="mb-3">
                                                    <label for="comment" class="form-label">Comment</label>
                                                    <textarea class="form-control" id="comment" rows="4"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary"
                                                    id="submitComment">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @else
                            <span class="text-center"><strong>Nothing is Found!</strong></span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </section>

    <section class="deal-wrap" id="submitSection" style="display: none;">
        <div class="container-xxl">
            <h3 class="mid-title">We need your logo to print it.</h3>



        </div>
    </section>

@endsection

@push('styles')
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // When the Accept or Reject button is clicked
            $('.accept-btn, .reject-btn').on('click', function() {
                var logoId = $(this).data('logo-id');
                var status = $(this).data('status'); // 1 for Accept, 0 for Reject

                // Open the modal to add a comment (optional)
                $('#modalLogoId').val(logoId);
                $('#comment').val(''); // Clear any previous comment
                $('#commentModal').modal('show');

                // Store the approval status in a hidden field or variable
                $('#submitComment').data('status', status);
            });

            // When the Submit button in the modal is clicked
            $('#submitComment').on('click', function() {
                var logoId = $('#modalLogoId').val();
                var comment = $('#comment').val();
                var status = $(this).data('status');

                // Send AJAX request to update the logo's status and comment
                $.ajax({
                    url: '{{ route('frontend.accept.logo') }}',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        approval_status: {
                            [logoId]: status
                        },
                        comment: {
                            [logoId]: comment
                        }
                    },
                    success: function(response) {
                        if (response.success) {
                            // Close the modal
                            $('#commentModal').modal('hide');

                            // Find the row corresponding to the logo
                            let logoRow = $('button[data-logo-id="' + logoId + '"]').closest(
                                '.row');
                            let html = response.approval_status == 1 ?
                                '<span class="approval-status text-success"><strong>Approved</strong></span>' :
                                '<span class="approval-status text-danger"><strong>Rejected</strong></span>'

                            let comment = response.comment


                            $(`#comment_${logoId}`).html(comment)
                            $(`#status_${logoId}`).html(html)


                        }
                    },
                    error: function() {
                        alert('An error occurred while submitting the data.');
                    }
                });
            });
        });
    </script>
    {{-- <script>
        function deleteCart(id) {
            console.log(id);

            let url = "{{ route('frontend.cart.delete', ['id' => ':id']) }}".replace(':id', id);

            console.log('url ', url)

            let confirmed = confirm("Are you sure you want to proceed?");
            if (confirmed) {
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function(response) {
                        if (response.status) {
                            alert(response.message);
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error)
                    }
                });
            }

        }

        $(document).ready(function() {

            let sessionId = localStorage.getItem('sessionId') && JSON.parse(localStorage.getItem('sessionId')) ||
                "";
            // console.log('cartData ', sessionId)

            $('#sessionId').val(sessionId);

            let url = "{{ route('frontend.cart.all', ['sessionId' => ':sessionId']) }}".replace(':sessionId', sessionId);

            $.ajax({
                type: 'GET',
                url: url,
                success: function(response) {
                    console.log(response)

                    if (response.count > 0) {
                        $('#submitSection').show();
                    }

                    $('#dynamicContent').html(response.data);

                },
                error: function(xhr, status, error) {
                    console.log(error)
                }
            });

        })
    </script> --}}
@endpush
