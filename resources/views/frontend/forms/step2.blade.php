<div id="step-2" class="">
    <div class="step-bdy">
        <div class="title-prt2">
            <!--<h2>STEP 2</h2>-->
            <p class="text-uppercase">Select the placement of you logo.</p>
            <div class=" mt-4">
                <p> 1 print included per garment, {{ App\Helpers\Helper::formatCurrency(3) }} charge for each additional print.</p>
            </div>
        </div>

        @foreach ($subcategories as $sub => $subcategory)
            <h3>{{ Str::ucfirst($subcategory->name) }}</h3>

            <div class="position-prt mt-4">

                @foreach ($subcategory->productposition as $pos => $position)
                    {{-- <h3>{{ Str::ucfirst($position->type) }}</h3> --}}
                    <div class="position-filter">
                        <h4>{{ $position->title }}</h4>
                        <ul>

                            @foreach ($position->images as $img => $image)
                                <li>
                                    <div class="pro-img">
                                        <img class="img-fluid" src="{{ url('assets/frontend/' . $image->image) }}"
                                            alt="" />
                                    </div>

                                    <input class="image-checkbox" data-type="{{ $subcategory->slug }}"
                                        data-image="{{ url('assets/frontend/' . $image->image) }}" type="checkbox"
                                        id="{{ $position->type ?? '' }}[{{ $image->location ?? '' }}][{{ $image->id ?? '' }}]"
                                        name="positions[{{ $position->type ?? '' }}][{{ $image->location ?? '' }}][]"
                                        value="{{ $image->id ?? '' }}">

                                    <label
                                        for="{{ $position->subcategory->slug ?? '' }}[{{ $image->location ?? '' }}]"></label>
                                </li>
                            @endforeach
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
        $(document).ready(function() {
            let imageObject = {};

            $(".image-checkbox").on("change", function() {
                let imageUrl = $(this).data("image");
                let name = $(this).data("type");

                console.log('name ', name)

                let positionCountId = `#position_count_${name}`; // Adjust ID to match hidden input

                // Increment or decrement position count
                if ($(this).is(":checked")) {
                    if (!imageObject[name]) {
                        imageObject[name] = [];
                    }
                    if (!imageObject[name].includes(imageUrl)) {
                        imageObject[name].push(imageUrl);

                        // Update count
                        let currentCount = parseInt($(positionCountId).val()) || 0;
                        $(positionCountId).val(currentCount + 1);

                        console.log('count', currentCount);
                    }
                } else {
                    if (imageObject[name]) {
                        imageObject[name] = imageObject[name].filter((url) => url !== imageUrl);
                        if (imageObject[name].length === 0) {
                            delete imageObject[name];
                        }

                        // Decrement count
                        let currentCount = parseInt($(positionCountId).val()) || 0;
                        $(positionCountId).val(currentCount - 1);

                        console.log('count', currentCount);
                    }
                }


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


                // console.log("Image Object:", positionCountId);

                // Update the display
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
            });
        });
    </script>
@endpush
