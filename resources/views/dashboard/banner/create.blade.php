@extends('layouts.master')

@section('title', __('Create Banner'))

@section('css')
    <style>
    </style>
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{route('dashboard.banners.index')}}">{{ __('Banners') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Create') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Add New Banner</h5>
            </div>
            <form action="{{ route('dashboard.banners.store') }}" method="POST" enctype="multipart/form-data">
                <div class="card-body row">
                    @csrf
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="title" class="form-label">Banner Title <span
                                class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                                name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="type">{{ __('Banner Type') }} <span
                                    class="text-danger">*</span></label>
                            <div class="select2-primary">
                                <select id="type" name="type" class="select2 form-select">
                                    <option value="" selected disabled>Select Value</option>
                                    <option value="top">Top</option>
                                    <option value="middle">Middle</option>
                                    <option value="bottom">Bottom</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="url_name" class="form-label">Button Text <span
                                class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('url_name') is-invalid @enderror" id="url_name"
                                name="url_name" value="{{ old('url_name') }}" required>
                            @error('url_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="url" class="form-label">URL <span
                                class="text-danger">*</span></label>
                            <input type="url" class="form-control @error('url') is-invalid @enderror" id="url"
                                name="url" value="{{ old('url') }}" required>
                            @error('url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span
                                class="text-danger">*</span></label>
                            <textarea rows="5" class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="image" class="form-label">Image <span
                                class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" accept="image/*"
                                id="image" name="image">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="status">{{ __('Status') }} <span
                                    class="text-danger">*</span></label>
                            <div class="select2-primary">
                                <select id="status" name="status" class="select2 form-select">
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">Add Banner</button>
                    </div>
                </div>
            </form>
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
