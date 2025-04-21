<div class="row mt-3">
    <div class="col-12">
        <div class="card mb-6">
            <div class="card-datatable">
                <table class="datatables-order-details table mb-0">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th class="w-50">Item Name & Order Detail</th>
                            <th>Product Images</th>
                            <th class="text-center w-25">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 0; @endphp

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
                                        <td class="text-center">
                                            <img src="{{ url(asset('storage/' . $bundle->image)) }}" alt="{{ $bundle->image }}" width="50" height="50">
                                        </td>
                                        <td><strong>Bundle Price: ${{ $bundle->price }}</strong></td>
                                    </tr>
                                    @foreach ($orders as $order)
                                        @php
                                            $pr_aatr = '';
                                            $attrContents = json_decode($order->attributes);
                                            if (!empty($order->bundle->products[$k]['id'])) {
                                                $pr_aatr = App\Models\ProductAttribute::where('product_id', $order->bundle->products[$k]['id'])
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
                                                        <span style="background:{{ $attrContents->color }}; display:inline-block; width:20px; height:20px; border:1px solid #ccc;"></span>
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
                                                            $positions = json_decode($order->positions, true);
                                                            $position_image = [];
                                                            if (is_array($positions)) {
                                                                foreach ($positions as $position) {
                                                                    if (is_array($position)) {
                                                                        foreach ($position as $pos) {
                                                                            $image = App\Models\PositionImage::where('id', $pos)->pluck('image')->first();
                                                                            $position_image[] = $image ? url('assets/frontend/' . $image) : '';
                                                                        }
                                                                    } else {
                                                                        $image = App\Models\PositionImage::where('id', $position)->pluck('image')->first();
                                                                        $position_image[] = $image ? url('assets/frontend/' . $image) : '';
                                                                    }
                                                                }
                                                            }
                                                        @endphp
                                                        @foreach ($position_image as $img)
                                                            <img style="height: 25px; width:25px;" src="{{ $img }}" alt="" />
                                                        @endforeach
                                                    </li>
                                                </ul>
                                                @php $k++; @endphp
                                            </td>
                                            <td class="text-center">
                                                @if (!empty($pr_aatr))
                                                    <img src="{{ url(asset('storage/' . $pr_aatr->image)) }}" class="img-fluid img-thumbnail" style="width: 150px;" />
                                                @endif
                                            </td>
                                            <td>
                                                <p>Email On Back: {{ $order->is_email_checked ? 'YES' : 'NO' }}</p>
                                                <p>Phone On Back: {{ $order->is_phone_checked ? 'YES' : 'NO' }}</p>
                                                <p>Comment: {{ $order->comments }}</p>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @endforeach
                        @endif

                        @if ($ecomOrders->isNotEmpty())
                            @foreach ($ecomOrders as $odrr)
                                @php $attrContents = json_decode($odrr->attributes); @endphp
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>
                                        <h4>{{ $odrr->product->name }}</h4>
                                        <ul>
                                            <li><strong>Colour:</strong>
                                                <span style="background:{{ $attrContents->color }}; width:20px; height:20px; border:1px solid #ccc; display:inline-block;"></span>
                                            </li>
                                            <li><strong>Size:</strong> {{ $attrContents->size }}</li>
                                            <li><strong>Quantities:</strong> {{ $attrContents->quantity }}</li>
                                            @if ($attrContents->cart_image)
                                                <li><strong>Logo Image:</strong>
                                                    @foreach ($attrContents->cart_image as $img)
                                                        <img style="height: 25px; width:25px;" src="{{ url('storage/' . $img) }}" alt="" />
                                                    @endforeach
                                                </li>
                                            @endif
                                            @if ($attrContents->note)
                                                <li><strong>Note:</strong> {{ $attrContents->note }}</li>
                                            @endif
                                        </ul>
                                    </td>
                                    <td class="text-center">
                                        <img src="{{ url(asset('storage/' . $attrContents->image)) }}" class="img-fluid img-thumbnail" style="width: 150px;" />
                                    </td>
                                    <td><strong>Product Price: ${{ $odrr->amount }}</strong></td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>

                <div class="d-flex justify-content-end align-items-center m-6 mb-2">
                    <div class="order-calculations">
                        <div class="d-flex justify-content-start mb-2">
                            <span class="w-px-100 text-heading">Subtotal:</span>
                            <h6 class="mb-0">${{ $odr->amount }}</h6>
                        </div>
                        @foreach ($odr->tax as $tx)
                            @php
                                $tax_t = App\Models\Tax::where('tax_type', $tx->tax_type)->first();
                            @endphp
                            <div class="d-flex justify-content-start mb-2">
                                <span class="w-px-100 text-heading">{{ $tx->tax_type }} ({{ $tax_t->tax_code }}):</span>
                                <h6 class="mb-0">+ ${{ round($tx->taxable_amount) }}</h6>
                            </div>
                        @endforeach
                        <div class="d-flex justify-content-start">
                            <h6 class="w-px-100 mb-0">Total:</h6>
                            <h6 class="mb-0">${{ round($odr->final_amount) }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
