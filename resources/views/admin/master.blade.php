<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords"
        content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="icon" type="image/x-icon" href="{{ url('storage/' . (Helper::setting('favicon') ?? '')) }}">

    <link rel="canonical" href="https://demo-basic.adminkit.io/" />

    <title>Construction-Tshirt | @yield('title')</title>

    <link href="{{ url(asset('assets/admin/css/app.css')) }}" rel="stylesheet">

      {{-- Toastr Assets --}}
    <link rel="stylesheet" href="{{ url(asset('assets/frontend/css/toastr.min.css?v=1.0.0')) }}">
    <script src="{{ url(asset('assets/frontend/js/toastr_jquery.min.js?v=1.0.0')) }}"></script>
    <script src="{{ url(asset('assets/frontend/js/toastr.min.js?v=1.0.0')) }}"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" integrity="sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    @stack('third_party_stylesheets')

    @stack('styles')

    <link href="{{ url(asset('assets/admin/css/custom.css')) }}" rel="stylesheet">
   
</head>

<body>
    <div class="wrapper">

        <!-- Navigation -->

        @include('admin.includes.menu')


        <div class="main">

            <!-- Topbar -->

            @include('admin.includes.topbar')



            <!-- Main Section -->

            @yield('content')

            {!! Toastr::message() !!}


            <footer class="footer">
                <div class="container-fluid">
                    <div class="row text-muted">
                        <div class="col-6 text-start">
                            <p class="mb-0">
                                <a class="text-muted" href="https://adminkit.io/"
                                    target="_blank"> <a class="text-muted"
                                    href="https://adminkit.io/" target="_blank"><strong>Construction T-Shirt Kit</strong></a> &copy;
                            </p>
                        </div>
                        {{-- <div class="col-6 text-end">
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <a class="text-muted" href="https://adminkit.io/" target="_blank">Support</a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="text-muted" href="https://adminkit.io/" target="_blank">Help Center</a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="text-muted" href="https://adminkit.io/" target="_blank">Privacy</a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="text-muted" href="https://adminkit.io/" target="_blank">Terms</a>
                                </li>
                            </ul>
                        </div> --}}
                    </div>
                </div>
            </footer>
        </div>

    </div>

    <script src="{{ url(asset('assets/admin/js/app.js')) }}"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  

        {{-- Toastr Assets --}}
    <link rel="stylesheet" href="{{ url(asset('assets/frontend/css/toastr.min.css?v=1.0.0')) }}">
    <script src="{{ url(asset('assets/frontend/js/toastr_jquery.min.js?v=1.0.0')) }}"></script>
    <script src="{{ url(asset('assets/frontend/js/toastr.min.js?v=1.0.0')) }}"></script>

    <script src="{{ url(asset('assets/admin/tinymce/tinymce/tinymce.min.js')) }}"></script>
    <script>
        tinymce.init({
        selector: '#tinymce-editor', // Replace this CSS selector to match the placeholder element for TinyMCE
        plugins: 'code table lists',
        elementpath: false,
        promotion: false,
        branding: false,
        license_key: 'gpl',
        advcode_inline: true,
        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table'
    });
      </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/fontawesome.min.css" integrity="sha512-TPigxKHbPcJHJ7ZGgdi2mjdW9XHsQsnptwE+nOUWkoviYBn0rAAt0A5y3B1WGqIHrKFItdhZRteONANT07IipA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    @stack('third_party_scripts')

   

    @stack('scripts')

</body>

</html>
