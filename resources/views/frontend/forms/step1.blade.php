<div id="step-1" class="">
    <div class="step-bdy">
        <div class="title-prt2">
            <!--<h2>STEP 1</h2>-->
            <p>SELECTS YOUR COLOURS, YOUR QUANTITIES AND YOUR SIZE.</p>
        </div>

        <div class="row mt-5">
            @php
                // Step 1: Collect all color values for each product
                $productColors = collect($bundle->products)->map(function ($product) {
                    return collect($product->color)
                        ->pluck('value')
                        ->map(function ($v) {
                            return strtolower(trim($v));
                        })
                        ->unique();
                });

                // Step 2: Find common colors across all products
                $commonColors = $productColors
                    ->reduce(function ($carry, $colors) {
                        return $carry === null ? $colors : $carry->intersect($colors);
                    }, null)
                    ->values()
                    ->toArray(); // Reset keys
            @endphp


            @empty(!$bundle->products)

                @foreach ($bundle->products as $key => $product)
                    <div class="col-md-4">
                        <div class="product-bx mb-4">
                            <div class="pro-img">
                                <img class="img-fluid"
                                    src="{{ $product->color[0]->image ? url(asset('storage/' . $product->color[0]->image)) : url(asset('assets/frontend/images/t-shirt.png')) }}"
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
                                            @php
                                                // Sort product colors: common first, then the rest
                                                $sortedColors = collect($product->color)
                                                    ->sortBy(function ($color) use ($commonColors) {
                                                        $value = strtolower(trim($color->value));
                                                        $index = array_search($value, $commonColors);
                                                        return $index !== false ? $index : 999; // Common colors first
                                                    })
                                                    ->values();
                                            @endphp
                                            @foreach ($sortedColors as $key2 => $color)
                                                <div data-attrval="{{ $color->attr_id }}" data-color="{{ $color->value }}"
                                                    data-prid="{{ $product->id }}" data-key="{{ $key }}"
                                                    data-image="{{ !is_null($color->image) ? url(asset('storage/' . $color->image)) : url(asset('assets/frontend/images/t-shirt.png')) }}"
                                                    class="entry {{ $key2 == 0 ? 'active' : '' }}"
                                                    style="background: {{ $color->value }};">&nbsp;</div>
                                            @endforeach
                                        @endisset

                                        <input type="hidden" name="product[{{ $key }}][color_attribute_id]"
                                            value="{{ $product->color[0]->attr_id }}" id="color_attr_{{ $key }}">

                                        <input type="hidden" name="product[{{ $key }}][color]"
                                            value="{{ $product->color[0]->value }}" id="color_{{ $key }}">

                                    </div>
                                </div>
                                <div class="filters-color mt-3">
                                    <label>Size / Quantity</label>
                                    <div class="size-select-bx">
                                        @if (!$product->size->isEmpty())
                                            @foreach ($product->size as $key3 => $size)
                                                <div class="form-group">
                                                    <label>{{ $size->value }}</label>

                                                    <!-- Quantity selection for each size -->
                                                    <select
                                                        class="form-select form-select-sm productsize productquant-{{ $product->id }}"
                                                        data-cost="{{ $size->extra_cost }}"
                                                        data-size="{{ $size->value }}"
                                                        data-productid="{{ $product->id }}"
                                                        id="productquant-{{ $product->id . '-' . $size->value }}"
                                                        name="product[{{ $key }}][size][{{ $key3 }}][quantity]">

                                                        @for ($i = 0; $i <= $product->pivot->quantity; $i++)
                                                            <option value="{{ $i }}"
                                                                {{ $i == 0 ? 'selected' : '' }}>{{ $i }}
                                                            </option>
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
                                            <input type="hidden"
                                                name="product[{{ $key }}][size][0][attribute_id]" value="">
                                            <input type="hidden"
                                                name="product[{{ $key }}][size][0][attribute_value]"
                                                value="">
                                            <input type="hidden" name="product[{{ $key }}][type]" value="cap">
                                        @endif

                                        {{-- <input type="hidden" name="product[{{ $key }}][type]" value="{{ $product->type }}"> --}}
                                    </div>
                                    <div class="error-message" id="error-product-{{ $product->id }}"></div>
                                </div>
                            </div>
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
    {{-- <script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.add-extra-color').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-productid');
                const key = this.getAttribute('data-key');

                // Fix: Use template literals correctly for querySelector
                const wrapper = document.querySelector(
                    `.color-size-wrapper[data-productid="${productId}"][data-key="${key}"]`
                );

                const clone = wrapper.cloneNode(true);

                // Reset selects inside clone
                clone.querySelectorAll('select').forEach(select => {
                    select.selectedIndex = 0;

                    // Re-add correct productquant class for proper updateRemaining tracking
                    const productId = select.getAttribute('data-productid');
                    if (productId) {
                        select.classList.forEach(cls => {
                            if (cls.startsWith('productquant-')) {
                                select.classList.remove(cls);
                            }
                        });
                        select.setAttribute('data-productid', productId);
                        select.id = `productquant-${productId}-${Date.now()}`;
                        select.classList.add(`productquant-${productId}`);
                    }
                });

                // Make input names unique — fix string quotes & syntax
                const time = Date.now();
                clone.querySelectorAll('[name]').forEach(input => {
                    input.setAttribute('name', input.name.replace(
                        `product[${key}]`,
                        `product[${key}][extra][${time}]`
                    ));
                });

                // Add remove button
                const removeBtn = document.createElement('button');
                removeBtn.className = 'btn btn-sm btn-danger mt-3 remove-extra-color';
                removeBtn.type = 'button';
                removeBtn.textContent = 'Remove';

                clone.appendChild(removeBtn);

                // Fix: container selector with backticks and quotes
                const container = document.getElementById(`extra-colors-${productId}`);
                container.appendChild(clone);
            });
        });

        // Delegate event to remove cloned blocks
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-extra-color')) {
                e.target.closest('.color-size-wrapper').remove();
            }
        });
    });
</script> --}}

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
@endpush

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

            // Fix: Trigger click on the default active color to populate inputs
            $('.color-selector .entry.active').trigger('click');

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
        });
    </script> --}}
{{-- @endpush --}}
