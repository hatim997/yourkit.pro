@extends('frontend.layouts')

@section('title', 'FAQ')

@section('content')

    <section class="main-wrap">
        <div class="container-xxl">
            <div class="row align-items-center flex-row-reverse">
                <div class="col-md-5">
                    <div class="abt-img ps-4">
                        <img src="{{ asset('assets/frontend/images/deal1.jpg') }}" alt="" class="img-fluid" />
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="title-prt">
                        <h2>Frequently Asked Questions?</h2>
                    </div>

                    <div class="accordion" id="accordionExample">

                        @empty(!$faqs)

                        @foreach ($faqs as $key => $faq)

                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $key }}" aria-expanded="true" aria-controls="collapse{{ $key }}">{{ $faq->title }}</button>
                            </h2>
                            <div id="collapse{{ $key }}" class="accordion-collapse collapse {{ ($key == 0) ? 'show' : '' }}"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">{!! $faq->description !!}</div>
                            </div>
                        </div>
                            
                        @endforeach
                            
                        @endempty

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
