@extends('frontend.layouts')

@section('title', 'Contact Us')

@section('content')

    <section class="main-wrap">
        <div class="container-xxl">

            <div class="row">
                <div class="col-md-7">
                    <h2 class="contact-title" data-aos="fade-up" data-aos-duration="2000"><span>Send Us A</span> Message</h2>
                    <form class="rd-mailform" method="POST" action="{{ route('frontend.contact.post') }}" id="contactForm">
                        @csrf
                        <div class="row">
                            {{-- Name --}}
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Your name</label>
                                    <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name') }}"/>
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            {{-- Phone --}}
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Your phone</label>
                                    <input class="form-control @error('phone') is-invalid @enderror" type="text" name="phone" value="{{ old('phone') }}"/>
                                    @error('phone')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Your e-mail</label>
                                    <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}"/>
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            {{-- Message --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Message</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" name="message">{{ old('message') }}</textarea>
                                    @error('message')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12 my-8">
                                @if(config('captcha.version') === 'v3')
                                    {!! \App\Helpers\Helper::renderRecaptcha('contactForm', 'register') !!}
                                @elseif(config('captcha.version') === 'v2')
                                    <div class="form-field-block">
                                        {!! app('captcha')->display() !!}
                                        @if ($errors->has('g-recaptcha-response'))
                                            <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <button class="btn btn-outline-warning mt-3" type="submit">
                            <span>Send message</span> <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </form>

                </div>
                <div class="col-md-5">
                    <div class="map-box">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2792.6163285784414!2d-73.64086492371977!3d45.57815357107608!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4cc91f392125865f%3A0xc127d6a0ec5d9144!2s9955%20Av.%20Lausanne%2C%20Montr%C3%A9al%2C%20QC%20H1H%205A6%2C%20Canada!5e0!3m2!1sen!2sin!4v1741354199775!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script')
    {!! NoCaptcha::renderJs() !!}
@endsection
