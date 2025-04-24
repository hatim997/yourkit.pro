@extends('layouts.master')

@section('title', __('Edit Ecommerce Product'))

@section('css')
    <style>
    </style>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">{{ __('Ecommerce Products') }}</li>
    <li class="breadcrumb-item active">{{ __('Edit') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Edit Ecommerce Product</h5>
            </div>
            <form action="{{ route('dashboard.ecommerce-products.update', $product->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="slug" class="form-label">Product Slug <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug"
                                name="slug" value="{{ old('slug', $product->slug) }}" required>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Category and Subcategory -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                            <select id="category_id" name="category_id"
                                class="select2 form-select @error('category_id') is-invalid @enderror">
                                <option value="" disabled>{{ __('Select Category') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="subcategory_id" class="form-label">Sub Category <span
                                    class="text-danger">*</span></label>
                            <select id="subcategory_id" name="subcategory_id"
                                class="select2 form-select @error('subcategory_id') is-invalid @enderror">
                                <option value="" disabled>{{ __('Select Sub Category') }}</option>
                                @foreach ($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}"
                                        {{ $product->subcategory_id == $subcategory->id ? 'selected' : '' }}>
                                        {{ $subcategory->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="size_chart" class="form-label">Size Chart </label>
                            <input type="file" class="form-control @error('size_chart') is-invalid @enderror"
                                id="size_chart" name="size_chart" value="{{ old('size_chart') }}">
                            @if ($product->size_chart)
                                <small>
                                    Current: <a href="{{ asset('storage/' . $product->size_chart) }}"
                                        target="_blank">View</a>
                                </small>
                            @endif
                            @error('size_chart')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select id="status" name="status" class="select2 form-select">
                                <option value="1" {{ $product->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $product->status == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12 mt-5">
                        <div class="row">
                            <div class="col-md-10">
                                <h5 class="mb-0">{{ __('Volume Discount (Optional)') }}</h5>
                            </div>
                            <div class="col-md-2">
                                <button type="button" id="addMoreDiscountBtn"
                                    class="add-new btn btn-sm btn-warning waves-effect waves-light">
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                                        class="d-none d-sm-inline-block">{{ __('Add') }}</span>
                                </button>
                            </div>
                        </div>

                        <!-- Container for dynamic discount fields -->
                        <div id="discountFieldsContainer">
                            @forelse ($product->productVolumeDiscounts as $discount)
                                <div class="row discount-group align-items-end">
                                    <div class="col-md-5">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Minimum Quantity') }}</label>
                                            <input type="number" min="0" class="form-control" name="quantity[]"
                                                value="{{ $discount->quantity }}">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Discount Percentage') }}</label>
                                            <input type="number" min="0" max="100" class="form-control"
                                                name="discount_percentage[]"
                                                value="{{ $discount->discount_percentage }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button"
                                            class="btn btn-danger btn-sm remove-discount">Remove</button>
                                    </div>
                                </div>
                            @empty
                                <div class="row discount-group align-items-end">
                                    <div class="col-md-5">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Minimum Quantity') }}</label>
                                            <input type="number" min="0" class="form-control" name="quantity[]">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Discount Percentage') }}</label>
                                            <input type="number" min="0" max="100" class="form-control"
                                                name="discount_percentage[]">
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                    </div>

                    <!-- Submit -->
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">Update Ecommerce Product</button>
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
                let slug = $(this).val().toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
                $('#slug').val(slug);
            });
        });

        $('#addMoreDiscountBtn').click(function () {
            let html = `
                <div class="row discount-group align-items-end">
                    <div class="col-md-5">
                        <div class="mb-3">
                            <label class="form-label">Minimum Quantity</label>
                            <input type="number" min="0" class="form-control" name="quantity[]">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="mb-3">
                            <label class="form-label">Discount Percentage</label>
                            <input type="number" min="0" max="100" class="form-control" name="discount_percentage[]">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm remove-discount">Remove</button>
                    </div>
                </div>
            `;
            $('#discountFieldsContainer').append(html);
        });

        // Handle remove
        $('#discountFieldsContainer').on('click', '.remove-discount', function () {
            $(this).closest('.discount-group').remove();
        });
    </script>
@endsection
