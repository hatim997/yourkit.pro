@extends('frontend.layouts')

@section('title', 'Dashboard')

@section('content')
    <section class="main-wrap">
        <div class="container-xxl">

            <div class="row">

                @include('frontend.includes.authenticate-menu')

                <div class="col-md-9">
                    <div class="rt-sec">
                        <div class="details-hdr">
                            <h2>Personal Info</h2>
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-warning">Edit</a>
                        </div>

                        <div class="row">
                            <div class="custom-fld col-sm-6">
                                <h4>*First Name <span>{{ $user->name ??''}}</span></h4>
                            </div>
                            <div class="custom-fld col-sm-6">
                                <h4>*Date of birth <span>{{ $user->profile->dob ??'' }}</span></h4>
                            </div>
                            <div class="custom-fld col-sm-6">
                                <h4>*Location <span>{{ $user->profile->location ??''}}</span></h4>
                            </div>
                            <div class="custom-fld col-sm-6">
                                <h4>*Address <span>{{ $user->profile->address ??''}}</span></h4>
                            </div>
                        </div>

                        <hr class="my-4" />

                        <div class="details-hdr">
                            <h2>Contact Info</h2>
                        </div>

                        <div class="row">
                            <div class="custom-fld col-sm-6">
                                <h4>*Contact Number <span>{{ $user->phone }}</span></h4>
                            </div>
                            <div class="custom-fld col-sm-6">
                                <h4>*Contact Email ID <span>{{ $user->email }}</span></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
