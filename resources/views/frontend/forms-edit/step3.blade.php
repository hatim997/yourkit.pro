<div id="step-3" class="">
    <div class="step-bdy">
        <div class="title-prt2">
            <h2>STEP 3</h2>
            <p class="text-uppercase"> Placement Overview</p>
        </div>

        <div class="row mt-5 " id="displayImg">

        </div>

        <div class="row mt-4 justify-content-center">

            <div class="col-md-12">
                <div class="form-check">
                    <input class="form-check-input CheckDoc" type="checkbox" id="add_number" name="is_phone_checked">
                    <label class="form-check-label" for="flexCheckDefault">
                        ADD THE PHONE NUMBER ON THE BACK - {{ Helper::setting('currency-symbol') ?? '$' }}2 PER GARMENT
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input CheckDoc" type="checkbox" id="add_email" name="is_email_checked">
                    <label class="form-check-label" for="flexCheckDefault">
                        ADD THE EMAIL ON THE BACK - {{ Helper::setting('currency-symbol') ?? '$' }}2 PER GARMENT
                    </label>
                </div>
                <div class="form-group">
                    <label class="form-check-label">SPECIAL INDICATION(S)</label>
                    <textarea class="form-control" cols="1" name="comment" id="specialTxt"></textarea>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class=" mt-4">
        <p>{{ Helper::setting('currency-symbol') ?? '$' }} 2, will be charge for adding both email and mobile no</p>
    </div> --}}
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            let html = '';

            $(".CheckDoc").change(function() {
                if (this.checked) {
                    $('#specialTxt').attr('required', true);
                } else {
                    $('#specialTxt').removeAttr('required');
                }
            });

        })
    </script>
@endpush
