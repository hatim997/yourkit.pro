{!! $html !!}

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.multiple').select2();
        });
    </script>
    <script>

        function removeRow(row) {
            console.log(row)
            $(`#row-color_${row}`).remove();
        }

        $(document).ready(function() {

            const details = JSON.parse(`<?php echo isset($attributes) ? $attributes : json_encode([]); ?>`);
            var counter = 0;
            var option = '';

            $.each(details[1].arrtibutesvalue, function(key, element) {
                option += '<option value="' + element.id + '">' + element.value + '</option>';
            });

            $('.addRow').click(function() {

                console.log('attribute value -> ', details)
                var optionsHtml = $('#variantTable_color tr:first select').html();
               counter          = $('#variantTable_color tr').length ;
               let lastTrId     =  $('#variantTable_color tr:last').attr('id') ;
               lastTrId         = lastTrId.substring(10) ;
               counter          = parseInt(lastTrId) + 1

               // console.log('options', option)

                html = '<tr id="row-color_' + counter + '">' +
                    '<td>' +
                    '<select class="form-select" name="color[' + counter +
                    '][value]" id="color'+ counter +'" required="">' +
                    optionsHtml +
                    '</select>' +
                    '</td><td><input class="form-control" type="file" name="color[' + counter +
                    '][image]"></td>' +
                    '<td><button type="button" data-row="' + counter +
                    '" data-table="color" class="btn btn-danger" onclick="removeRow(' + counter +
                    ')"><i class="fa fa-minus"></i></button></td>' +
                    '</tr>';

                console.log(html);

                $('#variantTable_color tbody').append(html);
                $('#variantTable_color tr:last select').prop('selectedIndex', 0);
                counter ++;
            });

            $('#myForm').on('submit', function(e){
                if(checkDuplicateRow(e)){
                    alert("Duplicate Row Found. Kindly Check");
                    e.preventDefault() ;
                }


            })
        });


        function checkDuplicateRow(e)
        {
            let trLength = $('#variantTable_color tbody tr').length ;
            let duplicateFlag = false ;

            if(trLength > 0){

            outerLoop:
            for(let i = 0; i< trLength-1 ; i++){
                for(let j = i+1; j< trLength; j++){

                        let tr       =  $("#variantTable_color tbody tr")[i];
                        let trNext   = $("#variantTable_color tbody tr")[j];
                        let trId             = $(tr).attr('id');
                        let trNextId         = $(trNext).attr('id');
                        let firstCount       = trId.substring(10);
                        let secondCount      = trNextId.substring(10);

                        if($('#color'+firstCount).val() == $('#color' + secondCount).val()){
                            duplicateFlag = true ;
                            break outerLoop;  // Exits both loops
                        }
                    }

                }

            }

            return duplicateFlag ;
        }
    </script>
@endpush
