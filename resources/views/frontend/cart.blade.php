@extends('frontend.layouts')

@section('title', 'My Cart')

@section('content')

    <section class="main-wrap">
        <div class="container-xxl">

            {{-- <div class="cart-prt">
                <div class="cart-header">
                    <h5>Description</h5>
                    <h5>Total</h5>
                </div> --}}

            <div id="dynamicContent"></div>


            {{-- </div> --}}


        </div>
        </div>
    </section>

    <section class="deal-wrap" id="submitSection" style="display: none;">
        <div class="container-xxl">
            <h3 class="mid-title">We need your logo to print it.</h3>

            <form action="{{ route('frontend.cart.submit') }}" method="POST" enctype="multipart/form-data">

                @csrf

                <div class="row mt-4">
                    <div class="offset-md-2 col-md-8">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Files accepted: .jpg, .jepg, .png, .pdf</label>
                                    <input class="form-control @error('file') is-invalid @enderror" type="file"
                                        name="file[]" multiple />

                                    @error('file')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_logo_later" id="is_logo_later"
                                        value="1">
                                    <label>Send your logo later</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>COMMENTS</label>
                                    <textarea class="form-control @error('comment') is-invalid @enderror" rows="5" name="comment"></textarea>
                                    @error('comment')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">

                                <input type="hidden" name="sessionId" value="" id="sessionId">

                                <button id="paymentBtn" class="btn btn-outline-warning mt-3" type="submit"><span class="text-uppercase">Proceed to payment</span>
                                    <i class="fa-solid fa-arrow-right"></i></button>
                                    <button type="button" onclick="window.location.href='{{ route('frontend.home') }}'" class="btn btn-outline-warning mt-3">Continue Shopping</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </section>

@endsection

@push('styles')
@endpush

@push('scripts')
    <script>
        /* Updating Cart*/
        function updateCart(id) {
            console.log('update', id)
            let url = "{{ route('frontend.cart.edit', ['id' => ':id']) }}".replace(':id', id);
            window.location.href = url;
        }

        function updateEcomCart(id) {
            console.log('update', id)
            let url = "{{ route('frontend.ecom-cart.edit', ['id' => ':id']) }}".replace(':id', id);
            window.location.href = url;
        }



        /* Deleting Cart*/
        function deleteCart(id) {
            console.log(id);

            let url = "{{ route('frontend.cart.delete', ['id' => ':id']) }}".replace(':id', id);

            console.log('url ', url)

            let confirmed = confirm("Are you sure you want to delete?");
            if (confirmed) {
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function(response) {
                        if (response.status) {
                            //alert(response.message);
                            toastr.success(response.message, 'Success');
                            setTimeout(function () {
                                location.reload();
                            }, 1000);

                        } else {
                            //alert(response.message);
                            toastr.error(response.message, 'Error');
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
            //let sessionId ='Dgshyshnjns';

            console.log('cartData ', sessionId);

            if (sessionId != null) {
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
                        $('[data-toggle="tooltip"]').tooltip();

                    },
                    error: function(xhr, status, error) {
                        console.log(error)
                    }
                });
            }
        })
    </script>
@endpush
