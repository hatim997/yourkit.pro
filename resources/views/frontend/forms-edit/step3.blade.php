<div id="step-3" class="">
    <div class="step-bdy">
        <div class="title-prt2">
            <h2>STEP 3</h2>
            <p class="text-uppercase"> Placement Overview</p>
        </div>

        <div class="row mt-5 " id="displayImg">

        </div>

        <div class="row mt-4 justify-content-center">
            @php
                $subcategories = json_decode($bundle->subcategories, true);
            @endphp
            <div class="col-md-12">
                <div class="form-check">
                    <input class="form-check-input CheckDoc" type="checkbox" id="add_number" name="is_phone_checked" {{ $cart->is_phone_checked == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="flexCheckDefault">
                        ADD THE PHONE NUMBER ON THE BACK - {{ \App\Helpers\Helper::formatCurrency(2) }} PER GARMENT
                    </label>
                </div>
                <div id="numberPlacementOptions" class="mt-3" style="display: none;">
                    <hr>
                    <p><strong>Please specify on which garment(s) you want to add the PHONE NUMBER:</strong></p>

                    @if (in_array(2, $subcategories))
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_phone_on_hoodie" {{ $cart->{'is_phone_on_hoodie'} == '1' ? 'checked' : '' }}>
                            <label class="form-check-label">Hoodie</label>
                        </div>
                    @endif

                    @if (in_array(1, $subcategories))
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_phone_on_t-shirt" {{ $cart->{'is_phone_on_t-shirt'} == '1' ? 'checked' : '' }}>
                            <label class="form-check-label">T-Shirt</label>
                        </div>
                    @endif
                    <hr>
                </div>

                <div class="form-check">
                    <input class="form-check-input CheckDoc" type="checkbox" id="add_email" name="is_email_checked" {{ $cart->is_email_checked == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="flexCheckDefault">
                        ADD THE EMAIL ON THE BACK - {{ \App\Helpers\Helper::formatCurrency(2) }} PER GARMENT
                    </label>
                </div>
                <div id="emailPlacementOptions" class="mt-3" style="display: none;">
                    <hr>
                    <p><strong>Please specify on which garment(s) you want to add the EMAIL:</strong></p>

                    @if (in_array(2, $subcategories))
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_email_on_hoodie" {{ $cart->{'is_email_on_hoodie'} == '1' ? 'checked' : '' }}>
                            <label class="form-check-label">Hoodie</label>
                        </div>
                    @endif

                    @if (in_array(1, $subcategories))
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_email_on_t-shirt" {{ $cart->{'is_email_on_t-shirt'} == '1' ? 'checked' : '' }}>
                            <label class="form-check-label">T-Shirt</label>
                        </div>
                    @endif
                    <hr>
                </div>
                <div class="form-group">
                    <label class="form-check-label">SPECIAL INDICATION(S)</label>
                    <textarea class="form-control" cols="1" name="comment" id="specialTxt">{{ $cart->comment }}</textarea>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class=" mt-4">
        <p>{{ \App\Helpers\Helper::formatCurrency(2) }} , will be charge for adding both email and mobile no</p>
    </div> --}}
</div>

@push('scripts')
    <script>
        // $(document).ready(function() {
        //     let html = '';

        //     $(".CheckDoc").change(function() {
        //         if (this.checked) {
        //             $('#specialTxt').attr('required', true);
        //         } else {
        //             $('#specialTxt').removeAttr('required');
        //         }
        //     });

        //     function togglePlacementOptions() {
        //         if ($('#add_number').is(':checked')) {
        //             $('#numberPlacementOptions').slideDown();
        //             $('#numberPlacementOptions input[type="checkbox"]').prop('disabled', false);
        //         } else {
        //             $('#numberPlacementOptions').slideUp();
        //             $('#numberPlacementOptions input[type="checkbox"]').prop('disabled', true);
        //         }

        //         if ($('#add_email').is(':checked')) {
        //             $('#emailPlacementOptions').slideDown();
        //             $('#emailPlacementOptions input[type="checkbox"]').prop('disabled', false);
        //         } else {
        //             $('#emailPlacementOptions').slideUp();
        //             $('#emailPlacementOptions input[type="checkbox"]').prop('disabled', true);
        //         }
        //     }


        //     $(".CheckDoc").change(function() {
        //         togglePlacementOptions();
        //     });

        //     // Initial check on load
        //     togglePlacementOptions();

        // })
        $(document).ready(function() {
            function togglePlacementOptions() {
                if ($('#add_number').is(':checked')) {
                    $('#numberPlacementOptions').slideDown();
                    $('#numberPlacementOptions input[type="checkbox"]').prop('disabled', false);
                } else {
                    $('#numberPlacementOptions').slideUp();
                    $('#numberPlacementOptions input[type="checkbox"]').prop('disabled', true);
                }

                if ($('#add_email').is(':checked')) {
                    $('#emailPlacementOptions').slideDown();
                    $('#emailPlacementOptions input[type="checkbox"]').prop('disabled', false);
                } else {
                    $('#emailPlacementOptions').slideUp();
                    $('#emailPlacementOptions input[type="checkbox"]').prop('disabled', true);
                }
            }

            function syncMainCheckboxWithSubs(mainCheckboxId, subCheckboxNames) {
                let isAnyChecked = false;

                subCheckboxNames.forEach(function(name) {
                    if ($(`input[name="${name}"]`).is(':checked')) {
                        isAnyChecked = true;
                    }
                });

                $(`#${mainCheckboxId}`).prop('checked', isAnyChecked).trigger('change');
            }

            // Toggle specialTxt required
            $(".CheckDoc").change(function() {
                if ($(this).is(':checked')) {
                    $('#specialTxt').attr('required', true);
                } else {
                    $('#specialTxt').removeAttr('required');
                }
                togglePlacementOptions();
            });

            // Listen for phone sub-checkboxes
            $('input[name="is_phone_on_hoodie"], input[name="is_phone_on_t-shirt"]').change(function() {
                syncMainCheckboxWithSubs('add_number', ['is_phone_on_hoodie', 'is_phone_on_t-shirt']);
            });

            // Listen for email sub-checkboxes
            $('input[name="is_email_on_hoodie"], input[name="is_email_on_t-shirt"]').change(function() {
                syncMainCheckboxWithSubs('add_email', ['is_email_on_hoodie', 'is_email_on_t-shirt']);
            });

            // Initial run
            togglePlacementOptions();
        });
    </script>
@endpush
