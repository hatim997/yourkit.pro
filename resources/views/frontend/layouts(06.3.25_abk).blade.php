@php
     $settings = \DB::table('settings')->pluck('value', 'setting_name');
@endphp
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
   
   <link rel="icon" type="image/x-icon" href="{{ url('storage/' . (Helper::setting('favicon') ?? '')) }}">
    <link rel="stylesheet" href="{{ url(asset('assets/frontend/css/bootstrap.min.css')) }}">
    <link rel="stylesheet" href="{{ url(asset('assets/frontend/css/owl.carousel.2.3.4.css')) }}">
    <link rel="stylesheet" href="{{ url(asset('assets/frontend/css/aos.2.3.1.css')) }}">
    <link rel="stylesheet" href="{{ url(asset('assets/frontend/css/all.min.css')) }}">
    <link rel="stylesheet" href="{{ url(asset('assets/frontend/css/smart_wizard.css')) }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css" integrity="sha512-yHknP1/AwR+yx26cB1y0cjvQUMvEa2PFzt1c9LlS4pRQ5NOTZFWbhBig+X9G9eYW/8m0/4OXNx8pxJ6z57x0dw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ url(asset('assets/frontend/css/style.css?v=1.0.2')) }}">
    <link rel="stylesheet" href="{{ url(asset('assets/frontend/css/responsive.css')) }}">
    @stack('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" />
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">


    <title>{{ Helper::setting('title') ?? '' }}</title>

    {{-- Toastr Assets --}}
    <link rel="stylesheet" href="{{ url(asset('assets/frontend/css/toastr.min.css?v=1.0.0')) }}">
    <script src="{{ url(asset('assets/frontend/js/toastr_jquery.min.js?v=1.0.0')) }}"></script>
    <script src="{{ url(asset('assets/frontend/js/toastr.min.js?v=1.0.0')) }}"></script>
  <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-2RJQKEZCBW"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-2RJQKEZCBW');
</script>
</head>

<body>
    <header class="header">
        <div class="container-xxl">
            <nav class="navbar navbar-expand-lg navbar-light">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img class="img-fluid" src="{{ url('storage/' . (Helper::setting('logo') ?? '')) }}" alt="">
                </a>

                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}"><a class="nav-link"
                                href="{{ route('home') }}">Home</a></li>
                        <li class="nav-item {{ request()->routeIs('product') ? 'active' : '' }}"><a class="nav-link"
                                href="{{ route('product') }}">Products</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('ecommerce') }}">What’s New</a></li>
                        <li class="nav-item {{ request()->routeIs('faq') ? 'active' : '' }}"><a class="nav-link"
                                href="{{ route('faq') }}">FAQ</a></li>
                        <li class="nav-item {{ request()->routeIs('contact') ? 'active' : '' }}"><a class="nav-link"
                                href="{{ route('contact') }}">CONTACT US</a></li>
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
                                    <a href="{{ route('dashboard') }}"><i class="bi bi-person"></i><p>{{ Auth::user()->name }}</p></a>
                                </div>
                            @else
                                <div class="user-ac">
                                    <a href="{{ route('signin') }}"><i class="bi bi-person"></i></a>
                                </div>
                            @endif


                        </li>
                        <li>
                            <div class="cart">
                                <a href="{{ route('cart') }}">
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
            </nav>
        </div>
    </header>



    @if (!Request::is('/'))
        @include('frontend.includes.header')
    @endif
    
    {!!Toastr::message() !!}
    @yield('content')

    @include('frontend.includes.footer')

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

    
    <script src="{{ url(asset('assets/frontend/js/main.js')) }}"></script>
    
    @stack('scripts')

    <script>
        function fetchData(sessionId) {
            let url = "{{ route('cart.count') }}";

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
    </script>


</body>

</html>
