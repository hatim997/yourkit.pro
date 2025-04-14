<section class="deal-wrap">
    <div class="container-xxl">
      <div class="title-prt">
        <h2>Deal of the day</h2>
        <a href="{{route('ecommerce')}}" class="btn btn-outline-warning">View All <i class="fa-solid fa-arrow-right"></i></a>
      </div>

      <div class="deal-slider owl-carousel mt-5 slider">
        @if (!empty($products))
                
         @foreach ($products as $product)
            <div class="item" data-aos="fade-up" data-aos-duration="1500">
                <div class="deal-bx">
                    <a href="{{ route('ecom.details', $product->productId) }}">
                        <div class="deal-img">
                           <img class="img-fluid" 
     src="{{ $product->ecommerce()->first()?->images()->first()?->image ? url('storage/' . $product->ecommerce()->first()->images()->first()->image) : asset('assets/frontend/images/logo.png') }}" 
     alt="Product Image" />
                            {{-- <span class="hot-tag">Hot <br />Deal</span> --}}
                        </div>
                        <div class="content-prt">
                            <h3>{{ $product->name }}</h3>
                            <span class="price">${{ $product->ecommerce()->first()->price }}</span>
                        </div>
                    </a>
                </div>
            </div>
         @endforeach
        @endif

        
      </div>
    </div>
</section>
