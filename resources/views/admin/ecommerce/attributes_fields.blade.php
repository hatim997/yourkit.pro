<div class="col-12 col-lg-12">
    <table class="table table-stripped" id="variantTable">
        <thead>
            <th>Size</th>
            <th>Color</th>
            <th>Price</th>
            <th>No. of stocks</th>
            <th>Image <small class="text-muted">(Image resolution must be 350px × 300px)</small></th>
            <th></th>
        </thead>
        <tbody>

           @if(!empty($product))
           @foreach($product->ecommerce as $key => $ecom)
          
           <tr id="row_0">
            <td>
                <select class="form-select " name="attribute[{{$key}}][size]" id="size_0" required="">
                    <option value="">Select Option</option>
                    @foreach ($sizes as $sz)
                        <option value="{{ $sz->id }}" @if($ecom->size_value_id == $sz->id) selected @endif>{{ $sz->value }}</option>
                    @endforeach

                </select>
            </td>

            <td>
                <select class="form-select " name="attribute[{{$key}}][color]" id="color_0" required="">
                    <option value="">Select Option</option>
                    @foreach ($colors as $cl)
                        <option value="{{ $cl->id }}" style="background-color: {{ $cl->value }}"  @if($ecom->color_value_id == $cl->id) selected @endif>
                            {{ $cl->value }}</option>
                    @endforeach
                </select>
            </td>
            <td><input class="form-control" type="number" name="attribute[{{$key}}][price]" value="{{$ecom->price}}" required></td>
            <td><input class="form-control" type="number" name="attribute[{{$key}}][quantity]" value="{{$ecom->quantity}}" required></td>
            <td><input class="form-control" type="file" name="attribute[{{$key}}][image][]" multiple>
            @foreach($ecom->images as $img)<img class="img-fluid" src="{{ url('storage/'. $img->image) }}"
                alt=""  width="30" 
                height="30" />@endforeach</td>
                <input type="hidden" name="attribute[{{$key}}][id]" value={{$ecom->id}}>
                @if($key==0)
                <td><button type="button" class="btn btn-success addRow"><i class="fa fa-plus"></i></button></td>
                @else <td><button type="button" class="btn btn-danger removeRow"><i class="fa fa-minus"></i></button></td>
                @endif
        </tr>
        
           @endforeach
           @else
            <tr id="row_0">
                <td>
                    <select class="form-select " name="attribute[0][size]" id="size_0" required="">
                        <option value="">Select Option</option>
                        @foreach ($sizes as $sz)
                            <option value="{{ $sz->id }}">{{ $sz->value }}</option>
                        @endforeach

                    </select>
                </td>

                <td>
                    <select class="form-select " name="attribute[0][color]" id="color_0" required="">
                        <option value="">Select Option</option>
                        @foreach ($colors as $cl)
                            <option value="{{ $cl->id }}" style="background-color: {{ $cl->value }}">
                                {{ $cl->value }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input class="form-control" type="number" name="attribute[0][price]" required></td>
                <td><input class="form-control" type="number" name="attribute[0][quantity]" required></td>
                <td><input class="form-control" type="file" name="attribute[0][image][]" multiple></td>
                <td><button type="button" class="btn btn-success addRow"><i class="fa fa-plus"></i></button></td>
            </tr>
            @endif
        </tbody>
    </table>

</div>

@push('scripts')
    <script>
        $(document).ready(function() {

            const sizes = JSON.parse(`<?php echo isset($sizes) ? $sizes : json_encode([]); ?>`);
            const colors = JSON.parse(`<?php echo isset($colors) ? $colors : json_encode([]); ?>`);

           // console.log(sizes)

            let counter = {{ isset($product) && $product->ecommerce->count() ?$product->ecommerce->count() : 1 }};
            console.log(counter)
            let sizeOption = '';
            let colorOption = '';

            $.each(sizes, function(sz, size) {
                sizeOption += '<option value="' + size.id + '">' + size.value + '</option>';
            });

            $.each(colors, function(cl, color) {
                colorOption += '<option value="' + color.id + '"style="background-color: '+color.value+'">' + color.value + '</option>';
            });

            $('#variantTable').on('click', '.removeRow', function() {
        
        $(this).closest('tr').remove();
    });

            $('.addRow').on('click', function() {
                let html = '<tr id="row_'+ counter +'"><td><select class="form-select " name="attribute['+ counter +'][size]" id="size_'+ counter +'" required=""><option value="">Select Option</option>'+ sizeOption +'</select></td><td><select class="form-select " name="attribute['+ counter +'][color]" id="color_'+ counter +'" required=""><option value="">Select Option</option>'+ colorOption +'</select></td><td><input class="form-control" type="number" name="attribute['+ counter +'][price]" required></td><td><input class="form-control" type="number" name="attribute['+ counter +'][quantity]" required></td><td><input class="form-control" type="file" name="attribute['+ counter +'][image][]" multiple></td><td><button type="button" class="btn btn-danger removeRow"><i class="fa fa-minus"></i></button></td></tr>';
                $('#variantTable tbody').append(html);
                counter++;
            })
        })
        
          $('#variantTable').on('click', '.removeRow', function() {
        let row = $(this).closest('tr');
        let deletedId = row.find("input[name^='attribute'][name$='[id]']").val();

        if (deletedId) {
           
            $('<input>').attr({
                type: 'hidden',
                name: 'deleted_attributes[]',
                value: deletedId
            }).appendTo('#variantTable');
        }

        row.remove(); 
    });
    </script>
@endpush
