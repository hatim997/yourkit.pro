@extends('frontend.layouts')

@section('title', 'Login')

@section('content')

    <section class="main-wrap">
        <div class="container">
            <div class="row">
                <!-- Login Form Column -->
                <div class="col-12 col-md-6">
                    <div class="login-register-form-wrap">
                        <h3>Login</h3>
                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="loginEmail" class="form-label">Email address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('user_email') is-invalid @enderror"
                                    placeholder="Enter your email" name="user_email" required>
                        
                                @error('user_email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="loginPassword" class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('user_password') is-invalid @enderror"
                                    placeholder="Enter your password" name="user_password" required>
                        
                                @error('user_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-submit">Login</button>
                            </div>
                            <div class="forgot-password">
                                <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">
                                    Forgot your password?
                                </a>
                            </div>
                        </form>
                    </div>
                </div>


                <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="forgotPasswordModalLabel">Reset Password</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="forgotPasswordForm" action="{{route('forget.password.post')}}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Enter your registered email</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Register Form Column -->
                <div class="col-12 col-md-6">
                    <div class="login-register-form-wrap">
                        <h3>Register</h3>
                        <form action="{{ route('register') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="registerName" class="form-label">Full Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Enter your name" name="name" required value="{{old('name')}}">

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                            <div class="form-group mb-3">
                                <label for="registerEmail" class="form-label">Email address <span
                                        class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Enter Your Email" name="email" required value="{{old('email')}}">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="registerEmail" class="form-label">Mobile <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                    placeholder="Enter Your Mobile" name="phone" required value="{{old('phone')}}">

                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="registerPassword" class="form-label">Password <span
                                        class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Create a password" name="password" required>

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="registerConfirmPassword" class="form-label">Confirm Password <span
                                        class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                    placeholder="Confirm your password" name="password_confirmation" required>

                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-submit">Register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection
