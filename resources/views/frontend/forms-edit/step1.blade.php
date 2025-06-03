<div id="step-1" class="">
    <div class="step-bdy">
        <div class="title-prt2">
            <h2>STEP 1</h2>
            <p>SELECTS YOUR COLOURS, YOUR QUANTITIES AND YOUR SIZE.</p>
        </div>

        <div class="row mt-5">

            @empty(!$bundle->products)

                @foreach ($bundle->products as $key => $product)
                    <div class="col-md-4">
                        <div class="product-bx mb-4">
                            <div class="pro-img">
                                @php

                                    if (isset($cart->contents)) {
                                        foreach ($product->color as $color) {
                                            foreach ($cart->contents as $content) {
                                                $imgData = json_decode($content->contents, true);

                                                // echo '<pre>'; print_r($colorData['color']);die;
                                                // echo '<pre>'; print_r($color->value);die;
                                                if ($imgData['color'] == $color->value) {
                                                    $isSelected = true;

                                                    break;
                                                }
                                            }
                                        }
                                    }

                                @endphp
                                <img class="img-fluid"
                                    src="{{ $color->image ? url(asset('storage/' . $color->image)) : url(asset('assets/frontend/images/t-shirt.png')) }}"
                                    alt="" id="pr_image_{{ $product->id }}">
                            </div>
                            <h4>{{ $product->pivot->quantity }} {{ $product->name }}</h4>
                            @if ($product->sub_category_id == '1' || $product->sub_category_id == '2')
                                <p class="remaining-text" id="remaining-text-{{ $product->id }}" style="color: red;">
                                    {{ $product->pivot->quantity }} {{ $product->name }}s remaining to select
                                </p>
                            @endif

                            <input type="hidden" class="printproducthid"
                                data-productquantity="{{ $product->pivot->quantity }}"
                                name="product[{{ $key }}][id]" value="{{ $product->id }}">

                            <input type="hidden" id="size_extra_cost_{{ $product->id }}"
                                name="product[{{ $key }}][size_extra_cost]" value="0.00">

                            @if ($bundle->discount_percentage > 0)
                                @php
                                    $discountedPrice = round(
                                        $bundle->price - ($bundle->price * $bundle->discount_percentage) / 100,
                                    );
                                    $discountValue = $bundle->price - $discountedPrice;
                                @endphp
                                <input type="hidden" name="discount" value="{{ $discountValue }}">
                            @else
                                <input type="hidden" name="discount" value="0.00">
                            @endif

                            <div class="color-size-wrapper" data-productid="{{ $product->id }}"
                                data-key="{{ $key }}">
                                <div class="filters-color">
                                    <label for="select-color">Select Color</label>
                                    <div class="color-selector">

                                        @isset($product->color)
                                            @foreach ($product->color as $key2 => $color)
                                                @php

                                                    $isSelected = false;
                                                    if (isset($cart->contents)) {
                                                        foreach ($cart->contents as $content) {
                                                            $colorData = json_decode($content->contents, true);

                                                            // echo '<pre>'; print_r($colorData['color']);die;
                                                            // echo '<pre>'; print_r($color->value);die;
                                                            if ($colorData['color'] == $color->value) {
                                                                $isSelected = true;

                                                                break;
                                                            }
                                                        }
                                                    }
                                                @endphp


                                                <div data-attrval="{{ $color->attr_id }}" data-color="{{ $color->value }}"
                                                    data-prid="{{ $product->id }}" data-key="{{ $key }}"
                                                    data-image="{{ !is_null($color->image) ? url(asset('storage/' . $color->image)) : url(asset('assets/frontend/images/t-shirt.png')) }}"
                                                    class="entry {{ $isSelected ? 'active' : '' }}"
                                                    style="background: {{ $color->value }};">&nbsp;
                                                </div>
                                            @endforeach
                                        @endisset

                                        <input type="hidden" name="product[{{ $key }}][color_attribute_id]"
                                            value="{{ $colorData['attr_id'] }}" id="color_attr_{{ $key }}">

                                        <input type="hidden" name="product[{{ $key }}][color]"
                                            value="{{ $colorData['color'] }}" id="color_{{ $key }}">

                                    </div>
                                </div>
                                <div class="filters-color mt-3">
                                    <label>Size / Quantity</label>
                                    <div class="size-select-bx">
                                        @if (!$product->size->isEmpty())
                                            @php
                                                if (isset($cart->contents)) {
                                                    foreach ($cart->contents as $content) {
                                                        $sizeData = json_decode($content->contents, true);
                                                        $myData[] = $sizeData['size'];
                                                    }
                                                }

                                                // echo '<pre>'; print_r($myData) ;die;
                                                // echo '<pre>'; dd($product->size);

                                            @endphp

                                            @foreach ($product->size as $key3 => $size)
                                                {{-- @php

                                            echo '<pre>'; print_r();die;

                                            @endphp --}}

                                                <div class="form-group">
                                                    <label>{{ $size->value }}</label>
                                                    <!-- Quantity selection for each size -->
                                                    <select
                                                        class="form-select form-select-sm productsize productquant-{{ $product->id }}"
                                                        data-cost="{{ $size->extra_cost }}" data-size="{{ $size->value }}"
                                                        data-productid="{{ $product->id }}"
                                                        id="productquant-{{ $product->id . '-' . $size->value }}"
                                                        name="product[{{ $key }}][size][{{ $key3 }}][quantity]">

                                                        @for ($i = 0; $i <= $product->pivot->quantity; $i++)
                                                            <option value="{{ $i }}"
                                                                {{ $myData[$key][$key3]['quantity'] == $i ? 'selected' : '' }}>
                                                                {{ $i }}</option>
                                                        @endfor
                                                    </select>

                                                    <!-- Hidden fields for each size to store attribute_id and attribute_value -->
                                                    <input type="hidden"
                                                        name="product[{{ $key }}][size][{{ $key3 }}][attribute_id]"
                                                        value="{{ $size->id }}">
                                                    <input type="hidden"
                                                        name="product[{{ $key }}][size][{{ $key3 }}][attribute_value]"
                                                        value="{{ $size->value }}">

                                                    <input type="hidden"
                                                        name="product[{{ $key }}][size][{{ $key3 }}][extra_cost]"
                                                        value="{{ $size->extra_cost }}">
                                                </div>

                                                <input type="hidden" name="product[{{ $key }}][type]"
                                                    value="{{ $product->slug }}">
                                            @endforeach
                                        @else
                                            <!-- Handling one-size items -->
                                            <div class="form-group">
                                                <label>One Size</label>
                                            </div>
                                            <input type="hidden" name="product[{{ $key }}][size][0][quantity]"
                                                value="{{ $product->pivot->quantity }}">
                                            <input type="hidden" name="product[{{ $key }}][size][0][attribute_id]"
                                                value="">
                                            <input type="hidden" name="product[{{ $key }}][size][0][attribute_value]"
                                                value="">
                                            <input type="hidden" name="product[{{ $key }}][type]" value="cap">
                                        @endif

                                        {{-- <input type="hidden" name="product[{{ $key }}][type]" value="{{ $product->type }}"> --}}
                                    </div>
                                    <div class="error-message" id="error-product-{{ $product->id }}"></div>
                                </div>
                            </div>
                            @php
                                $productContents = collect($cart->contents)->filter(function($content) use ($product) {
                                    $decoded = json_decode($content->contents, true);
                                    return $content->product_id == $product->id;
                                })->values();
                            @endphp


                            @if ($productContents->count() > 1)
                                @foreach ($productContents->slice(1) as $extraIndex => $extraContent)
                                    @php
                                        $extraData = json_decode($extraContent->contents, true);
                                    @endphp

                                    <div class="color-size-wrapper mt-3" data-productid="{{ $product->id }}" data-key="{{ $key }}">
                                        <div class="filters-color">
                                            <label>Select Color</label>
                                            <div class="color-selector">
                                                @foreach ($product->color as $color)
                                                    <div data-attrval="{{ $color->attr_id }}"
                                                        data-color="{{ $color->value }}"
                                                        data-prid="{{ $product->id }}"
                                                        data-key="{{ $key }}"
                                                        data-image="{{ !is_null($color->image) ? url(asset('storage/' . $color->image)) : url(asset('assets/frontend/images/t-shirt.png')) }}"
                                                        class="entry {{ $extraData['color'] == $color->value ? 'active' : '' }}"
                                                        style="background: {{ $color->value }};">&nbsp;
                                                    </div>
                                                @endforeach

                                                <input type="hidden" name="product[{{ $key }}][extra][{{ $extraIndex }}][color_attribute_id]"
                                                    value="{{ $extraData['attr_id'] }}">
                                                <input type="hidden" name="product[{{ $key }}][extra][{{ $extraIndex }}][color]"
                                                    value="{{ $extraData['color'] }}">
                                            </div>
                                        </div>

                                        <div class="filters-color mt-3">
                                            <label>Size / Quantity</label>
                                            <div class="size-select-bx">
                                                @foreach ($product->size as $sizeKey => $size)
                                                    <div class="form-group">
                                                        <label>{{ $size->value }}</label>
                                                        <select
                                                            class="form-select form-select-sm productsize productquant-{{ $product->id }}"
                                                            data-cost="{{ $size->extra_cost }}"
                                                            data-size="{{ $size->value }}"
                                                            data-productid="{{ $product->id }}"
                                                            name="product[{{ $key }}][extra][{{ $extraIndex }}][size][{{ $sizeKey }}][quantity]">
                                                            @for ($i = 0; $i <= $product->pivot->quantity; $i++)
                                                                <option value="{{ $i }}"
                                                                    {{ (isset($extraData['size'][$sizeKey]['quantity']) && $extraData['size'][$sizeKey]['quantity'] == $i) ? 'selected' : '' }}>
                                                                    {{ $i }}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                        <input type="hidden"
                                                            name="product[{{ $key }}][extra][{{ $extraIndex }}][size][{{ $sizeKey }}][attribute_id]"
                                                            value="{{ $size->id }}">
                                                        <input type="hidden"
                                                            name="product[{{ $key }}][extra][{{ $extraIndex }}][size][{{ $sizeKey }}][attribute_value]"
                                                            value="{{ $size->value }}">
                                                        <input type="hidden"
                                                            name="product[{{ $key }}][extra][{{ $extraIndex }}][size][{{ $sizeKey }}][extra_cost]"
                                                            value="{{ $size->extra_cost }}">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-sm btn-danger mt-3 remove-extra-color">Remove</button>
                                    </div>
                                @endforeach
                            @endif

                            <div class="additional-color-blocks mt-3" id="extra-colors-{{ $product->id }}"></div>
                            @if ($product->sub_category_id == '1' || $product->sub_category_id == '2')
                                <button type="button" class="btn btn-outline-primary btn-sm mt-3 add-extra-color"
                                    data-productid="{{ $product->id }}" data-key="{{ $key }}">
                                    Choose Extra Color
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach

            @endempty

        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('.add-extra-color').on('click', function() {
            const productId = $(this).data('productid');
            const key = $(this).data('key');

            // Always clone the FIRST matching wrapper only
            const $originalWrapper = $(
                `.color-size-wrapper[data-productid="${productId}"][data-key="${key}"]`).first();
            const $clone = $originalWrapper.clone();

            // Reset selects inside clone
            $clone.find('select').each(function() {
                $(this).prop('selectedIndex', 0);

                const productId = $(this).data('productid');
                if (productId) {
                    $(this).removeClass(function(index, className) {
                        return (className.match(/productquant-\S+/g) || []).join(' ');
                    });

                    const newId = `productquant-${productId}-${Date.now()}`;
                    $(this).attr('id', newId).addClass(`productquant-${productId}`);
                }
            });

            // Make input names unique
            const time = Date.now();
            $clone.find('[name]').each(function() {
                const name = $(this).attr('name');
                const newName = name.replace(`product[${key}]`,
                    `product[${key}][extra][${time}]`);
                $(this).attr('name', newName);
            });

            // Remove any previous .remove-extra-color buttons before adding new one
            $clone.find('.remove-extra-color').remove();

            // Add remove button
            const $removeBtn = $('<button>', {
                class: 'btn btn-sm btn-danger mt-3 remove-extra-color',
                type: 'button',
                text: 'Remove'
            });

            $clone.append($removeBtn);

            // Append clone to container
            $(`#extra-colors-${productId}`).append($clone);
        });


        // Delegate event to remove cloned blocks and update remaining
        $(document).on('click', '.remove-extra-color', function() {
            const $wrapper = $(this).closest('.color-size-wrapper');
            const productId = $wrapper.data('productid');
            $wrapper.remove();
            updateRemaining(productId);
        });


        // Color selector click
        $(document).on('click', '.color-selector .entry', function(e) {
            e.preventDefault();

            let $entry = $(this);
            let image = $entry.data('image');
            let rowKey = $entry.data('key');
            let productId = $entry.data('prid');
            let colorValue = $entry.data('color');
            let colorAttrValue = $entry.data('attrval');

            let $colorSelector = $entry.closest('.color-selector');
            let $wrapper = $entry.closest('.color-size-wrapper');

            // Fix selectors with backticks for interpolation
            $wrapper.find(`#color_${rowKey}`).val(colorValue);
            $wrapper.find(`#color_attr_${rowKey}`).val(colorAttrValue);

            $colorSelector.find('.entry').removeClass('active');
            $entry.addClass('active');

            // Only update image if this is the FIRST .color-size-wrapper for this product and key
            let isFirst = $wrapper.is(
                `.color-size-wrapper[data-productid="${productId}"][data-key="${rowKey}"]:first`
            );

            if (isFirst) {
                $('#pr_image_' + productId).attr('src', image);
            }
        });

        // Trigger default selection
        $('.color-selector .entry.active').trigger('click');

        // Change event delegated for productquant classes
        $(document).on('change', 'select.productsize[class*="productquant-"]', function() {
            const productId = $(this).data('productid');
            if (!productId) {
                console.warn('Missing data-productid on', this);
                return;
            }
            updateRemaining(productId);
        });


        function updateRemaining(productId) {
            // Fix selectors with backticks and quotes
            let total = parseInt($(`.printproducthid[value="${productId}"]`).data('productquantity')) || 0;

            let selectedTotal = 0;

            $(`.productquant-${productId}`).each(function() {
                selectedTotal += parseInt($(this).val()) || 0;
            });

            let remaining = total - selectedTotal;
            let $remainingText = $(`#remaining-text-${productId}`);

            if (remaining <= 0) {
                remaining = 0;
                $remainingText.css('color', 'green');
            } else {
                $remainingText.css('color', 'red');
            }

            $remainingText.text(`${remaining} T-shirts remaining to select`);

            // Update max options in selects
            $(`.productquant-${productId}`).each(function() {
                let currentVal = parseInt($(this).val());
                let $select = $(this);

                $select.empty();
                for (let i = 0; i <= remaining + currentVal; i++) {
                    $select.append(
                        `<option value="${i}" ${i === currentVal ? 'selected' : ''}>${i}</option>`);
                }
            });
        }

        // Bind initial change events
        @foreach ($bundle->products as $product)
            @if (!$product->size->isEmpty())
                $('.productquant-{{ $product->id }}').on('change', function() {
                    updateRemaining({{ $product->id }});
                });
            @endif
        @endforeach
        // Initialize remaining count on page load
        @foreach ($bundle->products as $product)
            @if (!$product->size->isEmpty())
                updateRemaining({{ $product->id }});
            @endif
        @endforeach

        $(document).on('change', 'select.productsize[class*="productquant-"]', function() {
            let SizeType = $(this).data('size');
            let extraCost = parseFloat($(this).data('cost'))
            let quantity = $(this).val();

            if(extraCost > 0 && quantity > 0){

                Swal.fire({
                    icon: "info",
                    text: `$${extraCost} will be charged for each garment of ${SizeType}`
                });
            }
        });
    });
</script>
    {{-- <script>
        $(document).ready(function() {
            $('.color-selector .entry').on('click', function(e) {
                e.preventDefault();

                let image = $(this).data('image');
                let rowKey = $(this).data('key');
                let productId = $(this).data('prid');
                let colorValue = $(this).data('color');
                let colorAttrValue = $(this).data('attrval');

                // Update product image
                $('#pr_image_' + productId).attr('src', image);

                // Update hidden inputs with selected color
                $(`#color_${rowKey}`).val(colorValue);
                $(`#color_attr_${rowKey}`).val(colorAttrValue);

                // Remove active class from all other colors in the same selector
                $(this).closest('.color-selector').find('.entry').removeClass('active');

                // Add active class to the newly selected color
                $(this).addClass('active');

                console.log('Row Key:', rowKey);
                console.log('Color:', colorValue);
                console.log('Color Attribute ID:', colorAttrValue);
                console.log('Image:', image);
            });

            $('.productquant-{{ $product->id }}').on('change', function() {
                updateRemaining($(this).data('productid'));
            });

            function updateRemaining(productId) {
                let total = parseInt($(`.printproducthid[data-productquantity][value="${productId}"]`).data(
                    'productquantity'));
                let selectedTotal = 0;

                $(`.productquant-${productId}`).each(function() {
                    selectedTotal += parseInt($(this).val());
                });

                let remaining = total - selectedTotal;
                let $remainingText = $(`#remaining-text-${productId}`);

                if (remaining <= 0) {
                    remaining = 0;
                    $remainingText.css('color', 'green');
                } else {
                    $remainingText.css('color', 'red');
                }

                $remainingText.text(`${remaining} T-shirts remaining to select`);

                // Update max options in selects
                $(`.productquant-${productId}`).each(function() {
                    let currentVal = parseInt($(this).val());
                    let $select = $(this);
                    let selectedSize = $select.data('size');

                    $select.empty();
                    for (let i = 0; i <= remaining + currentVal; i++) {
                        $select.append(
                            `<option value="${i}" ${i === currentVal ? 'selected' : ''}>${i}</option>`);
                    }
                });
            }

            // Bind initial change events
            @foreach ($bundle->products as $product)
                @if (!$product->size->isEmpty())
                    $('.productquant-{{ $product->id }}').on('change', function() {
                        updateRemaining({{ $product->id }});
                    });
                @endif
            @endforeach
            // Initialize remaining count on page load
            @foreach ($bundle->products as $product)
                @if (!$product->size->isEmpty())
                    updateRemaining({{ $product->id }});
                @endif
            @endforeach
        });
    </script> --}}
@endpush
