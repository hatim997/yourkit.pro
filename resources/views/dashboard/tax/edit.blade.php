@extends('layouts.master')

@section('title', __('Edit Tax'))

@section('css')
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.faqs.index') }}">{{ __('Taxes') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Edit') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Edit Tax</h5>
            </div>
            <form action="{{ route('dashboard.taxes.update', $tax->id) }}" method="POST" enctype="multipart/form-data">
            <div class="card-body row">
                    @csrf
                    @method('PUT')
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tax_type" class="form-label">Tax Type <span
                                class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('tax_type') is-invalid @enderror" id="tax_type"
                                name="tax_type" value="{{ old('tax_type', $tax->tax_type) }}" required>
                            @error('tax_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="slug" class="form-label">Tax Slug <span
                                class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug"
                                name="slug" value="{{ old('slug', $tax->slug) }}" required>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="tax_code" class="form-label">Tax Code <span
                                class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('tax_code') is-invalid @enderror" id="tax_code"
                                name="tax_code" value="{{ old('tax_code', $tax->tax_code) }}" required>
                            @error('tax_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="percentage" class="form-label">Tax Percentage (%)<span
                                class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('percentage') is-invalid @enderror" id="percentage"
                                name="percentage" value="{{ old('percentage', $tax->percentage) }}" required>
                            @error('percentage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label" for="status">{{ __('Status') }} <span
                                    class="text-danger">*</span></label>
                            <div class="select2-primary">
                                <select id="status" name="status" class="select2 form-select">
                                    <option value="1" {{ $tax->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $tax->status == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">Update Category</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#tax_type').on('input', function() {
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
