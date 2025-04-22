@extends('frontend.layouts')
{{-- @dd($bundles) --}}
@section('content')
    <section class="banner">
        <div class="banner-slider owl-carousel">

            @empty(!$banners)
                @foreach ($banners as $key => $banner)
                 @if($banner->type== 'top')
                    <div class="item" data-dot="<button> 0{{ $key + 1 }} </button>">
                        <div class="banner-inner-section">
                            <img src="{{ url(asset('storage/' . $banner->media->path)) }}" alt="" />
                            <div class="banner-txt">
                                <h2>{{ $banner->title }}</h2>
                                <h4>{{ $banner->description }}</h4>
                                <br/>
                                <a href="{{ route('frontend.product') }}" class="btn btn-outline-warning mt-4">Get started today <i
                                        class="fa-solid fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            @endempty

        </div>
    </section>


    <!-- Products -->

    @include('frontend.components.products')


   <section class="main-wrap">

        <div class="row mt-5">
            @php
                $bnr=App\Models\Banner::find(8);
            @endphp
            <div class="col-md-8" data-aos="fade-right" data-aos-duration="2000">
                <div class="offer-bx" onclick="window.location.href='{{$bnr->url}}'" style="cursor:pointer">
                    <img class="img-fluid" src="{{ url(asset('storage/' . $bnr->media->path)) }}" alt="" />
                    <div class="offer-txt">
                        {{-- <h4>Upto 20% Off</h4>
                        <h3>Make The Most Of <br />Our <strong>Kangaroos Printing</strong></h3> --}}
                        {{-- <a href="{{$bnr->url}}" class="btn btn-outline-warning mt-3">{{$bnr->url_name}} <i
                                class="fa-solid fa-arrow-right"></i></a> --}}
                    </div>
                </div>
            </div>
            @php
            $bnr2=App\Models\Banner::find(9);
        @endphp
            <div class="col-md-4" data-aos="fade-left" data-aos-duration="2500">
                <div class="offer-bx custom-chng" onclick="window.location.href='{{$bnr2->url}}'" style="cursor:pointer">
                    <img class="img-fluid" src="{{ url(asset('storage/' . $bnr2->media->path)) }}" alt="" />
                    <div class="offer-txt">
                        <!--<h4>Upto 15% Off</h4>-->
                        <!--<h3>On All T-Shirt Printing</h3>-->
                        {{-- <a href="{{$bnr2->url}}" class="btn btn-outline-warning mt-3">{{$bnr2->url_name}} <i
                                class="fa-solid fa-arrow-right"></i></a> --}}
                    </div>
                </div>
            </div>
        </div>

    </section>

    <!-- Deals of the Day -->

    @include('frontend.components.deals')


   <section class="main-wrap">
        <div class="container-xxl">
            <div class="row">
                @php
                $bnr3=App\Models\Banner::find(10);
            @endphp
                <div class="col-md-6" data-aos="zoom-in" data-aos-duration="3000">
                    <div class="category-bx" onclick="window.location.href='{{$bnr3->url}}'" style="cursor:pointer">
                        <img class="img-fluid" src="{{ url(asset('storage/' . $bnr3->media->path)) }}" alt="" />
                        <div class="inr-txt">
                            {{-- <h3>T-Shirt Printing Made Easy.</h3>
                            <p>You've got the ideas, we've got the tools</p> --}}
                            {{-- <a href="{{$bnr3->url}}" class="btn btn-outline-warning mt-3">{{$bnr3->url_name}}<i
                                    class="fa-solid fa-arrow-right"></i></a> --}}
                        </div>
                    </div>
                </div>

                @php
                $bnr4=App\Models\Banner::find(11);
            @endphp
                <div class="col-md-6" data-aos="zoom-in" data-aos-duration="3000">
                    <div class="category-bx" onclick="window.location.href='{{$bnr4->url}}'" style="cursor:pointer">
                      <img class="img-fluid" src="{{ url(asset('storage/' . $bnr4->media->path)) }}" alt="" />
                      <div class="inr-txt">
                        {{-- <h3>One Place For All Your Needs</h3>
                        <ul>
                          <li>T-Shirts</li>
                          <li>Caps</li>
                          <li>Kangaroos</li>
                          <li>ML T-Shirts</li>
                          <li>Hats</li>
                        </ul> --}}
                        {{-- <a href="{{$bnr4->url}}" class="btn btn-outline-warning mt-3">{{$bnr4->url_name}} <i class="fa-solid fa-arrow-right"></i></a> --}}
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


