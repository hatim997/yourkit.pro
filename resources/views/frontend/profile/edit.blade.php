@extends('frontend.layouts')

@section('title', 'Profile Edit')

@section('content')
    <section class="main-wrap">
        <div class="container-xxl">

            <div class="row">

                @include('frontend.includes.authenticate-menu')

                <div class="col-md-9">
                    <div class="rt-sec">
                        <div class="details-hdr">
                            <h2>Personal Info</h2>
                            <a href="{{ route('frontend.dashboard') }}" class="btn btn-outline-warning">Back</a>
                        </div>

                        <form action="{{ route('frontend.profile.update') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="custom-fld col-sm-6">
                                    <h4>*First Name <span><input class="form-control @error('name') is-invalid @enderror"
                                                type="text" name="name" placeholder="Enter your name" value="{{ $user->name ?? '' }}"></span></h4>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="custom-fld col-sm-6">
                                    <h4>*Date of birth <span><input class="form-control @error('dob') is-invalid @enderror"
                                                type="date" name="dob" placeholder="Enter your date of birth" value="{{ $user->userDetail->dob ?? '' }}"></span>
                                    </h4>
                                    @error('dob')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="custom-fld col-sm-6">
                                    <h4>*Location <span><input class="form-control @error('location') is-invalid @enderror"
                                                type="text" name="location" placeholder="Enter your location" value="{{ $user->userDetail->location ?? '' }}"></span>
                                    </h4>
                                    @error('location')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="custom-fld col-sm-6">
                                    <h4>*Address <span><input class="form-control @error('address') is-invalid @enderror"
                                                type="text" name="address"
                                                placeholder="Enter your complete address" value="{{ $user->userDetail->address ?? '' }}"></span></h4>
                                    @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="custom-fld col-sm-12">
                                    <button type="submit" class="btn btn-submit w-20 mb-3">Submit</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
