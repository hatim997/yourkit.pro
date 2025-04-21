@extends('layouts.master')

@section('title', __('Create Kit Products'))

@section('css')
    <style>
    </style>
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item">{{ __('Kit Products') }}</li>
    <li class="breadcrumb-item active">{{ __('Create') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Categories List Table -->

        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Add New Kit Product</h5>
            </div>
            <form action="{{ route('dashboard.kit-products.store') }}" method="POST" enctype="multipart/form-data">
                <div class="card-body row">
                    @csrf
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="slug" class="form-label">Product Slug <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug"
                                name="slug" value="{{ old('slug') }}" required>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="category_id">{{ __('Category') }} <span
                                    class="text-danger">*</span></label>
                            <select id="category_id" name="category_id"
                                class="select2 form-select @error('category_id') is-invalid @enderror">
                                <option value="" selected disabled>{{ __('Select Category') }}</option>
                                @if (isset($categories) && count($categories) > 0)
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $category->id == old('category_id') ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="subcategory_id">{{ __('Sub Category') }} <span
                                    class="text-danger">*</span></label>
                            <select id="subcategory_id" name="subcategory_id"
                                class="select2 form-select @error('subcategory_id') is-invalid @enderror">
                                <option value="" selected disabled>{{ __('Select Sub Category') }}</option>
                                @if (isset($subcategories) && count($subcategories) > 0)
                                    @foreach ($subcategories as $subcategory)
                                        <option value="{{ $subcategory->id }}"
                                            {{ $subcategory->id == old('subcategory_id') ? 'selected' : '' }}>
                                            {{ $subcategory->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="mrp" class="form-label">MRP <span class="text-danger">*</span></label>
                            <input type="number" min="0" class="form-control @error('mrp') is-invalid @enderror"
                                id="mrp" name="mrp" value="{{ old('mrp') }}" required>
                            @error('mrp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                            <input type="number" min="0" class="form-control @error('price') is-invalid @enderror"
                                id="price" name="price" value="{{ old('price') }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="no_of_stock" class="form-label">No of Stock <span
                                    class="text-danger">*</span></label>
                            <input type="number" min="0"
                                class="form-control @error('no_of_stock') is-invalid @enderror" id="no_of_stock"
                                name="no_of_stock" value="{{ old('no_of_stock') }}" required>
                            @error('no_of_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="select2Primary">{{ __('Sizes') }} <span
                                    class="text-danger">*</span></label>
                            <div class="select2-primary">
                                <select id="select2Primary" name="size_id[]" class="select2 form-select" multiple>
                                    @if (isset($sizeAttributes->attributeValues) && count($sizeAttributes->attributeValues) > 0)
                                        @foreach ($sizeAttributes->attributeValues as $size)
                                            <option value="{{ $size->id }}"
                                                {{ is_array(old('size_id')) && in_array($size->id, old('size_id')) ? 'selected' : '' }}>
                                                {{ $size->value }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
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
                    <div class="col-md-12">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="color_id">{{ __('Color') }} <span
                                        class="text-danger">*</span></label>
                                <button id="adsMoreBtn"
                                    class="form-label add-new btn btn-warning btn-sm waves-effect waves-light">
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                                        class="d-none d-sm-inline-block">{{ __('Add More') }}</span>
                                </button>
                            </div>
                            <div class="row colorItemContainer">
                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <div class="select2-primary">
                                            <select id="color_id" name="color_id[]" class="select2 form-select">
                                                <option value="" selected disabled>{{ __('Select Color') }}</option>
                                                @if (isset($colorAttributes->attributeValues) && count($colorAttributes->attributeValues) > 0)
                                                    @foreach ($colorAttributes->attributeValues as $color)
                                                        <option value="{{ $color->id }}"
                                                            {{ is_array(old('color_id')) && in_array($color->id, old('color_id')) ? 'selected' : '' }}>
                                                            {{ $color->value }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                                            accept="image/*" id="image" name="image[]">
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex align-items-center justify-content-end">
                                    <button type="button" class="btn btn-danger btn-sm removeColorBtn">Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">Add Kit Product</button>
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

            function toggleRemoveButtons() {
                const total = $('.colorItemContainer').length;
                if (total <= 1) {
                    $('.removeColorBtn').addClass('d-none');
                } else {
                    $('.removeColorBtn').removeClass('d-none');
                }
            }

            $('#adsMoreBtn').on('click', function(e) {
                e.preventDefault();

                let colorBlock = `
                <div class="row colorItemContainer mt-3">
                    <div class="col-md-5">
                        <div class="mb-3">
                            <div class="select2-primary">
                                <select name="color_id[]" class="select2 form-select">
                                    <option value="" selected disabled>Select Color</option>
                                    @foreach ($colorAttributes->attributeValues as $color)
                                        <option value="{{ $color->id }}">{{ $color->value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="mb-3">
                            <input type="file" class="form-control" accept="image/*" name="image[]">
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-center justify-content-end">
                        <button type="button" class="btn btn-danger btn-sm removeColorBtn">Remove</button>
                    </div>
                </div>`;

                $('.colorItemContainer').last().after(colorBlock);
                $('.select2').select2(); // reinitialize if needed
                toggleRemoveButtons();
            });

            $(document).on('click', '.removeColorBtn', function() {
                $(this).closest('.colorItemContainer').remove();
                toggleRemoveButtons();
            });

            toggleRemoveButtons();
        });
    </script>
@endsection
