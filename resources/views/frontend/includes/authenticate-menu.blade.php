<div class="col-md-3">
    <div class="lft-prt-dshboard">
        <div class="lft-profile-top">
            <div class="profileImg">
                <span>
                    <img id="previewImage" src="{{ isset(Auth::user()->image) ? url('storage/'.Auth::user()->image) : asset('assets/img/default/user.png') }}" alt="Profile Image">
                    <label for="userPic">
                        <input type="file" name="" id="userPic" hidden="" accept="image/*">
                        <i class="fa-solid fa-pen"></i>
                    </label>
                </span>
            </div>
            <h3>{{ Auth::user()->name  }} <span>{{ Auth::user()->phone }}</span></h3>
        </div>

        <div class="dashboard-list">
            <ul>
                <li  class="{{ Route::is('frontend.dashboard') ? 'active' : '' }}"><a href="{{route('frontend.dashboard')}}"><i class="fa fa-user"></i> My Info</a></li>
                <li class="{{ Route::is('frontend.order.view') ? 'active' : '' }}"><a href="{{route('frontend.order.view')}}"><i class="fa fa-list-alt"></i> My Order</a></li>
                {{-- <li><a href="wishlist.html"><i class="fa fa-list-alt"></i> Wishlist</a></li> --}}
                <li><a href="{{ route('frontend.logout') }}"><i class="fa-solid fa-power-off"></i> Log Out</a></li>
            </ul>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#userPic').on('change', function() {
            var file = this.files[0];
            if (file) {
                var formData = new FormData();
                formData.append('image', file);
                formData.append('_token', '{{ csrf_token() }}');


                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#previewImage').attr('src', e.target.result);
                };
                reader.readAsDataURL(file);


                $.ajax({
                    url: "{{ route('frontend.upload.image') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if(response.status == 'success') {
                            toastr.success('Image uploaded successfully!');
                        } else {
                            toastr.error('Upload failed, please try again.');
                        }
                    },
                    error: function() {
                        toastr.error('Something went wrong.');
                    }
                });
            }
        });
    });
</script>
