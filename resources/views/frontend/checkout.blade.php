@extends('frontend.layouts')

@section('title', 'Checkout')

@section('content')
    <section class="main-wrap">
        <div class="container-xxl">

            {{-- <div class="row">
                <div class="col-md-6">
                    <div class="coupon-accordion">
                        <h3>Returning customer? <span id="showlogin">Click here to login</span></h3>
                        <div id="checkout-login" class="coupon-content">
                            <div class="coupon-info">
                                <p class="coupon-text">Quisque gravida turpis sit amet nulla posuere lacinia. Cras sed est
                                    sit amet ipsum luctus.</p>
                                <form action="#">
                                    <p class="form-row-first">
                                        <label>Username or email <span class="required">*</span></label>
                                        <input type="text" />
                                    </p>
                                    <p class="form-row-last">
                                        <label>Password <span class="required">*</span></label>
                                        <input type="text" />
                                    </p>
                                    <p class="form-row">
                                        <input type="submit" value="Login" />
                                        <label>
                                            <input type="checkbox" />
                                            Remember me
                                        </label>
                                    </p>
                                    <p class="lost-password">
                                        <a href="#">Lost your password?</a>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="coupon-accordion">
                        <h3>Have a coupon? <span id="showcoupon">Click here to enter your code</span></h3>
                        <div id="checkout_coupon" class="coupon-checkout-content">
                            <div class="coupon-info">
                                <form action="#">
                                    <p class="checkout-coupon">
                                        <input type="text" placeholder="Coupon code" />
                                        <input type="submit" value="Apply Coupon" />
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            <form action="{{ route('frontend.checkout.store') }}" method="POST" id="checkoutForm">
                @csrf

                <div class="row mt-4">
                    <div class="col-md-8">
                        <div class="row checkout">
                            <div class="col-md-12">
                                <h2>Billing Details</h2>
                            </div>
                            <div class="col-md-6">
                                <h3>Name *</h3>
                                <input type="text" class="form-control" name="billing_name"
                                    value="{{ $user->name ?? old('name') }}" required>
                                @error('billing_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <h3>Company Name </h3>
                                <input type="text" class="form-control" name="company_name"
                                    value="{{ $user->company_name ?? old('company_name') }}" required>
                                @error('company_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <h3>Email Address *</h3>
                                <input type="email" class="form-control" name="billing_email"
                                    value="{{ $user->email ?? old('email') }}" required>
                                @error('billing_email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <h3>Phone *</h3>
                                <input type="tel" class="form-control" name="billing_mobile"
                                    value="{{ $user->phone ?? old('phone') }}" required>
                            </div>
                            <div class="col-md-12">
                                <h3>Country *</h3>
                                {{-- <select class="form-select" name="country">
                                    <option value="">Select Country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->name }}" data-code="{{ $country->code }}"
                                            {{ old('country') == $country->id ? 'selected' : '' }}>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select> --}}
                                <select class="form-select" name="country" id="country">
                                    <option value="">Select Country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->name }}" data-code="{{ $country->code }}"
                                            {{ old('country') == $country->name ? 'selected' : '' }}>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('country')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <h3>Address *</h3>
                                <input type="text" class="form-control" placeholder="Street Address" name="address"
                                    value="{{ $user->profile->address ?? old('phone') }}" required>
                                @error('address')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- <div class="col-md-12">
                                <input type="text" class="form-control"
                                    placeholder="Apartment, suite, unit etc. (optional)">
                            </div> --}}
                            <div class="col-md-6">
                                <h3>Town / City *</h3>
                                <input type="text" class="form-control" placeholder="Street Address" name="town"
                                    value="{{ $user->profile->location ?? old('location') }}" required>
                                @error('town')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <h3>Postcode *</h3>
                                <input type="text" class="form-control" name="pincode"
                                    value="{{ $user->profile->pincode ?? old('pincode') }}" required>
                                @error('pincode')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <div class="mt-1 mb-1">
                                    <p style="color: #ff8b00">Note: Shipping cost will be charged separately once the order
                                        is ready</p>
                                </div>

                                <h3>Choose one as per your need</h3>

                                <div class="form-check col-md-6">
                                    <input class="form-check-input" type="radio" name="shipping" id="flexRadioDefault1"
                                        value="1" {{ old('shipping', '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        Store Pickup? (No need of Shipping details)
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="shipping" id="flexRadioDefault2"
                                        value="2" {{ old('shipping') == '2' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="flexRadioDefault2">
                                        Shipping details different from billing details
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="shipping" id="flexRadioDefault3"
                                        value="3" {{ old('shipping') == '3' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="flexRadioDefault3">
                                        Shipping details same as billing details
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>



                    <div class="col-md-4">
                        <div class="checkout">
                            <h2>Your order</h2>
                        </div>

                        <div class="order-details">
                            <!-- Promo Code Section -->
                            {{-- @if (isset($discount) && $discount == 0) --}}
                            <div class="promo-code-box mb-4 p-3 border rounded bg-light">
                                <label for="promo_code" class="form-label fw-bold mb-2">Have a Promo Code?</label>
                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                    <input type="text" name="promo_code" id="promo_code" class="form-control"
                                        placeholder="Enter promo code" style="max-width: 250px;">
                                    <button type="button" id="applyPromoBtn" class="btn btn-success px-4">Apply</button>
                                    <span id="promoMessage" class="ms-3 small text-muted"></span>
                                </div>
                            </div>
                            {{-- @endif --}}
                            <div class="single-widget">
                                <h2>CART TOTALS</h2>
                                <div class="content">
                                    <ul>
                                        <li>Sub Total<span
                                                id="totalAmount">{{ $currency_symbol . number_format($total, 2) }}</span>
                                        </li>
                                        <li>Discount<span
                                                id="discountAmount">{{ $currency_symbol . number_format($discount, 2) }}</span>
                                        </li>
                                        <input type="hidden" id="sub-total" value="{{ $total }}">
                                        <input type="hidden" id="current-discount" value="{{ $discount }}">
                                        {{-- <li>(+) Shipping<span>{{ $currency_symbol }}00.00</span></li> --}}
                                        {{-- <li>(+) {{ $tax_tvq_string }}<span>{{ $currency_symbol . $tax_tvq }}</span></li>
                                        <li>(+) {{ $tax_tps_string }}<span>{{ $currency_symbol . $tax_tps }}</span></li> --}}

                                        @php
                                            $totalAmount = $total - $discount;
                                        @endphp

                                        {{-- @foreach ($taxes as $tax)
                                            @php
                                                $taxAmount = isset($tax->percentage)
                                                    ? ($tax->percentage * $total) / 100
                                                    : 0;
                                                $totalAmount += $taxAmount;

                                            @endphp

                                            <li>(+)
                                                {{ $tax->tax_type . ' ' . $tax->percentage . '% (' . $tax->tax_code . ')' }}<span>{{ $currency_symbol . number_format($taxAmount, 2) }}</span>
                                            </li>
                                        @endforeach --}}
                                        <div id="taxes-list">
                                            <!-- Taxes will be dynamically inserted here -->
                                        </div>

                                        <li>Total<span id="grandTotal">{{ number_format($totalAmount, 2) }}</span></li>
                                    </ul>
                                </div>
                            </div>

                            {{-- ----------------Stripe Payment---------------- --}}
                            {{-- <div class="single-widget">
                                <h2>PAYMENT METHOD</h2>
                                <div class="content">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_type" name="stripe"
                                            checked>
                                        <label class="form-check-label">Stripe</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_type" name="paypal">
                                        <label class="form-check-label">Paypal</label>
                                    </div>

                                    <span class="mt-4">
                                        <img class="img-fluid" src="images/payment.png" alt="" />
                                    </span>
                                </div>
                            </div>

                            <input type="hidden" name="sessionId" value="{{ $sessionId }}">
                            <input type="hidden" name="amount" value="{{ $total }}">

                            <button type="button" id="pay-with-stripe" class="btn btn-warning w-100">Place
                                Order</button>

                            <div id="stripe-card-element" style="display: none;" class="my-2">
                                <div id="card-element" class="my-3 p-2"></div>
                                <button type="submit" class="btn btn-warning w-100">Pay with Stripe</button>
                            </div> --}}

                            {{-- ----------------Authorize Payment---------------- --}}
                            <div class="single-widget">
                                <h2>PAYMENT DETAILS</h2>
                                <div class="row content">
                                    <div class="form-group">
                                        <label class="form-label">Card Number : <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="card_number"
                                            value="{{ old('card_number') }}" />
                                        @error('card_number')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Expiry Date (mmyy) : <span
                                                class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="expiry"
                                            value="{{ old('expiry') }}" />
                                        @error('expiry')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">CVV : <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="cvv"
                                            value="{{ old('cvv') }}" />
                                        @error('cvv')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <span class="mt-4">
                                        <img class="img-fluid"
                                            src="{{ url(asset('assets/frontend/images/payment.png')) }}"
                                            alt="" />
                                    </span>
                                </div>
                            </div>

                            <input type="hidden" name="sessionId" value="{{ $sessionId }}">
                            <input type="hidden" name="sub_amount" value="{{ $total }}">
                            <input type="hidden" name="discount" value="{{ $discount }}">
                            <input type="hidden" name="final_amount" value="{{ $totalAmount }}">
                            <input type="hidden" name="promo_code_id">

                            <button type="button" id="pay-with-authorise-payment" class="btn btn-warning w-100">Place
                                Order</button>

                        </div>
                    </div>

                    <div class="row mt-4" id="shipping-section" style="display: none;">
                        <div class="col-md-8">
                            <div class="row checkout">
                                <div class="col-md-12">
                                    <h2>Shipping Details</h2>
                                </div>
                                <div class="col-md-6">
                                    <h3>Name *</h3>
                                    <input type="text" class="form-control" name="shipping_name" value="">
                                    @error('shipping_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <h3>Company Name </h3>
                                    <input type="text" class="form-control" name="shipping_company" value="">
                                </div>
                                <div class="col-md-6">
                                    <h3>Email Address *</h3>
                                    <input type="email" class="form-control" name="shipping_email" value="">
                                    @error('shipping_email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <h3>Phone *</h3>
                                    <input type="tel" class="form-control" name="shipping_mobile" value="">
                                </div>
                                <div class="col-md-12">
                                    <h3>Country *</h3>
                                    <select class="form-select" name="shipping_country">
                                        <option value="">Select Country</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->name }}"
                                                {{ old('shipping_country') == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('shipping_country')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <h3>Address *</h3>
                                    <input type="text" class="form-control" placeholder="Street Address"
                                        name="shipping_address" value="">
                                    @error('shipping_address')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                {{-- <div class="col-md-12">
                                    <input type="text" class="form-control"
                                        placeholder="Apartment, suite, unit etc. (optional)">
                                </div> --}}
                                <div class="col-md-6">
                                    <h3>Town / City *</h3>
                                    <input type="text" class="form-control" placeholder="Street Address"
                                        name="shipping_town" value="">
                                    @error('shipping_town')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <h3>Postcode *</h3>
                                    <input type="text" class="form-control" name="shipping_pincode" value="">
                                    @error('shipping_pincode')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

            </form>

        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        $(document).ready(function() {

            // ----------------Stripe Payment----------------
            // const stripe = Stripe("{{ config('services.stripe.key') }}");
            // const elements = stripe.elements();
            // const cardElement = elements.create('card');
            // cardElement.mount('#card-element');

            // $('#pay-with-stripe').on('click', function(event) {
            //     $('#stripe-card-element').show();
            //     event.preventDefault();
            //     $(this).hide();

            //     $('#checkoutForm').on('submit', async function(event) {
            //         event.preventDefault();
            //         const {
            //             token,
            //             error
            //         } = await stripe.createToken(cardElement);
            //         if (error) {
            //             alert(error.message);
            //         } else {
            //             const hiddenInput = $('<input>')
            //                 .attr('type', 'hidden')
            //                 .attr('name', 'stripeToken')
            //                 .attr('value', token.id);
            //             $('#checkoutForm').append(hiddenInput);
            //             $(this).off('submit').submit();
            //         }
            //     })

            // });

            // ----------------Authorize Payment----------------
            $('#pay-with-authorise-payment').on('click', function(event) {
                $('#pay-with-authorise-payment').prop("disabled", true);

                $('#checkoutForm').submit();

            });

            var canadianTaxes = @json($taxes); // Ensure $taxes contains only Canadian taxes
            var currencySymbol = '{{ $currency_symbol }}';

            function calculateGrandTotal() {
                const country = $('#country').val();
                const baseTotal = parseFloat($('#sub-total').val());
                const discount = parseFloat($('#current-discount').val());
                let taxTotal = 0;

                const $taxesList = $('#taxes-list');
                $taxesList.empty();

                if (country === 'Canada') {
                    $.each(canadianTaxes, function(index, tax) {
                        const taxAmount = (tax.percentage / 100) * baseTotal;
                        taxTotal += taxAmount;

                        const taxItem = `
                            <li>(+) ${tax.tax_type} ${tax.percentage}% (${tax.tax_code})
                            <span>${currencySymbol}${taxAmount.toFixed(2)}</span></li>
                        `;
                        $taxesList.append(taxItem);
                    });
                }

                const grandTotal = (baseTotal - discount) + taxTotal;
                $('#grandTotal').text(grandTotal.toFixed(2));
                $('input[name="final_amount"]').val(grandTotal.toFixed(2)); // ✅ update hidden field
            }


            calculateGrandTotal();

            $('#applyPromoBtn').on('click', function() {
                let promoCode = $('#promo_code').val().trim();
                let $message = $('#promoMessage');
                let currentDiscount = $('#current-discount').val();

                if (!promoCode) {
                    $message.text('Please enter a promo code.').removeClass().addClass('text-danger');
                    return;
                }

                if (parseInt(currentDiscount) > 0) {
                    Swal.fire({
                        title: 'You already have a discount!',
                        text: "Do you want to replace it with the new promo code?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, replace it',
                        cancelButtonText: 'No, keep current'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            applyPromoCode(promoCode, $message);
                        }
                    });
                } else {
                    applyPromoCode(promoCode, $message);
                }
            });

            function applyPromoCode(promoCode, $message) {
                $.ajax({
                    url: '{{ route('frontend.apply.promo') }}',
                    method: 'POST',
                    data: {
                        promo_code: promoCode,
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        $message.text('Checking...').removeClass().addClass('text-info');
                    },
                    success: function(response) {
                        if (response.success) {
                            $message.text(response.message).removeClass().addClass('text-success');

                            // Get current values
                            let originalTotal = parseFloat($('#sub-total').val());
                            console.log('current total ', originalTotal);

                            let oldDiscount = parseFloat($('#current-discount').val());
                            console.log('current discount ', oldDiscount);
                            let percentage = parseFloat(response
                                .discount_percentage); // Make sure backend returns this

                            // Calculate new discount
                            let newDiscount = Math.round((percentage / 100) * originalTotal);
                            let updatedTotal = originalTotal - newDiscount;

                            let promoCodeId = response.promo_code_id

                            // Update UI
                            $('#discountAmount').text('{{ $currency_symbol }}' + newDiscount.toFixed(
                                2));
                            $('#totalAmount').text(updatedTotal.toFixed(2));
                            $('#current-discount').val(newDiscount.toFixed(2));
                            $('#sub-total').val(updatedTotal.toFixed(
                                2)); // optional: update base total
                            $('input[name="discount"]').val(newDiscount.toFixed(2));
                            $('input[name="sub_amount"]').val(updatedTotal.toFixed(2));
                            $('input[name="promo_code_id"]').val(promoCodeId);


                            calculateGrandTotal();
                        } else {
                            $message.text(response.message).removeClass().addClass('text-danger');
                        }
                    },
                    error: function() {
                        $message.text('Something went wrong!').removeClass().addClass('text-danger');
                    }
                });
            }

            $('#country').on('change', calculateGrandTotal);


            // function applyPromoCode(promoCode, $message) {
            //     $.ajax({
            //         url: '{{ route('frontend.apply.promo') }}',
            //         method: 'POST',
            //         data: {
            //             promo_code: promoCode,
            //             _token: '{{ csrf_token() }}'
            //         },
            //         beforeSend: function() {
            //             $message.text('Checking...').removeClass().addClass('text-info');
            //         },
            //         success: function(response) {
            //             if (response.success) {
            //                 $message.text(response.message).removeClass().addClass('text-success');
            //                 // location.reload();
            //             } else {
            //                 $message.text(response.message).removeClass().addClass('text-danger');
            //             }
            //         },
            //         error: function() {
            //             $message.text('Something went wrong!').removeClass().addClass('text-danger');
            //         }
            //     });
            // }



            // $('#applyPromoBtn').on('click', function() {
            //     let promoCode = $('#promo_code').val().trim();
            //     let $message = $('#promoMessage');

            //     if (!promoCode) {
            //         $message.text('Please enter a promo code.').removeClass().addClass('text-danger');
            //         return;
            //     }

            //     $.ajax({
            //         url: '{{ route('frontend.apply.promo') }}', // make sure this route exists
            //         method: 'POST',
            //         data: {
            //             promo_code: promoCode,
            //             _token: '{{ csrf_token() }}'
            //         },
            //         beforeSend: function() {
            //             $message.text('Checking...').removeClass().addClass('text-info');
            //         },
            //         success: function(response) {
            //             if (response.success) {
            //                 $message.text(response.message).removeClass().addClass(
            //                     'text-success');

            //                 // Optionally update values
            //                 // $('#totalAmount').text(response.new_total);
            //                 // $('#discountSection').text(response.discount_amount);
            //             } else {
            //                 $message.text(response.message).removeClass().addClass(
            //                     'text-danger');
            //             }
            //         },
            //         error: function() {
            //             $message.text('Something went wrong!').removeClass().addClass(
            //                 'text-danger');
            //         }
            //     });
            // });

        })
    </script>


    <script>
        // $(document).ready(function() {
        //     $('#shipping-section').hide();
        //     $('input[name="shipping"]').change(function() {
        //         if ($('#flexRadioDefault2').is(':checked')) {
        //             $('#shipping-section').fadeIn();

        //             $('#shipping-section input, #shipping-section select').each(function() {
        //                 $(this).attr('required', true);
        //             });

        //         } else {
        //             $('#shipping-section').fadeOut();

        //             $('#shipping-section input, #shipping-section select').each(function() {
        //                 $(this).attr('required', false);
        //             });
        //         }
        //     });
        // });


        $(document).ready(function() {
            // Check old value of shipping on page load
            var oldShipping = "{{ old('shipping') }}"; // Get old value from Laravel session

            if (oldShipping == "2") {
                $('#shipping-section').show(); // Show shipping section
                $('#shipping-section input, #shipping-section select').each(function() {
                    $(this).attr('required', true);
                });
            } else {
                $('#shipping-section').hide(); // Hide shipping section
                $('#shipping-section input, #shipping-section select').each(function() {
                    $(this).attr('required', false);
                });
            }

            // Handle radio button change event
            $('input[name="shipping"]').change(function() {
                if ($('#flexRadioDefault2').is(':checked')) {
                    $('#shipping-section').fadeIn();
                    $('#shipping-section input, #shipping-section select').each(function() {
                        $(this).attr('required', true);
                    });
                } else {
                    $('#shipping-section').fadeOut();
                    $('#shipping-section input, #shipping-section select').each(function() {
                        $(this).attr('required', false);
                    });
                }
            });
        });
    </script>
@endpush
