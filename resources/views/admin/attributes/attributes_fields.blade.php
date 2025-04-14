<table class="table table-bordered" id="dynamicTable">
    <tr>
        <th>Title @if($attribute->type=='Color')(Incase of color attribute, # code of particular color need to enter for proper functionality e.g #f40b0b)@endif</th>
        <th>Action</th>
    </tr>

    <input type="hidden" id="numRows" value="0">
</table>

@push('scripts')
    <script>
        var i = parseInt($('#numRows').val());
        let details = JSON.parse(`<?php echo isset($attribute->attributeValues) ? json_encode($attribute->attributeValues) : json_encode([]); ?>`);
        console.log('new console', details);


        /**** Calls when add button clicks **/
        $(document).on('click', '#add', function() {

            let html = '<tr><td><input type="text" name="details[]" placeholder="Enter Title" class="form-control" required/></td><td><button type="button" class="btn btn-danger remove-tr"><span><i class="fa fa-minus"></i></span></button></td></tr>';
            $("#dynamicTable").append(html);
            $('#numRows').val(i)

            i++;

        })

        /**** Default Calling on Page Load **/
        $(document).ready(function() {


            let html = '';
            if (details == 0) {
                html = '<tr><td><input type="text" name="details[]" placeholder="Enter Title" class="form-control" required/></td><td><button type="button" name="add" id="add" class="btn btn-success"><span><i class="fa fa-plus"></i></span></button></td></tr>';
                $('#numRows').val(i);
                i++;

            } else {

                console.log('stage ', i);

                var btn = '';

                details?.forEach((data, index) => {

                    if (i === 0) {
                        btn =
                            '<button type="button" name="add" id="add" class="btn btn-success"><span><i class="fa fa-plus"></i></span></button>';
                    } else {
                        btn ='';
                           // '<button type="button" class="btn btn-danger remove-tr"><span><i class="fa fa-minus"></i></span></button>';
                    }

                    html += '<tr><td><input type="'+ details.input_type +'" name="details[]" placeholder="Enter Title" class="form-control" value="' + (data.value ??
                            '') +
                        '" required/></td><td>'+ btn +'</td></tr>';

                    i++;
                })

                $('#numRows').val(details.length);
            }

            $("#dynamicTable").append(html);
        })

        $(document).on('click', '.remove-tr', function() {
            $(this).parents('tr').remove();
        });
    </script>
@endpush
