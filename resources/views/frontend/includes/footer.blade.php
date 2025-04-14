<footer class="footer">
    <div class="container-xxl">
      <div class="newsletter-prt">
        <h3>Sign Up For Exclusive Offers From Us</h3>
        <div class="newsletter-bx">
          <form method="get" action="#" id="mailForm">
            <input type="hidden" id="id_idempotency_key" name="idempotency_key" value="{{ uniqid() }}">
            <input type="email" placeholder="Enter your email.." id="subscribe_email">
            <button type="submit" class="btn btn-outline-warning">Subscribe</button>
          </form>
        </div>
      </div>
@php
     $settings = \DB::table('settings')->pluck('value', 'setting_name');
@endphp
      <div class="row mt-5">
        <div class="col-md-4">
          <div class="f-logo">
             <img class="img-fluid" src="{{ url('storage/' . (Helper::setting('logo') ?? '')) }}" alt="">
          </div>
          <p class="copyright mt-5 d-none d-sm-block">{!! Helper::setting('footer-text') ?? '' !!}</p>
          
        </div>
        <div class="col-md-2">
          <ul class="footer-menu-list">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('product') }}">Products</a></li>
            <li><a href="#">What’s New</a></li>
            <li><a href="{{ route('faq') }}">FAQ</a></li>
            <li><a href="{{ route('contact') }}">CONTACT US</a></li>
            
          </ul>
        </div>
        <div class="col-md-3">
          <ul class="contact-lst">
            <li><a href="tel:15145618019"><strong>Tel:</strong>{{ $settings['site_phone'] ?? '+15145618019' }}</a></li>
            <li><a href="mailto:sales@yourkit.pro"><strong>Email:</strong>{{ $settings['site_email'] ?? 'sales@yourkit.pro' }}</a></li>
            <li><a href="{{ route('terms') }}">Terms of Sale</a></li>
            <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
            <li><a href="{{ route('return') }}">Return Policy</a></li>
            <li><a href="{{ route('delivery.info') }}">Delivery Information</a></li>
          </ul>
        </div>
        <div class="col-md-3">
          <ul class="f-social">
            <li><a href="{{ $settings['facebook'] }}" target="_blank"><i class="fa-brands fa-facebook-f"></i></a></li>
            <li><a href="{{ $settings['twitter'] }}" target="_blank"><i class="fa-brands fa-twitter"></i></a></li>
            <li><a href="{{ $settings['linkedin'] }}" target="_blank"><i class="fa-brands fa-linkedin"></i></a></li>
          </ul>
          <p class="copyright mt-3 d-block d-sm-none">yourkit.pro powered by WebOrka Inc.</p>
        </div>
      </div>
    </div>
  </footer>

  <script type="text/javascript">
    $(document).ready(function() {

        $('#mailForm').on('submit', function(e) {
            e.preventDefault();
            $('#sendMail').prop('disabled', true);
            //$('#sendMail').html('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>');
            var mailData = new FormData(document.getElementById('mailForm'));

            mailData.append('subscribe_email', $('#subscribe_email').val());


            $.ajax({
                url: "{{ route('send.newslettermail') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: mailData,
                dataType: "json",
                type: "POST",
                cache: false,
                processData: false,
                contentType: false,
                async: true,
                enctype: "multipart/form-data",
                success: function(response) {
                  console.log(response);
                    //  start_import();
                    // $('#sendMail').prop('disabled', false);

                    //$('#sendMail').html('Submit');

                    // if (response.success) {
                    //     $('#progress').css({
                    //         'display': 'none'
                    //     });
                    //     location.reload();
                    // } else {
                    //     console.log(response.message);
                    //     // alert(response.message);
                    // }
                    if (response.success){
                        toastr.success(response.message, response.title);
                        $("#subscribe_email").val("");
                        $("#id_idempotency_key").val("{{ uniqid() }}");
                        $('#sendMail').prop('disabled', false);

                    }else{
                        toastr.error(response.message, response.title);
                        $("#id_idempotency_key").val("{{ uniqid() }}");
                        $('#sendMail').prop('disabled', false);
                    }



                    // console.log(response);
                }
            });

        })


    });
</script>