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


                            </div>
                        </div>
                        <div class="product-details-thumbnail">
                            <div class="product-thumbnail-slider" id="product-thumbnail-slider">

                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-7 col-md-12 col-sm-12">
                    <form id="addCart">
                        @csrf
                        @php
                            $cart->contents;
                            foreach ($cart->contents as $content) {
                                $attr = json_decode($content->contents);
                            }
                        @endphp
                        <div class="service-dtls-text">
                            <h3>{{ $product->name }}</h3>
                            {{-- <p>{{ $product->description }}</p> --}}
                            <div class="price">
                                <h5></h5>
                            </div>
                            <div class="service-steps">
                                <h5>COLOR</h5>
                                <div class="service-radio">
                                    <ul class="service-radio-list">

                                        @foreach ($product->color as $cl => $color)
                                            <li data-color="{{ $color }}"
                                                class="{{ $color == $attr->color ? 'selected' : '' }} colors"><a
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
                                                class="{{ $size == $attr->size ? 'selected' : '' }} sizes">
                                                <span>{{ $size }}</span>
                                            </li>
                                        @endforeach


                                    </ul>

                                </div>
                            </div>
                            <div class="service-steps">
                                <h5>Quantity</h5>
                                <div class="prod-details">
                                    <!--<select class="form-select" name="quantity" id="quantitySelector">-->
                                    <!--    <option value="1" @if ($attr->quantity == 1) selected @endif>1</option>-->
                                    <!--    <option value="2" @if ($attr->quantity == 2) selected @endif>2</option>-->
                                    <!--    <option value="3" @if ($attr->quantity == 3) selected @endif>3</option>-->
                                    <!--    <option value="4" @if ($attr->quantity == 4) selected @endif>4</option>-->
                                    <!--    <option value="5" @if ($attr->quantity == 5) selected @endif>5</option>-->
                                    <!--    <option value="6" @if ($attr->quantity == 6) selected @endif>6</option>-->
                                    <!--    <option value="7" @if ($attr->quantity == 7) selected @endif>7</option>-->
                                    <!--    <option value="8" @if ($attr->quantity == 8) selected @endif>8</option>-->
                                    <!--    <option value="9" @if ($attr->quantity == 9) selected @endif>9</option>-->
                                    <!--    <option value="10" @if ($attr->quantity == 10) selected @endif>10</option>-->
                                    <!--</select>-->
                                    <input type="number" class="form-control" name="quantity" id="quantitySelector"
                                        value="{{ $attr->quantity }}" min="1" max="9999">
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
                            @if ($attr->cart_image)
                                @foreach ($attr->cart_image as $img)
                                    <span class="avater-img"><img src="{{ url('storage/' . $img) }}" alt=""></span>
                                @endforeach
                                <p>[If you want change images upload again]</p>
                                <input type="hidden" name="has_existing_images" value="1">
                            @else
                                <input type="hidden" name="has_existing_images" value="0">
                            @endif
                            <input type="file" name="cart_image[]" class="form-control" id="cartImage" multiple>

                            <label for="note" class="form-label">Note </label>
                            <textarea class="form-control" name="note" id="note" cols="30" rows="2"
                                placeholder="Please specify the positioning of the logo print."> {{ trim($attr->note) ?? '' }}</textarea>
                            <input type="hidden" name="product_id" value="{{ $cart->table_id }}">
                            <input type="hidden" name="discount" id="discount" value="{{ $cart->discount }}">
                            <input type="hidden" name="color" value="{{ $attr->color }}">
                            <input type="hidden" name="size" value="{{ $attr->size }}">
                            <input type="hidden" name="price" value="{{ $cart->total_cost }}">
                            <input type="hidden" name="image" value="{{ $attr->image }}">
                            <input type="hidden" value="{{ $cart->table_id }}" name="table_id">
                            <input type="hidden" value="products" name="table">
                            <input type="hidden" name="sessionId" id="sessionId" value="{{ session()->getId() }}">
                            <input type="hidden" name="quantity" id="hiddenQuantity" value="{{ $attr->quantity }}">
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

        $(document).ready(function() {

            let quqantity = "{{ $attr->quantity ?? 1 }}";

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

                let url = '{{ route('frontend.ecom-cart.update', $cart->id) }}';

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
        document.addEventListener('DOMContentLoaded', () => {
            const discounts = @json($product->productVolumeDiscounts);
            let originalPrice = parseFloat({{ $product->ecommerce()->first()->price }});

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

                if (discountApplied) {
                    const discountPercent = discountApplied.discount_percentage;
                    const discountValue = (originalPrice * (discountPercent / 100)).toFixed(2);
                    const discountedPrice = (originalPrice - discountValue).toFixed(2);

                    document.querySelector('.price h5').innerHTML =
                        `$${discountedPrice} <span style="text-decoration: line-through; color: #999; font-size: 16px; margin-left: 5px;">$${originalPrice.toFixed(2)}</span> <span style="color: #d00; font-size: 14px;">(${discountPercent.toFixed(2)}% off)</span>`;
                    document.getElementById('discount').value = discountValue;
                } else {
                    document.querySelector('.price h5').innerHTML = `$${originalPrice.toFixed(2)}`;
                    document.getElementById('discount').value = '0';
                }

                if (nextDiscount) {
                    const qtyNeeded = nextDiscount.quantity - quantity;
                    document.getElementById('volume-discount-message').innerHTML =
                        `Buy ${qtyNeeded} more to get ${nextDiscount.discount_percentage.toFixed(2)}% off!`;
                } else {
                    document.getElementById('volume-discount-message').innerHTML = '';
                }
            }

            // Initial setup
            const qtyInput = document.getElementById('quantitySelector');
            if (qtyInput) {
                updateDiscountMessage(parseInt(qtyInput.value));
                qtyInput.addEventListener('input', (e) => {
                    const quantity = parseInt(e.target.value);
                    if (quantity >= 1) updateDiscountMessage(quantity);
                });
            }

            let selectedColor = null;
            let selectedSize = null;
            const productId = document.querySelector('input[name="product_id"]').value;
            const fetchAttributesUrl = "{{ route('frontend.fetch.ecom.attr') }}";
            const storedColor = "{{ $attr->color }}";
            const storedSize = "{{ $attr->size }}";

            const colorInput = document.querySelector('input[name="color"]');
            const sizeInput = document.querySelector('input[name="size"]');

            const firstColorElement = document.querySelector(`.colors[data-color="${storedColor}"]`);
            const firstSizeElement = document.querySelector(`.sizes[data-size="${storedSize}"]`);

            if (firstColorElement) {
                firstColorElement.classList.add('selected');
                selectedColor = storedColor;
                colorInput.value = selectedColor;
            }

            if (firstSizeElement) {
                firstSizeElement.classList.add('selected');
                selectedSize = storedSize;
                sizeInput.value = selectedSize;
            }

            if (selectedColor && selectedSize) {
                fetchUpdatedAttributes();
            }

            document.querySelectorAll('.colors').forEach((el) => {
                el.addEventListener('click', (e) => {
                    document.querySelectorAll('.colors').forEach(elm => elm.classList.remove(
                        'selected'));
                    e.currentTarget.classList.add('selected');
                    selectedColor = e.currentTarget.dataset.color;
                    colorInput.value = selectedColor;
                    fetchUpdatedAttributes();
                });
            });

            document.querySelectorAll('.sizes').forEach((el) => {
                el.addEventListener('click', (e) => {
                    document.querySelectorAll('.sizes').forEach(elm => elm.classList.remove(
                        'selected'));
                    e.currentTarget.classList.add('selected');
                    selectedSize = e.currentTarget.dataset.size;
                    sizeInput.value = selectedSize;
                    fetchUpdatedAttributes();
                });
            });

            function fetchUpdatedAttributes() {
                if (selectedColor && selectedSize) {
                    fetch(fetchAttributesUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                product_id: productId,
                                color: selectedColor,
                                size: selectedSize
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                if ($('.product-big-slider').hasClass('slick-initialized')) {
                                    $('.product-big-slider').slick('unslick');
                                }
                                if ($('.product-thumbnail-slider').hasClass('slick-initialized')) {
                                    $('.product-thumbnail-slider').slick('unslick');
                                }

                                originalPrice = parseFloat(data.price);
                                updateDiscountMessage(parseInt(document.getElementById('quantitySelector')
                                    .value));
                                document.querySelector('input[name="price"]').value = data.price;

                                let imgHtml = '';
                                let firstImage = '';

                                data.images.forEach((img, i) => {
                                    const relative = img.replace(/^.*\/storage\//, '');
                                    imgHtml +=
                                        `<div class="big-sld-items"><img class="img-fluid" src="/storage/${relative}" alt=""></div>`;
                                    if (i === 0) firstImage = relative;
                                });

                                document.querySelector('.product-thumbnail-slider').innerHTML = imgHtml;
                                document.querySelector('.product-big-slider').innerHTML = imgHtml;
                                document.querySelector('input[name="image"]').value = firstImage;

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
                                    asNavFor: '#product-big-slider',
                                    dots: false,
                                    focusOnSelect: true,
                                    prevArrow: '<button class="slick-prev slick-arrow"><i class="fa-solid fa-angle-left"></i></button>',
                                    nextArrow: '<button class="slick-next slick-arrow"><i class="fa-solid fa-angle-right"></i></button>',
                                    responsive: [{
                                            breakpoint: 992,
                                            settings: {
                                                vertical: true
                                            }
                                        },
                                        {
                                            breakpoint: 768,
                                            settings: {
                                                vertical: true
                                            }
                                        },
                                        {
                                            breakpoint: 580,
                                            settings: {
                                                vertical: true,
                                                slidesToShow: 3
                                            }
                                        },
                                        {
                                            breakpoint: 380,
                                            settings: {
                                                vertical: true,
                                                slidesToShow: 2
                                            }
                                        }
                                    ]
                                });
                            } else {
                                toastr.error('No such combo is available.', 'Error');
                                document.querySelectorAll('.colors').forEach(el => el.classList.remove(
                                    'selected'));
                                document.querySelectorAll('.sizes').forEach(el => el.classList.remove(
                                    'selected'));
                                colorInput.value = '';
                                sizeInput.value = '';
                                selectedColor = null;
                                selectedSize = null;
                            }
                        })
                        .catch(err => {
                            console.error('Error:', err);
                            document.querySelector('.price h5').textContent = '';
                            document.querySelector('input[name="price"]').value = '';
                            document.querySelector('.glasscase-slider').innerHTML = '';
                        });
                }
            }
        });
    </script>

    {{-- <script>
        $(document).ready(function() {
            const discounts = @json($product->productVolumeDiscounts);
            let originalPrice = parseFloat({{ $product->ecommerce()->first()->price }});

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

                    $('.price h5').html(
                        `$${discountedPrice} <span style="text-decoration: line-through; color: #999; font-size: 16px; margin-left: 5px;">$${originalPrice.toFixed(2)}</span> <span style="color: #d00; font-size: 14px;">(${discountPercent.toFixed(2)}% off)</span>`
                    );
                } else {
                    $('.price h5').html(`$${originalPrice.toFixed(2)}`);
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

            // Initial setup
            const initialQuantity = parseInt($('#quantitySelector').val());
            updateDiscountMessage(initialQuantity);

            // On quantity change
            $('#quantitySelector').on('input change', function() {
                const quantity = parseInt($(this).val());
                if (quantity >= 1) {
                    updateDiscountMessage(quantity);
                }
            });
        });
    </script> --}}

    {{-- <Script>
        document.addEventListener('DOMContentLoaded', () => {
            let selectedColor = null;
            let currentSelectedColor = null;
            let selectedSize = null;
            let currentSelectedSize = null;

            const productId = document.querySelector('input[name="product_id"]').value;
            const fetchAttributesUrl = "{{ route('frontend.fetch.ecom.attr') }}";


            const storedColor = "{{ $attr->color }}";
            const storedSize = "{{ $attr->size }}";


            const firstColorElement = document.querySelector(`.colors[data-color="${storedColor}"]`);
            const firstSizeElement = document.querySelector(`.sizes[data-size="${storedSize}"]`);

            if (firstColorElement) {
                firstColorElement.classList.add('selected');
                selectedColor = storedColor;
                document.querySelector('input[name="color"]').value = selectedColor;
            }

            if (firstSizeElement) {
                firstSizeElement.classList.add('selected');
                selectedSize = storedSize;
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
                                // Clear existing Slick sliders before re-initializing
                                if ($('.product-big-slider').hasClass('slick-initialized')) {
                                    $('.product-big-slider').slick('unslick');
                                }
                                if ($('.product-thumbnail-slider').hasClass('slick-initialized')) {
                                    $('.product-thumbnail-slider').slick('unslick');
                                }

                                currentSelectedColor = data.color;
                                currentSelectedSize = data.size;

                                // document.querySelector('.price h5').textContent = `$${data.price}`;
                                // document.querySelector('input[name="price"]').value = data.price;
                                const updatedPrice = parseFloat(data.price);
                                originalPrice = updatedPrice; // Update the global price
                                updateDiscountMessage(parseInt(document.getElementById('quantitySelector').value));

                                let imgHtml = '';
                                let firstImage = ''; // Store first image to update hidden input

                                data.images.forEach((image, index) => {
                                    let relativeImage = image.replace(/^.*\/storage\//,
                                    ''); // Remove base URL
                                    imgHtml +=
                                        `<div class="big-sld-items"><img class="img-fluid" src="/storage/${relativeImage}" alt=""></div>`;

                                    if (index === 0) {
                                        firstImage =
                                        relativeImage; // Capture the first image as a relative path
                                    }
                                });

                                const slider = document.querySelector('.product-thumbnail-slider');
                                const slider1 = document.querySelector('.product-big-slider');

                                slider.innerHTML = imgHtml;
                                slider1.innerHTML = imgHtml;

                                // ✅ Update the hidden input field with the new image
                                document.querySelector('input[name="image"]').value = firstImage;

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
                                    asNavFor: '#product-big-slider',
                                    dots: false,
                                    focusOnSelect: true,
                                    prevArrow: '<button class="slick-prev slick-arrow"><i class="fa-solid fa-angle-left"></i></button>',
                                    nextArrow: '<button class="slick-next slick-arrow"><i class="fa-solid fa-angle-right"></i></button>',
                                    responsive: [{
                                            breakpoint: 992,
                                            settings: {
                                                vertical: true
                                            }
                                        },
                                        {
                                            breakpoint: 768,
                                            settings: {
                                                vertical: true
                                            }
                                        },
                                        {
                                            breakpoint: 580,
                                            settings: {
                                                vertical: true,
                                                slidesToShow: 3
                                            }
                                        },
                                        {
                                            breakpoint: 380,
                                            settings: {
                                                vertical: true,
                                                slidesToShow: 2
                                            }
                                        }
                                    ]
                                });
                            } else {
                                toastr.error('No such combo is available.', 'Error');
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
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            document.querySelector('.price h5').textContent = '';
                            document.querySelector('input[name="price"]').value = '';
                            document.querySelector('.glasscase-slider').innerHTML = '';
                        });
                }
            }
        });
    </Script> --}}
@endpush
