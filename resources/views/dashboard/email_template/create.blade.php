

@extends('admin.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>
                        Create Blog
                    </h1>
                </div>
            </div>

        {{-- @include('adminlte-templates::common.errors') --}}

            <div class="card">

                {!! Form::open(['route' => 'admin.blogs.store','files' => true]) !!}

            <div class="card-body">

                <div class="row">
                    @include('admin.blogs.fields')
                </div>

            </div>

            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('admin.blogs.index') }}" class="btn btn-default"> Cancel </a>
            </div>

            {!! Form::close() !!}

            </div>
        </div>
    </div>
@endsection

