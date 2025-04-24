@extends('frontend.layouts')

@section('title', 'Products')

@section('content')

    <section class="main-wrap">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-12 col-sm-12">
                    <div class="product-details-slider">

                        <div class="product-big-img">
                            <div class="product-big-slider" id="product-big-slider">
                                {{-- <div class="big-sld-items">
                              <img class="img-fluid" src="{{ url(asset('assets/frontend/images/front1.png')) }}" alt="">
                            </div>
                            <div class="big-sld-items">
                              <img class="img-fluid" src="{{ url(asset('assets/frontend/images/front2.png')) }}" alt="">
                            </div>
                            <div class="big-sld-items">
                              <img class="img-fluid" src="{{ url(asset('assets/frontend/images/front3.png')) }}" alt="">
                            </div> --}}

                            </div>
                        </div>
                        <div class="product-details-thumbnail">
                            <div class="product-thumbnail-slider" id="product-thumbnail-slider">
                                {{-- <div class="thumbnail-sld-items">
                                <img class="img-fluid" src="{{ url(asset('assets/frontend/images/front1.png')) }}" alt="">
                              </div>
                              <div class="thumbnail-sld-items">
                                <img class="img-fluid" src="{{ url(asset('assets/frontend/images/front2.png')) }}" alt="">
                              </div>
                              <div class="thumbnail-sld-items">
                                <img class="img-fluid" src="{{ url(asset('assets/frontend/images/front3.png')) }}" alt="">
                              </div> --}}

                            </div>
                        </div>
                    </div>
                    {{-- <div class="product-dtl-img">
                        <ul class="gc-start glasscase-slider"> --}}
                    {{-- <li>
                                <img src="{{ url(asset('assets/frontend/images/logo.png')) }}" alt="" class="img-fluid">
                            </li>
                            <li>
                                <img src="{{ url(asset('assets/frontend/images/bg1.jpg')) }}" alt="" class="img-fluid">
                            </li> --}}
                    {{-- <li>
                                <img src="{{ url(asset('assets/frontend/images/bg2.jpg')) }}" alt="" class="img-fluid">
                            </li>
                            <li>
                                <img src="{{ url(asset('assets/frontend/images/bg4.jpg')) }}" alt="" class="img-fluid">
                            </li> --}}
                    {{-- </ul> --}}
                    {{-- <ul class="gc-start glasscase-slider">

                            @foreach ($product->ecommerce as $ecom) --}}

                    {{-- <li> --}}

                    {{-- <img src="{{ url('storage/' . $product->ecommerce()->first()->image) }}" alt=""
                            class="img-fluid"> --}}
                    {{-- </li>
                            @endforeach

                        </ul> --}}
                    {{-- </div> --}}
                </div>
                <div class="col-lg-7 col-md-12 col-sm-12">
                    <form id="addCart">
                        @csrf
                        <div class="service-dtls-text">
                            <h3>{{ $product->name }}</h3>
                            {{-- <p>{{ $product->description }}</p> --}}
                            <div class="price">
                                <h5 id="product-price-display">
                                    ${{ $product->ecommerce()->first()->price }}
                                </h5>
                            </div>
                            <div class="service-steps">
                                <h5>COLOR</h5>
                                <div class="service-radio">
                                    <ul class="service-radio-list">

                                        @foreach ($product->color as $cl => $color)
                                            <li data-color="{{ $color }}"
                                                class="{{ $cl == 0 ? 'selected' : '' }} colors"><a
                                                    style="background: {{ $color }}"></a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="service-steps">
                                <h5>SIZE</h5>
                                <div class="service-radio">
                                    <ul class="service-radio-list2">
                                        @foreach ($product->size as $sz => $size)
                                            <li data-size="{{ $size }}"
                                                class="{{ $sz == 0 ? 'selected' : '' }} sizes">
                                                <span>{{ $size }}</span>
                                            </li>
                                        @endforeach


                                    </ul>

                                </div>
                            </div>
                            <div class="service-steps">
                                <h5>Quantity</h5>
                                <div class="prod-details">
                                    {{-- <select class="form-select" name="quantity" id="quantitySelector">
                                        <option value="1" selected>1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                    </select> --}}
                                    <input type="number" class="form-control" name="quantity" id="quantitySelector"
                                        value="1" min="1" max="9999">

                                    {{-- <button class="btn btn-outline-warning" type="submit">Add to
                                        Cart</button> --}}
                                    <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal"
                                        data-bs-target="#cartModal">
                                        Add to Cart
                                    </button>
                                </div>

                                <div id="volume-discount-message" style="margin-top: 10px; font-weight: 500; color: green;">
                                </div>
                            </div>
                            <div class="view-size-chart-wrap">
                                <ul>
                                    @if (!empty($product->size_chart))
                                        <li>
                                            <a class="size-chart-btn" href="{{ url('storage/' . $product->size_chart) }}"
                                                target="_blank">
                                                <img class="img-fluid"
                                                    src="{{ url(asset('assets/frontend/images/size_chart.png')) }}"
                                                    alt="">
                                            </a>
                                        </li>
                                    @endif
                                    {{-- <li>
                                        <a class="size-chart-btn" href="#" target="_blank">
                                            <img class="img-fluid" src="http://127.0.0.1:8000/storage/subcategory/672e13ae1d4c1_1731072942.png" alt="">
                                        </a>
                                    </li>
                                    <li>
                                        <a class="size-chart-btn" href="#" target="_blank">
                                            <img class="img-fluid" src="http://127.0.0.1:8000/storage/subcategory/672e13ae1d4c1_1731072942.png" alt="">
                                        </a>
                                    </li> --}}
                                </ul>
                            </div>
                        </div>
                    </form>
                    <div class="service-details">
                        <div class="accordion faq-accordion-box" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Description
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="service-dtl-info">

                                            <p>{!! $product->description !!}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Add to Cart Modal -->
    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="modalCartForm" enctype="multipart/form-data">
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cartModalLabel">Upload Logos</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="cartImage" class="form-label">Upload Image </label>
                            <input type="file" name="cart_image[]" class="form-control" id="cartImage" multiple>
                            <label for="note" class="form-label">Note </label>
                            <textarea class="form-control" name="note" id="note" cols="30" rows="2"
                                placeholder="Please specify the positioning of the logo print."></textarea>
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="discount" id="discount" value="0">
                            <input type="hidden" name="color">
                            <input type="hidden" name="size">
                            <input type="hidden" name="price">
                            <input type="hidden" name="image">
                            <input type="hidden" value="{{ $product->id }}" name="table_id">
                            <input type="hidden" value="products" name="table">
                            <input type="hidden" name="sessionId" id="sessionId">
                            <input type="hidden" name="quantity" id="hiddenQuantity">
                            {{-- <input type="hidden" name="ecom_id" id="ecom_id"> --}}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <p><strong>Note:</strong> If you do not wish to print logo on the product, click on submit button.
                        </p>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ url('assets/frontend/css/glass-case.css') }}">
@endpush

@push('scripts')
    <script>
        function generateRandomString(length = 16) {
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let result = '';
            for (let i = 0; i < length; i++) {
                const randomIndex = Math.floor(Math.random() * characters.length);
                result += characters.charAt(randomIndex);
            }
            return result;
        }
        // $(document).ready(function() {
        //     $('#modalCartForm').on('submit', function(e) {
        //        // e.preventDefault();

        //        var selectedQuantity = $('select[name="quantity"]').val();


        //         $('#hiddenQuantity').val(selectedQuantity);

        //         let sessionId = localStorage.getItem('sessionId') && JSON.parse(localStorage.getItem(
        //             'sessionId')) || "";
        //         if (!sessionId) {
        //             sessionId = generateRandomString();
        //         }
        //         $('#sessionId').val(sessionId);
        //         console.log(sessionId)

        //         // let formdata = $(this).serialize();
        //         // console.log('Form Data:', formdata);


        //         // $.ajax({
        //         //     url: $(this).attr('action'),
        //         //     method: $(this).attr('method'),
        //         //     data: formdata,
        //         //     success: function(response) {

        //         //         console.log('Success:', response);
        //         //         alert('Product added to cart successfully!');
        //         //     },
        //         //     error: function(error) {
        //         //         // Handle error
        //         //         console.error('Error:', error);
        //         //         alert('Failed to add product to cart. Please try again.');
        //         //     }
        //         // });
        //     })
        // });
        $(document).ready(function() {

            let quqantity = 1;

            $('#quantitySelector').on('change', function() {
                quqantity = $(this).val();
            })

            let sessionId = localStorage.getItem('sessionId') && JSON.parse(localStorage.getItem(
                'sessionId')) || "";
            if (!sessionId) {
                sessionId = generateRandomString(); // Generate random session ID
                localStorage.setItem('sessionId', JSON.stringify(sessionId));
            }


            $('#modalCartForm').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                formData.append('sessionId', sessionId)
                formData.append('quantity', quqantity)

                let url = '{{ route('frontend.ecom.cart.submit') }}';

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {

                        console.log(response)



                        if (response.status) {
                            localStorage.setItem('sessionId', JSON.stringify(response
                                .data));
                            window.location.href = "{{ route('frontend.cart') }}";
                        } else {

                            toastr.error(response.message, 'Error');
                        }
                    },
                    // error: function(error) {

                    //     alert('Failed to add product to cart. Please try again.');
                    //     console.error('Error:', error);
                    // }
                    error: function(xhr) {
                        let response = xhr.responseJSON;
                        if (response && response.errors) {
                            if (response.errors.color) {
                                toastr.error(response.errors.color[0], 'Error');
                            }
                            if (response.errors.size) {
                                toastr.error(response.errors.size[0], 'Error');
                            }
                        } else {
                            toastr.error('Failed to add product to cart. Please try again.',
                                'Error');
                        }
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            const discounts = @json($product->productVolumeDiscounts);
            const originalPrice = parseFloat({{ $product->ecommerce()->first()->price }});

            function updateDiscountMessage(quantity) {
                let discountApplied = null;
                let nextDiscount = null;

                // Sort discounts by quantity ascending
                discounts.sort((a, b) => a.quantity - b.quantity);

                for (let i = 0; i < discounts.length; i++) {
                    const d = discounts[i];

                    if (quantity >= d.quantity) {
                        discountApplied = d;
                    } else {
                        nextDiscount = d;
                        break;
                    }
                }

                // Display discounted price if applicable
                if (discountApplied) {
                    const discountPercent = discountApplied.discount_percentage;
                    const discountValue = (originalPrice * (discountPercent / 100)).toFixed(2);
                    const discountedPrice = (originalPrice - (originalPrice * (discountPercent / 100))).toFixed(2);

                    $('#product-price-display').html(
                        `$${discountedPrice} <span style="text-decoration: line-through; color: #999; font-size: 16px; margin-left: 5px;">$${originalPrice.toFixed(2)}</span> <span style="color: #d00; font-size: 14px;">(${discountPercent.toFixed(2)}% off)</span>`
                    );
                    $('#discount').val(discountValue);
                } else {
                    $('#product-price-display').html(`$${originalPrice.toFixed(2)}`);
                    $('#discount').val(0);
                }


                // Show message to reach next discount tier
                if (nextDiscount) {
                    const qtyNeeded = nextDiscount.quantity - quantity;
                    $('#volume-discount-message').html(
                        `Buy ${qtyNeeded} more to get ${nextDiscount.discount_percentage.toFixed(2)}% off!`);
                } else {
                    $('#volume-discount-message').html('');
                }
            }

            $('#quantitySelector').on('input change', function() {
                const quantity = parseInt($(this).val());
                if (quantity >= 1) {
                    updateDiscountMessage(quantity);
                }
            });

            // Initial trigger on page load
            updateDiscountMessage(parseInt($('#quantitySelector').val()));
        });
        document.addEventListener('DOMContentLoaded', () => {
            let selectedColor = null;
            let currentSelectedColor = null;
            let selectedSize = null;
            let currentSelectedSize = null;
            const productId = document.querySelector('input[name="product_id"]').value;
            const fetchAttributesUrl = "{{ route('frontend.fetch.ecom.attr') }}";

            const firstColorElement = document.querySelector('.colors');
            const firstSizeElement = document.querySelector('.sizes');

            if (firstColorElement) {
                firstColorElement.classList.add('selected');
                selectedColor = firstColorElement.getAttribute('data-color');
                document.querySelector('input[name="color"]').value = selectedColor;
            }

            if (firstSizeElement) {
                firstSizeElement.classList.add('selected');
                selectedSize = firstSizeElement.getAttribute('data-size');
                document.querySelector('input[name="size"]').value = selectedSize;
            }


            if (selectedColor && selectedSize) {
                fetchUpdatedAttributes();
            }


            document.querySelectorAll('.colors').forEach((colorElement) => {
                colorElement.addEventListener('click', (event) => {
                    document.querySelectorAll('.colors').forEach(el => el.classList.remove(
                        'selected'));
                    event.currentTarget.classList.add('selected');
                    selectedColor = event.currentTarget.getAttribute('data-color');
                    document.querySelector('input[name="color"]').value = selectedColor;
                    fetchUpdatedAttributes();
                });
            });


            document.querySelectorAll('.sizes').forEach((sizeElement) => {
                sizeElement.addEventListener('click', (event) => {
                    document.querySelectorAll('.sizes').forEach(el => el.classList.remove(
                        'selected'));
                    event.currentTarget.classList.add('selected');
                    selectedSize = event.currentTarget.getAttribute('data-size');
                    document.querySelector('input[name="size"]').value = selectedSize;
                    fetchUpdatedAttributes();
                });
            });

            function fetchUpdatedAttributes() {
                if (selectedColor && selectedSize) {
                    fetch(fetchAttributesUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify({
                                product_id: productId,
                                color: selectedColor,
                                size: selectedSize
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Check if the sliders have been initialized before calling unslick
                                if ($('.product-big-slider').hasClass('slick-initialized')) {
                                    $('.product-big-slider').slick('unslick');
                                }
                                if ($('.product-thumbnail-slider').hasClass('slick-initialized')) {
                                    $('.product-thumbnail-slider').slick('unslick');
                                }

                                //store current color and size
                                currentSelectedColor = data.color;
                                currentSelectedSize = data.size;

                                document.querySelector('.price h5').textContent = `$${data.price}`;
                                document.querySelector('input[name="price"]').value = data.price;




                                let imgHtml = '';
                                data.images.forEach(image => {
                                    imgHtml +=
                                        `<div class="big-sld-items"><img class="img-fluid" src="${image}" alt=""></div>`;

                                });


                                const slider = document.querySelector('.product-thumbnail-slider');
                                const slider1 = document.querySelector('.product-big-slider');
                                // console.log(slider1);
                                slider.innerHTML = imgHtml;
                                slider1.innerHTML = imgHtml;

                                $('#product-big-slider').slick({
                                    slidesToShow: 1,
                                    slidesToScroll: 1,
                                    arrows: false,
                                    fade: true,
                                    asNavFor: '#product-thumbnail-slider'
                                });
                                $('#product-thumbnail-slider').slick({
                                    slidesToShow: 4,
                                    slidesToScroll: 1,
                                    // vertical:true,
                                    asNavFor: '#product-big-slider',
                                    dots: false,
                                    focusOnSelect: true,
                                    //verticalSwiping:true,
                                    prevArrow: '<button class="slick-prev slick-arrow"><i class="fa-solid fa-angle-left"></i></button>',
                                    nextArrow: '<button class="slick-next slick-arrow"><i class="fa-solid fa-angle-right"></i></button>',
                                    responsive: [{
                                            breakpoint: 992,
                                            settings: {
                                                // vertical: true,
                                            }
                                        },
                                        {
                                            breakpoint: 768,
                                            settings: {
                                                // vertical: true,
                                            }
                                        },
                                        {
                                            breakpoint: 580,
                                            settings: {
                                                // vertical: true,
                                                slidesToShow: 3,
                                            }
                                        },
                                        {
                                            breakpoint: 380,
                                            settings: {
                                                // vertical: true,
                                                slidesToShow: 2,
                                            }
                                        }
                                    ]
                                });

                                //         if ($('.glasscase-slider').hasClass('gc-start')) {
                                //     $('.glasscase-slider').glassCase('destroy');
                                // }


                                //         $('.glasscase-slider').glassCase({
                                //             thumbsPosition: 'bottom',
                                //             widthDisplayPerc: 100,
                                //             isDownloadEnabled: false
                                //         });




                            } else {
                                toastr.error('This size and color combination is not available.', 'Error');
                                // document.querySelectorAll('.colors').forEach(el => {
                                //     if(el.getAttribute("data-color") == currentSelectedColor)
                                //     {
                                //         el.classList.add('selected');
                                //         document.querySelector('input[name="color"]').value = '';
                                //     }
                                //     else
                                //     {
                                //         el.classList.remove('selected');
                                //     }
                                // });
                                // document.querySelectorAll('.sizes').forEach(el => {
                                //     if(el.getAttribute("data-size") == currentSelectedSize)
                                //     {
                                //         el.classList.add('selected');
                                //         document.querySelector('input[name="size"]').value = '';
                                //     }
                                //     else
                                //     {
                                //         el.classList.remove('selected');

                                //     }
                                // });
                                document.querySelectorAll('.colors').forEach(el => el.classList.remove(
                                    'selected'));


                                document.querySelectorAll('.sizes').forEach(el => el.classList.remove(
                                    'selected'));


                                document.querySelector('input[name="color"]').value = '';
                                document.querySelector('input[name="size"]').value = '';


                                selectedColor = null;
                                selectedSize = null;
                                currentSelectedColor = null;
                                currentSelectedSize = null;
                                //selectedColor = currentSelectedColor;
                                // selectedSize = currentSelectedSize;

                                document.querySelector('.product-big-slider').innerHTML = '';
                                document.querySelector('.product-thumbnail-slider').innerHTML = '';

                                // **Remove product price**
                                document.querySelector('.price h5').textContent = '';

                                // Optionally clear input field for price
                                document.querySelector('input[name="price"]').value = '';


                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            document.querySelector('.price h5').textContent = '';
                            document.querySelector('input[name="price"]').value = '';
                            document.querySelector('.glasscase-slider').innerHTML = ''; // Clear images on error
                        });

                }


            }



            // function fetchUpdatedAttributes() {
            //     if (selectedColor && selectedSize) {
            //         fetch(fetchAttributesUrl, {
            //                 method: 'POST',
            //                 headers: {
            //                     'Content-Type': 'application/json',
            //                     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
            //                         'content')
            //                 },
            //                 body: JSON.stringify({
            //                     product_id: productId,
            //                     color: selectedColor,
            //                     size: selectedSize
            //                 })
            //             })
            //             .then(response => response.json())
            //             .then(data => {
            //                 if (data.success) {

            //                     document.querySelector('.product-dtl-img img').src = data.image;
            //                     document.querySelector('.price h5').textContent = `$${data.price}`;

            //                     // Update hidden fields for price and image
            //                     document.querySelector('input[name="price"]').value = data.price;
            //                     document.querySelector('input[name="image"]').value = data.img;
            //                 } else {
            //                     // Clear fields for invalid combinations
            //                    // document.querySelector('.product-dtl-img img').src = '';
            //                    // document.querySelector('.price h5').textContent = '';
            //                     //document.querySelector('input[name="price"]').value = '';
            //                    // document.querySelector('input[name="image"]').value = '';
            //                     alert('No such combo available.');
            //                     document.querySelectorAll('.colors').forEach(el => el.classList.remove('selected'));
            //                    document.querySelectorAll('.sizes').forEach(el => el.classList.remove('selected'));
            //                 }
            //             })
            //             .catch(error => {
            //                 console.error('Error:', error);
            //                 document.querySelector('.product-dtl-img img').src = '';
            //                 document.querySelector('.price h5').textContent = '';
            //                 document.querySelector('input[name="price"]').value = '';
            //                 document.querySelector('input[name="image"]').value = '';
            //             });
            //     }
            // }
        });
    </script>
@endpush
