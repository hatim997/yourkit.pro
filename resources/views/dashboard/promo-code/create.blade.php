@extends('layouts.master')

@section('title', __('Create Promo Code'))

@section('css')
    <style>
    </style>
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.promo-codes.index') }}">{{ __('Promo Codes') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Create') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Add New Promo Code</h5>
            </div>
            <form action="{{ route('dashboard.promo-codes.store') }}" method="POST" enctype="multipart/form-data">
                <div class="card-body row">
                    @csrf
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="code" class="form-label" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Only letters and numbers allowed. No spaces or special characters.') }}">Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" id="code"
                                name="code" value="{{ old('code') }}" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="discount_percentage" class="form-label">Discount Percentage (%) <span class="text-danger">*</span></label>
                            <input type="number" min="0" max="100" class="form-control @error('discount_percentage') is-invalid @enderror" id="discount_percentage"
                                name="discount_percentage" value="{{ old('discount_percentage') }}" required>
                            @error('discount_percentage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="valid_until" class="form-label">Valid Until <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('valid_until') is-invalid @enderror" id="valid_until"
                                name="valid_until" value="{{ old('valid_until') }}" required>
                            @error('valid_until')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="usage_limit" class="form-label">Usage Limit <span class="text-danger">*</span></label>
                            <input type="number" min="0" class="form-control @error('usage_limit') is-invalid @enderror" id="usage_limit"
                                name="usage_limit" value="{{ old('usage_limit') }}" required>
                            @error('usage_limit')
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
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">Add Promo Code</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
<script>
    $(document).ready(function () {
        $('#code').on('input', function () {
            let value = $(this).val();
            // Remove any non-alphanumeric characters
            let sanitized = value.replace(/[^a-zA-Z0-9]/g, '');
            $(this).val(sanitized);
        });
        $('#discount_percentage').on('input', function () {
            let value = parseInt($(this).val());
            if (value < 0) {
                $(this).val(0);
            } else if (value > 100) {
                $(this).val(100);
            }
        });
    });
</script>
@endsection
