@extends('layouts.master')

@section('title', __('Edit Kit Product'))

@section('css')
    <style>
    </style>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">{{ __('Kit Products') }}</li>
    <li class="breadcrumb-item active">{{ __('Edit') }}</li>
@endsection
{{-- @dd($kitProduct->color) --}}
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Edit Kit Product</h5>
            </div>
            <form action="{{ route('dashboard.kit-products.update', $kitProduct->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name', $kitProduct->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="slug" class="form-label">Product Slug <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug"
                                name="slug" value="{{ old('slug', $kitProduct->slug) }}" required>
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
                                        {{ $kitProduct->category_id == $category->id ? 'selected' : '' }}>
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
                                        {{ $kitProduct->subcategory_id == $subcategory->id ? 'selected' : '' }}>
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
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{ old('description', $kitProduct->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Prices and Stock -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="mrp" class="form-label">MRP <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('mrp') is-invalid @enderror" id="mrp"
                                name="mrp" value="{{ old('mrp', $kitProduct->mrp) }}" required>
                            @error('mrp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price"
                                name="price" value="{{ old('price', $kitProduct->price) }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="no_of_stock" class="form-label">No of Stock <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('no_of_stock') is-invalid @enderror"
                                id="no_of_stock" name="no_of_stock"
                                value="{{ old('no_of_stock', $kitProduct->no_of_stock) }}" required>
                            @error('no_of_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Sizes -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="size_id">Sizes <span class="text-danger">*</span></label>
                            <select id="size_id" name="size_id[]" class="select2 form-select" multiple>
                                @foreach ($sizeAttributes->attributeValues as $size)
                                    <option value="{{ $size->id }}"
                                        {{ in_array($size->id, $productSizeAttributes ?? []) ? 'selected' : '' }}>
                                        {{ $size->value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select id="status" name="status" class="select2 form-select">
                                <option value="1" {{ $kitProduct->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $kitProduct->status == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <!-- Colors and Images -->
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label">Colors & Images</label>
                            <div class="row colorItemContainer">
                                @foreach ($kitProduct->color as $index => $color)
                                    <div class="row colorItemContainer mt-3" data-id="{{ $color->pivot->id }}">
                                        <div class="col-md-5">
                                            <select class="form-select" disabled>
                                                @foreach ($colorAttributes->attributeValues as $clr)
                                                    <option value="{{ $clr->id }}"
                                                        {{ $clr->id == $color->pivot->value ? 'selected' : '' }}>{{ $clr->value_name }} ({{ $clr->value }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            @if ($color->pivot->image)
                                                <img src="{{ asset('storage/' . $color->pivot->image) }}"
                                                    alt="Color Image" class="img-thumbnail" style="height: 60px;">
                                            @endif
                                        </div>
                                        <div class="col-md-2 d-flex align-items-center justify-content-end">
                                            <button href="#" type="button"
                                                class="btn btn-danger btn-sm removeColorAjaxBtn"
                                                data-id="{{ $color->pivot->id }}">Remove</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button id="adsMoreBtn" type="button" class="btn btn-warning btn-sm mt-2 float-end">Add
                                More</button>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">Update Kit Product</button>
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

            $('#adsMoreBtn').on('click', function(e) {
                e.preventDefault();
                const newColorBlock = `
                    <div class="row colorItemContainer mt-3">
                        <div class="col-md-5">
                            <select name="color_id[]" class="select2 form-select">
                                <option disabled selected>Select Color</option>
                                @foreach ($colorAttributes->attributeValues as $color)
                                    <option value="{{ $color->id }}">{{ $color->value_name }} ({{ $color->value }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <input type="file" name="image[]" class="form-control" accept="image/*"/>
                        </div>
                        <div class="col-md-2 d-flex align-items-center justify-content-end">
                            <button type="button" class="btn btn-danger btn-sm removeColorBtn">Remove</button>
                        </div>
                    </div>`;
                $(this).before(newColorBlock);
                $('.select2').select2();
            });

            $(document).on('click', '.removeColorBtn', function() {
                $(this).closest('.colorItemContainer').remove();
            });
        });

        $(document).on('click', '.removeColorAjaxBtn', function(event) {
            event.preventDefault();
            let colorRow = $(this).closest('.colorItemContainer');
            let id = $(this).data('id');
            Swal.fire({
                title: "Are you sure?",
                text: "You would not be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Cancel',
                confirmButtonText: 'Yes, delete it!',
                customClass: {
                    confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                    cancelButton: 'btn btn-label-secondary waves-effect waves-light'
                },
                buttonsStyling: false
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/dashboard/kit-products/color-attribute/delete/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            if (res.success) {
                                colorRow.remove();
                                Swal.fire({
                                    title: 'Success!',
                                    text: res.message,
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: "Something went wrong! Please try again later",
                                    icon: 'error',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Error!',
                                text: "Something went wrong! Please try again later",
                                icon: 'error',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: "Your data is safe!",
                        icon: "info",
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });
        });
    </script>
@endsection
