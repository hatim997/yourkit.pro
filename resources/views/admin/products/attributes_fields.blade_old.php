<button class="btn btn-sm btn-primary" type="button" id="addBtn">New Row </button>

<table class="table table-stripped" id="variantTable">

</table>

@push('scripts')
    <script>
        $(document).ready(function() {

            var heads;
            var data;
            var counter = 0;

            $('#arrtibutesId').on('change', function() {
                let values = $(this).val(); // Get selected values (likely an array)

                console.log('values', values);

                $.ajax({
                    url: "{{ route('admin.attribute.value') }}",
                    method: 'GET',
                    data: {
                        values: values
                    },
                    cache: false,
                    success: function(response) {
                        console.log('server-response', response);

                        let html = '<thead class="">';
                        let row = '';

                        heads = response.heads;
                        data = response.data;

                        $.each(heads, function(key, element) {
                            html += '<th>' + element + '</th>';
                        });

                        html += '</thead>'; // closing table header

                        $.each(data, function(key, elements) {
                            let td = '<td>';

                            let select =
                                '<select class="form-select" name="'+ heads[key].toLowerCase() +'['+ counter +'][value]" id="attribute_value1' + counter + '" required>';
                            let option = '<option value="">Select Option</option>';
                            let attribute = '<input type="hidden" name="'+ heads[key].toLowerCase() +'['+ counter +'][attribute_id]" id="attr_id' + counter +
                                '" value="' + elements[key].attribute_id + '">';

                            $.each(elements, function(k, item) {
                                option += '<option value="' + item.id + '">' +
                                    item.value + '</option>';
                            });

                            select = select + option + '</select>';
                            td += select + attribute + '</td>';
                            row += td;

                            row +='<td><input class="form-contol" type="file" name="'+ heads[key].toLowerCase() +'['+ counter +'][image]"><td>';

                        })


                        html += '<tr id="row-'+ counter +'">' + row + '</tr>';

                        $('#variantTable').html(html);

                    },
                    error: function(error) {
                        console.warn(error);
                    }
                });
            });

            $('#addBtn').on('click', function() {
                if ($('#arrtibutesId').val() != "") {

                    counter++

                    let rowLength = $('#variantTable tbody tr').length;
                    let html;
                    let row = '';
                    console.log('rowL ', rowLength)

                    if (rowLength > 0) {

                        $.each(data, function(key, elements) {
                            let td = '<td>';

                            let select =
                                '<select class="form-select" name="'+ heads[key].toLowerCase() +'['+ counter +'][value]" id="attribute_value1' + counter + '" required>';
                            let option = '<option value="">Select Option</option>';
                            let attribute = '<input type="hidden" name="'+ heads[key].toLowerCase() +'['+ counter +'][attribute_id]" id="attr_id' + counter +
                                '" value="' + elements[key].attribute_id + '">';

                            $.each(elements, function(k, item) {
                                option += '<option value="' + item.id + '">' +
                                    item.value + '</option>';
                            });

                            select = select + option + '</select>';
                            td += select + attribute + '</td>';
                            row += td;

                        })
                        row += '<td><button class="btn btn-danger"><i class="fa fa-trash"></i></button></td>'
                        html += '<tr id="row-'+ counter +'">' + row + '</tr>';
                        $('#variantTable tbody').append(html);

                    }

                } else {
                    alert("Select a attribute first");
                }


            });

        });
    </script>
@endpush
