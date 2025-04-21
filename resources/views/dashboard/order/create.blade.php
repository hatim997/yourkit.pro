@extends('layouts.master')

@section('title', __('Create Category'))

@section('css')
    <style>
    </style>
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item">{{ __('Categories') }}</li>
    <li class="breadcrumb-item active">{{ __('Create') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Categories List Table -->

        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Add New Category</h5>
            </div>
            <form action="{{ route('dashboard.categories.store') }}" method="POST" enctype="multipart/form-data">
                <div class="card-body row">
                    @csrf
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="slug" class="form-label">Category Slug</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug"
                                name="slug" value="{{ old('slug') }}" required>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="image" class="form-label">Category Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" accept="image/*"
                                id="image" name="image">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">Add Category</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#name').on('input', function() {
                let slug = $(this).val()
                    .toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '') // Remove invalid chars
                    .replace(/\s+/g, '-') // Replace spaces with -
                    .replace(/-+/g, '-'); // Replace multiple - with single -
                $('#slug').val(slug);
            });
        });
    </script>
@endsection
