<div class="col-12 col-lg-12">
    <label for="name" class="col-form-label">Bundle Name<span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Bundle Name"
        name="name" value="{{ $bundle?->name ?? ''}}" required>

    @error('name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div>

<div class="col-12 col-lg-12 mt-2">
    <table class="table table-sm" id="bundleTable">
        <thead>
            <th>Product</th>
            <th>Quantity</th>
            <th></th>
        </thead>
        <tbody>
            @if(!empty($bundle))
                @foreach ($bundle?->products as $i => $item)
                    <tr id="row-{{ $i }}">
                        <td>
                            <select name="products[{{ $i }}][product_id]" class="form-select  @error('product_id') is-invalid @enderror " required>
                                <option value="">--Select--</option>
                                @foreach ($products as $j => $product)
                                    <option value="{{ $product->id }}" @if ($product?->id == $item?->pivot?->product_id) selected @endif>{{ $product->name }}</option>
                                @endforeach

                            </select>
                        </td>
                        <td>
                            <input type="number" required class="form-control" min="1" name="products[{{ $i }}][quantity]" value="{{ $item?->pivot?->quantity }}">
                        </td>
                        <td>
                            @if($i == 0)
                                <button type="button" class="btn btn-xs btn-success" id="addRow"><i class="fa fa-plus"></i></button></td>
                            @else
                                <button type="button" class="btn btn-xs btn-danger" onclick="removeRow({{ $i }})"><i class="fa fa-trash"></i></button></td>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr id="row-0">
                    <td>
                        <select name="products[0][product_id]" class="form-select  @error('product_id') is-invalid @enderror " required>
                            <option value="">--Select--</option>
                            @foreach ($products as $j => $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>

                            @endforeach

                        </select>
                    </td>
                    <td>
                        <input type="number" class="form-control" name="products[0][quantity]" min="1" required>
                    </td>
                    <td>
                        <button type="button" class="btn btn-xs btn-success" id="addRow"><i class="fa fa-plus"></i></button></td>
                    </td>
                </tr>
            @endif

        </tbody>
    </table>

</div>

<div class="col-12 col-lg-12">
    <label for="name" class="col-form-label">Bundle Price<span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('price') is-invalid @enderror " placeholder="Bundle Price"
        name="price" value="{{ $bundle?->price ?? '' }}" required oninput="this.value = this.value.replace(/(?!^-?\d*\.?\d*$)[^\d.]/g, '').replace(/(\..*)\./g, '$1');">

    @error('name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div>

<div class="row">
    <div class="col-8 col-lg-8">
        <label for="image" class="col-form-label">Image<span class="text-danger">*</span> <small>(Image size must be 350px x 270px)
        </small></label>
        <input type="file" class="form-control @error('image') is-invalid @enderror " name="image">

        @error('image')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

    </div>
    @if(!empty($bundle))
        @if(is_file(public_path('storage/' . $bundle->image)))
            <div class="col-4 col-lg-4">

                <br>
                <img src="{{ url('storage/'.  $bundle->image) }}" alt="{{ $bundle->image }}" width="50" height="50">
            </div>
        @endif
    @endif
</div>



<div class="col-12 col-lg-12">
    <label for="status" class="col-form-label">Status<span class="text-danger">*</span></label>
    <select name="status" class="form-select @error('status') is-invalid @enderror ">
        <option value="" disabled>--Select--</option>
        <option value="1" @if(isset($bundle) && $bundle?->status == 1) selected @endif> Active </option>
        <option value="0" @if(isset($bundle) && $bundle?->status == 0) selected @endif> Inactive </option>


    </select>



</div>

<div class="col-12 col-lg-12 my-2">
    <a href="{{ route('admin.products-bundle.index') }}" class="btn btn-secondary waves-effect waves-light">Back</a>
    <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
</div>
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Choose Product",
                allowClear: true
            });

            $('#addRow').click(function() {


                    counter          = $('#bundleTable').length ;
                    let lastTrId     =  $('#bundleTable tr:last').attr('id') ;
                    lastTrId         = lastTrId.substring(4) ;
                    counter          = parseInt(lastTrId) + 1
                    var optionsHtml  = $('#bundleTable tbody>tr:first select').html();

                     console.log('options', optionsHtml)

                    html = '<tr id="row-' + counter + '">' +
                        '<td><select name="products['+ counter +'][product_id]" class="form-select" required=""><option value="">--Select--</option>'+
                            optionsHtml +'</select></td><td><input type="number" class="form-control" name="products['+ counter +'][quantity]" min="1" required=""></td>' + '<td><button type="button"' +
                        'class="btn btn-danger btn-xs" onclick="removeRow(' + counter +
                        ')"><i class="fa fa-trash"></i></button></td>'

                        '</tr>';

                    $('#bundleTable tbody').append(html);
                    $('#bundleTable tr:last select').prop('selectedIndex', 0);

            });


        });

        function removeRow(row) {
            $(`#row-${row}`).remove();
        }

        // function checkDuplicateRow(e)
        // {
        //     let trLength = $('#bundleTable tbody tr').length ;
        //     let duplicateFlag = false ;

        //     if(trLength > 0){

        //     outerLoop:
        //     for(let i = 0; i< trLength-1 ; i++){
        //         for(let j = i+1; j< trLength; j++){

        //                 let tr       =  $("#bundleTable tbody tr")[i];
        //                 let trNext   =  $("#bundleTable tbody tr")[j];
        //                 let trId             = $(tr).attr('id');
        //                 let trNextId         = $(trNext).attr('id');
        //                 let firstCount       = trId.substring(4);
        //                 let secondCount      = trNextId.substring(4);
        //                 alert(firstCount);
        //                 alert(secondCount);

        //                 if($('#product'+firstCount).val() == $('#product' + secondCount).val()){

        //                     duplicateFlag = true ;
        //                     break outerLoop;  // Exits both loops
        //                 }
        //             }

        //         }

        //     }

        //     return duplicateFlag ;
        // }


    </script>
@endpush
