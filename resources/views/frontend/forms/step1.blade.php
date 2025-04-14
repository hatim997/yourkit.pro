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

                            <input type="hidden" class="printproducthid"
                                data-productquantity="{{ $product->pivot->quantity }}"
                                name="product[{{ $key }}][id]" value="{{ $product->id }}">

                            <input type="hidden" id="size_extra_cost_{{ $product->id }}"
                                name="product[{{ $key }}][size_extra_cost]" value="0.00">

                            <div class="filters-color">
                                <label for="select-color">Select Color</label>
                                <div class="color-selector">

                                    @isset($product->color)
                                        @foreach ($product->color as $key2 => $color)
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
        });
    </script>
@endpush
