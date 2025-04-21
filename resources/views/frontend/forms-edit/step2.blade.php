<div id="step-2" class="">
    <div class="step-bdy">
        <div class="title-prt2">
            <h2>STEP 2</h2>
            <p class="text-uppercase">Select the placement of you logo.</p>
            <div class=" mt-4">
                <p> 1 print included per garment, {{ \App\Helpers\Helper::formatCurrency(3) }} charge for each additional print.</p>
            </div>
        </div>
        @foreach ($subcategories as $sub => $subcategory)
        <h3>{{ Str::ucfirst($subcategory->name) }}</h3>
        <div class="position-prt mt-4">
            @foreach ($subcategory->productposition as $pos => $position)
                <div class="position-filter">
                    <h4>{{ $position->title }}</h4>
                    <ul>

                        @php
                            $arr = [];
                            $cartPositions = [];
                            $positionArray = [];


                        @endphp

                        @foreach ($position->images as $img => $image)
                            @php

                            // dd($image);

                                // if (isset($cartPositions[$img][$image->location])) {
                                //     $pos = $cartPositions[$img][$image->location];

                                //     $poscos[] = $pos;


                                //     foreach ($pos as $key => $value) {
                                //         $positionArray[] = $value;
                                //     }
                                // }

                                if (isset($cart)) {
                                foreach ($cart->contents as $content) {
                                    $cartPositions = json_decode($content->positions, true);

                                    if(isset($cartPositions)){
                                        foreach ($cartPositions as $keyPos => $valuePos) {

                                        foreach ($valuePos as $value) {
                                            $positionArray[] = $value;
                                        }
                                    }
                                    }
                                }

                                // dd($positionArray);
                            }


                            @endphp

                            <li>
                                <div class="pro-img">
                                    <img class="img-fluid" src="{{ url('assets/frontend/' . $image->image) }}"
                                        alt="" />
                                </div>
                                {{-- <input type="radio" onclick="changeImage('{{ url('assets/frontend/'.$image->image) }}', '{{ $image->location }}')" id="{{ $position->type }}[{{ $image->location }}]" name="positions[{{ $position->type }}][{{ $image->location }}]" value="{{ $image->id }}"> --}}

                                <input class="image-checkbox" data-image="{{ url('assets/frontend/' . $image->image) }}" data-type="{{ $subcategory->slug }}"
                                    type="checkbox"
                                    id="{{ $position->type ?? '' }}[{{ $image->location ?? '' }}][{{ $image->id ?? '' }}]"
                                    name="positions[{{ $position->type ?? '' }}][{{ $image->location ?? '' }}][]"
                                    value="{{ $image->id ?? '' }}" {{ !empty($positionArray) && in_array($image->id, $positionArray) ? 'checked' : '' }}>


                                <label for="{{ $position->type ?? '' }}[{{ $image->location ?? '' }}]"></label>
                            </li>
                        @endforeach

                        {{-- @php
                            dd($poscos);
                        @endphp --}}

                    </ul>
                </div>

            @endforeach
            <input type="hidden" name="position_count[{{ $subcategory->id }}]" value="0"
                id="position_count_{{ $subcategory->slug }}">
        </div>
            @endforeach

    </div>
</div>

@push('scripts')
    <script>
        // $(document).ready(function() {
        //     let imageObject = {};

        //     $(".image-checkbox").on("change", function() {
        //         let imageUrl = $(this).data("image");
        //         let name = $(this).data("type");

        //         console.log('name ', name)

        //         let positionCountId = `#position_count_${name}`; // Adjust ID to match hidden input

        //         // Increment or decrement position count
        //         if ($(this).is(":checked")) {
        //             if (!imageObject[name]) {
        //                 imageObject[name] = [];
        //             }
        //             if (!imageObject[name].includes(imageUrl)) {
        //                 imageObject[name].push(imageUrl);

        //                 // Update count
        //                 let currentCount = parseInt($(positionCountId).val()) || 0;
        //                 $(positionCountId).val(currentCount + 1);

        //                 console.log('count', currentCount);
        //             }
        //         } else {
        //             if (imageObject[name]) {
        //                 imageObject[name] = imageObject[name].filter((url) => url !== imageUrl);
        //                 if (imageObject[name].length === 0) {
        //                     delete imageObject[name];
        //                 }

        //                 // Decrement count
        //                 let currentCount = parseInt($(positionCountId).val()) || 0;
        //                 $(positionCountId).val(currentCount - 1);

        //                 console.log('count', currentCount);
        //             }
        //         }

        //         // console.log("Image Object:", positionCountId);

        //         // Update the display
        //         $("#displayImg").empty();
        //         $.each(imageObject, function(name, images) {
        //             let nameHtml = `<h4>${name}</h4>`;
        //             $("#displayImg").append(nameHtml);
        //             $.each(images, function(key, image) {
        //                 let html =
        //                     '<div class="col-md-2"><div class="pro-img"><img class="img-fluid" src="' +
        //                     image +
        //                     '" alt=""></div></div>';
        //                 $("#displayImg").append(html);
        //             });
        //         });
        //     });
        // });

        $(document).ready(function() {
    let imageObject = {};

    function updateDisplay() {
        $("#displayImg").empty();
        $.each(imageObject, function(name, images) {
            let nameHtml = `<h4>${name}</h4>`;
            $("#displayImg").append(nameHtml);
            $.each(images, function(key, image) {
                let html =
                    '<div class="col-md-2"><div class="pro-img"><img class="img-fluid" src="' +
                    image +
                    '" alt=""></div></div>';
                $("#displayImg").append(html);
            });
        });
    }

    function updatePositionCount() {
        $(".image-checkbox:checked").each(function() {
            let name = $(this).data("type");
            let positionCountId = `#position_count_${name}`;
            let currentCount = parseInt($(positionCountId).val()) || 0;

            // Increase count only if not already considered
            if (!imageObject[name]) {
                imageObject[name] = [];
            }

            let imageUrl = $(this).data("image");
            if (!imageObject[name].includes(imageUrl)) {
                imageObject[name].push(imageUrl);
                $(positionCountId).val(currentCount + 1);
            }
        });

        updateDisplay();
    }

    $(".image-checkbox").on("change", function() {
        let imageUrl = $(this).data("image");
        let name = $(this).data("type");
        let positionCountId = `#position_count_${name}`;

        // Increment or decrement position count
        if ($(this).is(":checked")) {
            if (!imageObject[name]) {
                imageObject[name] = [];
            }
            if (!imageObject[name].includes(imageUrl)) {
                imageObject[name].push(imageUrl);

                let currentCount = parseInt($(positionCountId).val()) || 0;
                $(positionCountId).val(currentCount + 1);
            }
        } else {
            if (imageObject[name]) {
                imageObject[name] = imageObject[name].filter((url) => url !== imageUrl);
                if (imageObject[name].length === 0) {
                    delete imageObject[name];
                }

                let currentCount = parseInt($(positionCountId).val()) || 0;
                $(positionCountId).val(Math.max(0, currentCount - 1)); // Ensure count doesn't go negative
            }
        }

        let countCheckedCheckboxes = $(".image-checkbox:checked").length;

        if(countCheckedCheckboxes == 2){
            msgpopupflag = 1;
            Swal.fire({
                icon: "info",
                text: `It will be extra charged for each garment`
            });
        }

        updateDisplay();
    });

    // Initialize counts for pre-checked checkboxes on page load
    updatePositionCount();
});

    </script>
    <script>
        $(document).ready(function() {
            let imageArray = [];

            function showFine(imageArray) {
                console.log('fine', imageArray);

                // Clear the container before updating
                let html = '';
                if (imageArray.length > 0) {
                    $.each(imageArray, function(key, image) {
                        html +=
                            '<div class="col-md-4"><div class="pro-img"><img class="img-fluid" src="' +
                            image + '" alt=""></div></div>';
                    });
                }

                // Update the container with the complete HTML
                $('#displayImg').html(html);
            }

            $(".image-checkbox").each(function(index, element) {
                let imageUrl = $(this).data("image");

                if ($(this).is(":checked")) {
                    if (!imageArray.includes(imageUrl)) {
                        imageArray.push(imageUrl);
                    }

                    console.log('initial ', imageArray);
                    showFine(imageArray);
                }
            });

            $(".image-checkbox").on("change", function() {
                let imageUrl = $(this).data("image");
                if ($(this).is(":checked")) {
                    if (!imageArray.includes(imageUrl)) {
                        imageArray.push(imageUrl);
                    }
                } else {
                    imageArray = imageArray.filter((url) => url !== imageUrl);
                }

                console.log('img-array ', imageArray);
                showFine(imageArray);
            });
        });
    </script>

        <script>
$(document).on('change', '.image-checkbox', function() {

    let subcategorySlug = $(this).data('type');
   //alert(subcategorySlug)
    let countCheckedCheckboxes = $(
       `.image-checkbox[data-type="${subcategorySlug}"]:checked`).length;

   if (countCheckedCheckboxes === 2) {
       Swal.fire({
           icon: "info",
           text: `It will be extra charged for each garment`
       });
    }
});
</script>
@endpush
