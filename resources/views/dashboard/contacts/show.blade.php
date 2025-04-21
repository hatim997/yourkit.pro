@extends('layouts.master')

@section('title', __('View Contact Message'))

@section('css')
    <style>
    </style>
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.contact-messages.index') }}">{{ __('Contact Messages') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('View') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row invoice-preview">
            <!-- Invoice -->
            <div class="col-xl-12 col-md-12 col-12 mb-md-0 mb-6">
                <div class="card invoice-preview-card p-sm-12 p-6">
                    <h6>Details:</h6>
                    <div class="card-body invoice-preview-header rounded">
                        <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column align-items-xl-center align-items-md-start align-items-sm-center align-items-start">
                            <div class="mb-xl-0 mb-6 text-heading">
                                <p class="mb-2"><span style="font-weight: 600;">Name : </span>{{ $contact->name }}</p>
                                <p class="mb-2"><span style="font-weight: 600;">Phone Number : </span>{{ $contact->phone }}</p>
                                <p class="mb-2"><span style="font-weight: 600;">Email : </span>{{ $contact->email }}</p>
                                <p class="mb-2"><span style="font-weight: 600;">Received At : </span>{{ $contact->created_at->format('d F, Y h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12 mb-xl-0 mb-md-6 mb-sm-0 mb-6">
                                <h6>Message:</h6>
                                <p class="mb-1">{{ $contact->message }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Invoice -->
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            //
        });
    </script>
@endsection
