

{{-- @extends('admin.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>
                        Edit Email Template
                    </h1>
                </div>
            </div>

            

            <div class="card">

                <form action="{{route('admin.email-templates.update', [$email->id])}}" method = 'post' enctype="multipart/form-data">
                    @csrf
                    @method("patch")

                    <div class="card-body">
                        <div class="row">
                            @include('admin.email_template.fields')
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('admin.email-templates.index') }}" class="btn btn-default"> Cancel </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection --}}

@extends('admin.master')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">

            <div class="mb-3">
                <h1 class="h3 d-inline align-middle">Edit Email Template</h1>
            </div>
            <div class="row">

                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.email-templates.update', [$email->id])}}" method = 'post' enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @include('admin.email_template.fields')
                    </form>
                    </div>
                </div>
            </div>

        </div>
    </main>
@endsection

