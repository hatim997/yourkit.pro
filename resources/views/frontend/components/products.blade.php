<section class="main-wrap">
    <div class="container-xxl">

        <div class="top-title-prt">
            <div class="style-txt-prt" data-aos="fade-left" data-aos-duration="3000">
                <h2>FREE! Full Color Print</h2>
                <p>Or embroideries up to 7500 stitches in one location</p>
            </div>
            <ul class="filter-lst">

                @empty(!$subcategories)
                    @foreach ($subcategories->where('status',1) as $subcategory)
                        <!--<li class="active" data-aos="zoom-in" data-aos-duration="2000">-->
                        <!--    <div class="filter-ico">-->
                        <!--        <img src="{{ isset($subcategory->media) ? url(asset('storage/'. $subcategory->media->path)) : "" }}" alt="" />-->
                        <!--        <span><img src="{{ isset($subcategory->media) ? url(asset('storage/'. $subcategory->media->path)) : "" }}"-->
                        <!--                alt="" /></span>-->
                        <!--    </div>-->
                        <!--    <h4>{{ $subcategory->name }}</h4>-->
                        <!--</li>-->
                        <li class="subcategory-filter" data-id="{{ $subcategory->id }}" data-aos="zoom-in" data-aos-duration="2000">
                            <div class="filter-ico">
                                <img src="{{ isset($subcategory->media) ? url(asset('storage/'. $subcategory->media->path)) : "" }}" alt="" />
                                <span><img src="{{ isset($subcategory->media) ? url(asset('storage/'. $subcategory->media->path)) : "" }}"
                                        alt="" /></span>
                            </div>
                            <h4>{{ $subcategory->name }}</h4>
                        </li>
                    @endforeach
                @endempty

            </ul>
        </div>
        <div id="bundle-loader" style="display:none;" class="text-center my-5">
            <div class="spinner-border text-warning" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div class="row mt-5 customize-bx">

            @forelse ($bundles as $bundle)
                @php
                    $subCatIds = $bundle->products->pluck('sub_category_id')->unique()->implode(',');
                @endphp
                <div class="col-md-4 bundle-item" data-subcategories="{{ $subCatIds }}">
                    <!--<div class="pro-bx" style="height: 470px" data-aos="flip-left" data-aos-duration="1000">-->
                    <div class="pro-bx" style="height: 470px" data-aos-duration="1000">
                        <div class="price-tag">
                            <h5><span>Starting at</span> {{ App\Helpers\Helper::formatCurrency($bundle->price) }}</h5>
                        </div>
                        <div class="pro-img">
                            @if(is_file(public_path('storage/'.$bundle->image)))
                                <img class="img-fluid" src="{{ url(asset('storage/'.$bundle->image)) }}"  alt="" />
                            @else
                                <img class="img-fluid" src="{{ url(asset('default-shirt.png')) }}"  alt="" />
                            @endif
                        </div>
                        <ul class="quantity-info-lst">
                            @forelse ($bundle->products as $product)
                                <li>
                                    <span class="quantity">{{ $product->pivot->quantity }}</span>
                                    <p>{{ $product->name }}</p>
                                </li>
                            @empty
                                <li>No products available</li>
                            @endforelse
                        </ul>
                        <div class="select-overlay">
                            <a href="{{ route('frontend.product.page', ['meta' => $bundle->uuid]) }}" class="btn btn-outline-warning">Select <i
                                    class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            @empty
                <p>No bundles available.</p>
            @endforelse


        </div>

        @if (request()->routeIs('frontend.home'))

        <div class="text-center mt-4">
            <a href="{{ route('frontend.product') }}" class="btn btn-outline-warning">View All <i class="fa-solid fa-arrow-right"></i></a>
        </div>

        @endif

    </div>
</section>
<script>
    $(document).ready(function () {
        // Initialize with ALL subcategory IDs selected
        let selectedSubcats = $('.subcategory-filter').map(function() {
            return $(this).data('id');
        }).get();

        // Mark all filters as active initially
        $('.subcategory-filter').addClass('active');

        const $loader = $('#bundle-loader');
        const $bundles = $('.bundle-item');
        const $filters = $('.subcategory-filter');

        // Click handler for subcategory filters
        $filters.on('click', function () {
            const categoryId = $(this).data('id');
            const index = selectedSubcats.indexOf(categoryId);

            if (index === -1) {
                // Remove from selection
                selectedSubcats.push(categoryId);
                $(this).removeClass('active');
            } else {

                // Add back if clicked again
                selectedSubcats.splice(index, 1);
                $(this).addClass('active');
            }

            console.log('Selected Categories:', selectedSubcats);
            showLoaderAndFilter();
        });

        function showLoaderAndFilter() {
            $bundles.hide();
            $loader.fadeIn(200);

            setTimeout(() => {
                filterBundles();
                $loader.fadeOut(200);
            }, 400);
        }

        function filterBundles() {
            if (selectedSubcats.length === 0) {
                // If nothing selected, show nothing
                $bundles.hide();
                return;
            }

            $bundles.each(function () {
                const bundleSubcats = $(this).data('subcategories').toString().split(',').map(Number);

                // Show bundle ONLY if it doesn't contain ANY of the removed categories
                // (i.e., all its categories must be in selectedSubcats)
                const shouldShow = bundleSubcats.every(catId =>
                    selectedSubcats.includes(catId)
                );

                $(this).toggle(shouldShow);
            });
        }

        // Initial filter
        filterBundles();
    });
</script>
