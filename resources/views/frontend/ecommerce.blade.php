@extends('frontend.layouts')

@section('title', 'Products')

@section('content')

    <section class="main-wrap">
        <div class="container-xxl">
            <!-- <div class="top-title-prt"> -->
            <!-- <div class="style-txt-prt" data-aos="fade-left" data-aos-duration="3000">
              <h2>What's your style?</h2>
              <p>1 color logo included both sided on clothing 5000pts embroidery on hat included</p>
            </div> -->
            <!-- <ul class="filter-lst">
              <li class="active" data-aos="zoom-in" data-aos-duration="2000">
                <div class="filter-ico">
                  <img src="images/ico1.png" alt="" />
                  <span><img src="images/ico1-h.png" alt="" /></span>
                </div>
                <h4>T-Shirts</h4>
              </li>
              <li data-aos="zoom-in" data-aos-duration="2300">
                <div class="filter-ico">
                  <img src="images/ico2.png" alt="" />
                  <span><img src="images/ico2-h.png" alt="" /></span>
                </div>
                <h4>ML T-Shirts</h4>
              </li>
              <li data-aos="zoom-in" data-aos-duration="2600">
                <div class="filter-ico">
                  <img src="images/ico3.png" alt="" />
                  <span><img src="images/ico3-h.png" alt="" /></span>
                </div>
                <h4>Kangaroos</h4>
              </li>
              <li data-aos="zoom-in" data-aos-duration="2900">
                <div class="filter-ico">
                  <img src="images/ico4.png" alt="" />
                  <span><img src="images/ico4-h.png" alt="" /></span>
                </div>
                <h4>Caps</h4>
              </li>
              <li data-aos="zoom-in" data-aos-duration="3000">
                <div class="filter-ico">
                  <img src="images/ico5.png" alt="" />
                  <span><img src="images/ico5-h.png" alt="" /></span>
                </div>
                <h4>Hats</h4>
              </li>
            </ul> -->
            <!-- </div> -->
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                    <div class="prod-left" data-aos="fade-right" data-aos-duration="1200">
                        <h2>ARTICLES</h2>
                        <ul class="prod-left-filter">
                          @foreach($subcategories as $cat)
                          <li><a href="{{route('ecommerce.category',$cat->slug)}}">{{$cat->name}}</a></li>
                          @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12">
                    <div class="prod-right">
                        <div class="row customize-bx">

                            @foreach ($products as $product)

                            <div class="col-md-4">
                                <div class="pro-bx" data-aos="flip-left" data-aos-duration="1000">
                                    <div class="pro-img">
                                      
                                        <img class="img-fluid" src="{{ url('storage/'. $product->ecommerce()->first()->images()->first()->image) }}" alt="" />
                                    </div>
                                    <div class="prod-text">
                                        <h5>{{ $product->name }}</h5>
                                        <p>${{ $product->ecommerce()->first()->price }}</p>
                                    </div>
                                    <div class="select-overlay">
                                        <a href="{{ route('ecom.details', $product->productId) }}" class="btn btn-outline-warning">View More</a>
                                    </div>
                                </div>
                            </div>

                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
