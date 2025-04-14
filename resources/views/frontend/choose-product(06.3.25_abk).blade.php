@extends('frontend.layouts')

@section('title', 'Products')

@section('content')

    <section class="main-wrap">

        <div class="container mt-">
            <div class="progress px-1" style="height: 3px;">
                <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0"
                    aria-valuemax="100"></div>
            </div>
            <div class="step-container d-flex justify-content-between">
                <div class="step-circle" id="step-circle1" onclick="displayStep(1)">1</div>
                <div class="step-circle" id="step-circle2" onclick="displayStep(2)">2</div>
                <div class="step-circle" id="step-circle3" onclick="displayStep(3)">3</div>
            </div>

            <form id="multi-step-form">
                <div class="step step-1">
                    <!-- Step 1 form fields here -->
                    <h3>Step 1</h3>
                    <div class="mb-3">
                        @include('frontend.forms.step1')
                    </div>

                    <div class="stpBtn">
                        <a href="{{ route('product') }}" class="btn btn-outline-warning">Previous</a>
                        <button type="button" class="btn btn-outline-warning  next-step">Next</button>
                    </div>

                </div>

                <div class="step step-2">
                    <!-- Step 2 form fields here -->
                    <h3>Step 2</h3>
                    <div class="mb-3">
                        @include('frontend.forms.step2')
                    </div>
                    <div class="stpBtn">
                        <button type="button" class="btn btn-outline-warning  prev-step">Previous</button>
                        <button type="button" class="btn btn-outline-warning  next-step">Next</button>
                    </div>
                </div>

                <div class="step step-3">
                    <!-- Step 3 form fields here -->
                    <h3>Step 3</h3>
                    <div class="mb-3">
                        @include('frontend.forms.step3')
                    </div>
                    <div class="stpBtn">
                        <button type="button" class="btn btn-outline-warning  prev-step">Previous</button>
                        <input type="hidden" value="{{ $bundle->id }}" name="table_id">
                        <input type="hidden" value="bundles" name="table">
                        <input type="hidden" name="sessionId" id="sessionId" value="">
                        <button type="submit" class="btn btn btn-outline-warning">Submit</button>
                    </div>

                </div>
            </form>
        </div>

    </section>

@endsection

@push('styles')
    {{-- <link rel="stylesheet" href="{{ url(asset('assets/frontend/css/stepper.css')) }}"> --}}
@endpush

@push('scripts')
    <script src="{{ url(asset('assets/frontend/js/stepper.js')) }}"></script>

    <script>
        let sessionId = localStorage.getItem('sessionId') && JSON.parse(localStorage.getItem('sessionId')) || "";

        function generateRandomString(length = 16) {
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let result = '';
            for (let i = 0; i < length; i++) {
                const randomIndex = Math.floor(Math.random() * characters.length);
                result += characters.charAt(randomIndex);
            }
            return result;
        }

        $(document).ready(function() {

            if (!sessionId) {
                sessionId = generateRandomString();
            }

            $('#sessionId').val(sessionId);

            $('#multi-step-form').on('submit', function(e) {
                e.preventDefault();

                let url = '{{ route('product.store') }}';

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: $(this).serialize(),
                    success: function(response) {
                        try {
                            console.log('server response', response)

                            if(response.status){
                                localStorage.setItem('sessionId', JSON.stringify(response.data));
                                window.location.href = "{{ route('cart') }}";
                            }
                        } catch (e) {
                            console.error('Failed to store cart data in localStorage:', e);
                            alert('An error occurred while adding to the cart.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        alert('Failed to add to the cart. Please try again.');
                    }
                });
            });
        });
    </script>
@endpush
