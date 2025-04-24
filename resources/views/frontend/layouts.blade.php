{{-- @php
     $settings = \DB::table('settings')->pluck('value', 'setting_name');
@endphp --}}
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->

   <link rel="icon" type="image/x-icon" href="{{ asset(\App\Helpers\Helper::getFavicon())}}">
    <link rel="stylesheet" href="{{ url(asset('assets/frontend/css/bootstrap.min.css')) }}">
    <link rel="stylesheet" href="{{ url(asset('assets/frontend/css/owl.carousel.2.3.4.css')) }}">
    <link rel="stylesheet" href="{{ url(asset('assets/frontend/css/aos.2.3.1.css')) }}">
    <link rel="stylesheet" href="{{ url(asset('assets/frontend/css/all.min.css')) }}">
    <link rel="stylesheet" href="{{ url(asset('assets/frontend/css/smart_wizard.css')) }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css" integrity="sha512-yHknP1/AwR+yx26cB1y0cjvQUMvEa2PFzt1c9LlS4pRQ5NOTZFWbhBig+X9G9eYW/8m0/4OXNx8pxJ6z57x0dw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ url(asset('assets/frontend/css/style.css?v='.\App\Utils\Helper::getCssVersion())) }}" />
    <link rel="stylesheet" href="{{ url(asset('assets/frontend/css/responsive.css?v='.\App\Utils\Helper::getCssVersion())) }}">
    @stack('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" />
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">


    <title>{{ \App\Helpers\Helper::getCompanyName()}}</title>

    {{-- Toastr Assets --}}
    <link rel="stylesheet" href="{{ url(asset('assets/frontend/css/toastr.min.css?v='.\App\Utils\Helper::getCssVersion())) }}">
    <script src="{{ url(asset('assets/frontend/js/toastr_jquery.min.js?v='.\App\Utils\Helper::getCssVersion())) }}"></script>
    <script src="{{ url(asset('assets/frontend/js/toastr.min.js?v='.\App\Utils\Helper::getCssVersion())) }}"></script>

  <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-VTR94KBZJN"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-VTR94KBZJN');
</script>

<style>
    .discount-badge {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        font-size: 15px;
        transform: translate(50%, -50%);
        z-index: 1;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        line-height: 1.2;
        padding: 4px;
    }
    .discount-badge small {
        font-size: 8px;
    }
</style>
</head>

<body>
    <header class="header">
        <div id="google_translate_element"></div>
        <div class="container-xxl">
            <nav class="navbar navbar-expand-lg navbar-light">
                <a class="navbar-brand" href="{{ route('frontend.home') }}">
                    <img class="img-fluid" src="{{ asset(\App\Helpers\Helper::getLogoLight()) }}" alt="">
                </a>

                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item {{ request()->routeIs('frontend.home') ? 'active' : '' }}"><a class="nav-link"
                                href="{{ route('frontend.home') }}">Home</a></li>
                        <li class="nav-item {{ request()->routeIs('frontend.product') ? 'active' : '' }}"><a class="nav-link"
                                href="{{ route('frontend.product') }}">Products</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('frontend.ecommerce') }}">What’s New</a></li>
                        <li class="nav-item {{ request()->routeIs('frontend.faq') ? 'active' : '' }}"><a class="nav-link"
                                href="{{ route('frontend.faq') }}">FAQ</a></li>
                        <li class="nav-item {{ request()->routeIs('frontend.contact') ? 'active' : '' }}"><a class="nav-link"
                                href="{{ route('frontend.contact') }}">CONTACT US</a></li>
                    </ul>
                </div>

                <div class="my-ac-wrap">
                    <ul>
                        <li class="hed-search-wrap">
                            <div class="hed-search">
                                {{-- <a class="open-search" href="#">
                                    <i class="bi bi-search"></i>
                                </a> --}}
                                <div class="form-search">
                                    <form>
                                        <input type="search" placeholder="Search:">
                                        <button type="submit"><i class="bi bi-search"></i></button>
                                    </form>
                                </div>
                                <div class="close-overlay"></div>
                            </div>
                        </li>
                        <li>

                            @if (Auth::check())
                                <div class="user-ac">
                                    <a href="{{ route('frontend.dashboard') }}"><i class="bi bi-person"></i><p>{{ Auth::user()->name }}</p></a>
                                </div>
                            @else
                                <div class="user-ac">
                                    <a href="{{ route('frontend.signin') }}"><i class="bi bi-person"></i></a>
                                </div>
                            @endif


                        </li>
                        <li>
                            <div class="cart">
                                <a href="{{ route('frontend.cart') }}">
                                    <i class="bi bi-bag"></i>
                                    <span class="count" id="cartCount"></span>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="fa fa-bars"></span>
                </button>
                {{-- <div class="dropdown text-end m-4">
                    <button class="btn  dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                      Dropdown button
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                      <li><a class="dropdown-item" href="#">Action</a></li>
                      <li><a class="dropdown-item" href="#">Another action</a></li>
                      <li><a class="dropdown-item" href="#">Something else here</a></li>
                    </ul>
                </div> --}}

            </nav>


        </div>
    </header>



    @if (!Request::is('/'))
        @include('frontend.includes.header')
    @endif

    {{-- {!!Toastr::message() !!} --}}

    @yield('content')

    @include('frontend.includes.footer')
<a href="javascript:void(0)" id="BackToTop-btn"></a>
@yield('script')
<script>
    @if(session('error'))
        toastr.error("{{ session('error') }}", "Error", {
            "closeButton": true,
            "progressBar": true
        });
    @endif

    @if(session('success'))
        toastr.success("{{ session('success') }}", "Success", {
            "closeButton": true,
            "progressBar": true
        });
    @endif

    @if(session('message'))
        toastr.info("{{ session('message') }}", "Info", {
            "closeButton": true,
            "progressBar": true
        });
    @endif
</script>
<script>
$(document).ready(function() {
    var btn = $("#BackToTop-btn");

    $(window).scroll(function () {
        if ($(window).scrollTop() > 300) {
            btn.addClass("show");
        }
        else {
            btn.removeClass("show");
        }
    });

    btn.on("click", function (e) {
        e.preventDefault();
        $("html, body").animate({ scrollTop: 0 }, "300");
    });
});

    </script>
</script>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="{{ url(asset('assets/frontend/js/jquery.min.js')) }}"></script>
    <script src="{{ url(asset('assets/frontend/js/popper.min.js')) }}"></script>
    <script src="{{ url(asset('assets/frontend/js/bootstrap.min.js')) }}"></script>
    <script src="{{ url(asset('assets/frontend/js/owl.carousel.2.3.4.min.js')) }}"></script>
    <script src="{{ url(asset('assets/frontend/js/aos.2.3.1.js')) }}"></script>
    <script src="{{ url(asset('assets/frontend/js/modernizr.js')) }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js" integrity="sha512-HGOnQO9+SP1V92SrtZfjqxxtLmVzqZpjFFekvzZVWoiASSQgSr4cw9Kqd2+l8Llp4Gm0G8GIFJ4ddwZilcdb8A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ url(asset('assets/frontend/js/jquery.smartWizard.min.js')) }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script src="{{ url(asset('assets/frontend/js/main.js?v='.\App\Utils\Helper::getCssVersion())) }}"></script>
    <style>
        body  {
        top: 0px !important;
        }

    </style>
    <script type="text/javascript">
        function googleTranslateElementInit() {

            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'en,fr',
                autoDisplay: 'true',
                layout: google.translate.TranslateElement.InlineLayout.HORIZONTAL
                }, 'google_translate_element');

        }

    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    @stack('scripts')
    <!-- Loader -->
    <div id="preloader">
        <div class="jumper">
        <div></div>
        <div></div>
        <div></div>
        </div>
    </div>
    <script>
        function fetchData(sessionId) {
            let url = "{{ route('frontend.cart.count') }}";

            $.ajax({
                url: url,
                method: "GET",
                data: {
                    sessionId: sessionId
                },
                success: function(response) {
                    console.log("count:", response);
                    $('#cartCount').text(response);

                },
                error: function(xhr, status, error) {
                    console.error("Error:", status, error);
                }
            });
        }

        $(document).ready(function() {
            let sessionId = localStorage.getItem('sessionId') && JSON.parse(localStorage.getItem('sessionId')) ||
                "";
            console.log('cartData', sessionId);
            // fetchData(sessionId);
            // setInterval(() => fetchData(sessionId), 2000);

            fetchData(sessionId)
        });

         // Page loading animation
        $(window).on('load', function() {
            if($('.cover').length){
                $('.cover').parallax({
                    imageSrc: $('.cover').data('image'),
                    zIndex: '1'
                });
            }

            $("#preloader").animate({
                'opacity': '0'
            }, 600, function(){
                setTimeout(function(){
                    $("#preloader").css("visibility", "hidden").fadeOut();
                }, 300);
            });
        });
    </script>


</body>

</html>
