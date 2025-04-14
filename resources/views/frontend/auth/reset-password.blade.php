@extends('frontend.layouts')

@section('title', 'Reset Password')

@section('content')


    <section class="main-wrap">
        <div class="container">
            <div class="row">
                <!-- Login Form Column -->
                <div class="col-12 col-md-6">
                    <div class="login-register-form-wrap">
                      
                        <form action="{{ route('reset.password.post') }}" method="POST">
                            @csrf
                            <div class="login-form-head">
                                <h3>Reset Password </h3>
                                <p>Please enter new password</p>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 mb-3">
                                    <div class="login-input-box">
                                        <label for="password">Password<span>*</span></label>
                                        <input type="password" name="password" id="password" placeholder="Password"
                                            class="form-control">
                                        @error('password')
                                            <span class="text-danger" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12 col-sm-12 mb-3">
                                    <div class="login-input-box">
                                        <label for="password">Confirm Password<span>*</span></label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            placeholder="Confirm Password" class="form-control">
                                        @error('password_confirmation')
                                            <span class="text-danger" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <input type="hidden" name="token" value="{{ $token }}">
                                <div class="col-md-12 col-sm-12 mb-3">
                                    <div class="login-input-box">
                                        <button type="submit" class="btn btn-submit">Submit</button>
                                    </div>
                                </div>
                            </div>
                            <div class="login-form-lwr">

                                Back to login?
                                <a href="{{ route('signin') }}">Click Here</a>

                            </div>



                        </form>
                    </div>
                </div>


            </div>

        </div>
    </section>

@endsection
