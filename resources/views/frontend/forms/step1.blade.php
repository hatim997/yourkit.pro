<div id="step-1" class="">
    <div class="step-bdy">
        <div class="title-prt2">
            <!--<h2>STEP 1</h2>-->
            <p>SELECTS YOUR COLOURS, YOUR QUANTITIES AND YOUR SIZE.</p>
        </div>

        <div class="row mt-5">

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

                            <div class="filters-color">
                                <label for="select-color">Select Color</label>
                                <div class="color-selector">
                                    @isset($product->color)
                                        @php
                                            // Sort product colors according to master order
                                            $sortedColors = collect($product->color)
                                                ->sortBy(function ($color) use ($allColors) {
                                                    $index = array_search(
                                                        strtolower($color->value),
                                                        array_map('strtolower', $allColors),
                                                    );
                                                    return $index !== false ? $index : 999; // put unknown colors at end
                                                })
                                                ->values(); // reset keys
                                        @endphp
                                        {{-- @foreach ($product->color as $key2 => $color)
                                            <div data-attrval="{{ $color->attr_id }}" data-color="{{ $color->value }}"
                                                data-prid="{{ $product->id }}" data-key="{{ $key }}"
                                                data-image="{{ !is_null($color->image) ? url(asset('storage/' . $color->image)) : url(asset('assets/frontend/images/t-shirt.png')) }}"
                                                class="entry {{ $key2 == 0 ? 'active' : '' }}"
                                                style="background: {{ $color->value }};">&nbsp;</div>
                                        @endforeach --}}
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
                                                    data-cost="{{ $size->extra_cost }}" data-size="{{ $size->value }}"
                                                    data-productid="{{ $product->id }}"
                                                    id="productquant-{{ $product->id . '-' . $size->value }}"
                                                    name="product[{{ $key }}][size][{{ $key3 }}][quantity]">

                                                    @for ($i = 0; $i <= $product->pivot->quantity; $i++)
                                                        <option value="{{ $i }}"
                                                            {{ $i == 0 ? 'selected' : '' }}>{{ $i }}</option>
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
                    </div>
                @endforeach

            @endempty

        </div>
    </div>
</div>

@push('scripts')
    <script>
        // $(document).ready(function() {

        //     $('.color-selector .entry').on('click', function(e) {
        //         e.preventDefault()
        //         let image = $(this).data('image');
        //         let rowKey = $(this).data('key');
        //         let productId = $(this).data('prid');
        //         let colorValue = $(this).data('color');

        //         let colorAttrValue = $(this).data('attrval')

        //         $('#pr_image_' + productId).attr('src', image);

        //         $(`#color_${rowKey}`).val(colorValue);
        //         $(`#color_attr_${rowKey}`).val(colorAttrValue);
        //         console.log(rowKey);
        //         console.log('Color:', colorValue);
        //         console.log('Color Attribute ID:', colorAttrValue);
        //         console.log('Image:', image);
        //         $(this).removeClass('active');
        //         $(this).addClass('active');


        //     });

        // })

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
        });
    </script>
@endpush
