@extends('layouts.master')

@section('title', __('Create Ecommerce Products'))

@section('css')
    <style>
    </style>
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item">{{ __('Ecommerce Products') }}</li>
    <li class="breadcrumb-item active">{{ __('Create') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Categories List Table -->

        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Add New Ecommerce Product</h5>
            </div>
            <form action="{{ route('dashboard.ecommerce-products.store') }}" method="POST" enctype="multipart/form-data">
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
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="size_chart" class="form-label">Size Chart <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('size_chart') is-invalid @enderror"
                                id="size_chart" name="size_chart" value="{{ old('size_chart') }}" required>
                            @error('size_chart')
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
                    <div class="col-12 col-lg-12">
                        <table class="table table-stripped" id="variantTable">
                            <thead>
                                <th>Size</th>
                                <th>Color</th>
                                <th>Price</th>
                                <th>No. of stocks</th>
                                <th>Image <small class="text-muted">(Image resolution must be 350px × 300px)</small></th>
                                <th></th>
                            </thead>
                            <tbody>
                                <tr id="row_0">
                                    <td>
                                        <select class="form-select select2" name="attribute[0][size]" id="size_0"
                                            required="">
                                            <option value="" selected disabled>Select Value</option>
                                            @foreach ($sizeAttributes->attributeValues as $sz)
                                                <option value="{{ $sz->id }}">{{ $sz->value }}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td>
                                        <select class="form-select select2" name="attribute[0][color]" id="color_0"
                                            required="">
                                            <option value="" selected disabled>Select Value</option>
                                            @foreach ($colorAttributes->attributeValues as $cl)
                                                <option value="{{ $cl->id }}"
                                                    style="background-color: {{ $cl->value }}">
                                                    {{ $cl->value }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input class="form-control" type="number" name="attribute[0][price]" required>
                                    </td>
                                    <td><input class="form-control" type="number" name="attribute[0][quantity]"
                                            required></td>
                                    <td><input class="form-control" type="file" name="attribute[0][image][]"
                                            accept="image/*" multiple></td>
                                    <td><button type="button" class="btn btn-warning btn-sm addRow"><i
                                                class="ti ti-plus me-0 me-sm-1 ti-xs"></i> Add</button></td>
                                </tr>
                            </tbody>
                        </table>
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
                            <div class="row discount-group">
                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Minimum Quantity') }}</label>
                                        <input type="number" min="0" class="form-control" name="quantity[]">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Discount Percentage') }}</label>
                                        <input type="number" min="0" max="100" class="form-control" name="discount_percentage[]">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-3">
                        <button type="submit" class="btn btn-primary">Add Ecommerce Product</button>
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

            const sizes = JSON.parse(`<?php echo isset($sizeAttributes->attributeValues) ? $sizeAttributes->attributeValues : json_encode([]); ?>`);
            const colors = JSON.parse(`<?php echo isset($colorAttributes->attributeValues) ? $colorAttributes->attributeValues : json_encode([]); ?>`);

            let counter = 1;
            let sizeOption = '';
            let colorOption = '';

            $.each(sizes, function(sz, size) {
                sizeOption += '<option value="' + size.id + '">' + size.value + '</option>';
            });

            $.each(colors, function(cl, color) {
                colorOption += '<option value="' + color.id + '"style="background-color: ' + color.value +
                    '">' + color.value + '</option>';
            });

            $('#variantTable').on('click', '.removeRow', function() {
                $(this).closest('tr').remove();
            });

            $('.addRow').on('click', function() {
                let html = '<tr id="row_' + counter +
                    '"><td><select class="form-select select2" name="attribute[' + counter +
                    '][size]" id="size_' +
                    counter + '" required=""><option value="">Select Value</option>' + sizeOption +
                    '</select></td><td><select class="form-select select2" name="attribute[' + counter +
                    '][color]" id="color_' + counter +
                    '" required=""><option value="">Select Value</option>' + colorOption +
                    '</select></td><td><input class="form-control" type="number" name="attribute[' +
                    counter +
                    '][price]" required></td><td><input class="form-control" type="number" name="attribute[' +
                    counter +
                    '][quantity]" required></td><td><input class="form-control" type="file" name="attribute[' +
                    counter +
                    '][image][]" accept="image/*" multiple></td><td><button type="button" class="btn btn-danger removeRow"><i class="fa fa-minus"></i></button></td></tr>';
                $('#variantTable tbody').append(html);
                $('.select2').select2();
                counter++;
            })
        });
        $('#variantTable').on('click', '.removeRow', function() {
            let row = $(this).closest('tr');
            let deletedId = row.find("input[name^='attribute'][name$='[id]']").val();

            if (deletedId) {

                $('<input>').attr({
                    type: 'hidden',
                    name: 'deleted_attributes[]',
                    value: deletedId
                }).appendTo('#variantTable');
            }

            row.remove();
        });

        $('#addMoreDiscountBtn').on('click', function() {
            // Append a new row
            $('#discountFieldsContainer').append(`
                <div class="row discount-group">
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
                    <div class="col-md-2 d-flex align-items-center">
                        <button type="button" class="btn btn-danger btn-sm remove-discount">Remove</button>
                    </div>
                </div>
            `);

            updateRemoveButtons();
        });

        // Remove discount group
        $('#discountFieldsContainer').on('click', '.remove-discount', function() {
            $(this).closest('.discount-group').remove();
            updateRemoveButtons();
        });

        // Show/remove buttons based on count
        function updateRemoveButtons() {
            const allGroups = $('#discountFieldsContainer .discount-group');

            // Remove any existing remove buttons
            allGroups.find('.remove-discount').parent().remove();

            if (allGroups.length > 1) {
                // Add Remove button to each group
                allGroups.each(function() {
                    $(this).append(`
                <div class="col-md-2 d-flex align-items-center">
                    <button type="button" class="btn btn-danger btn-sm remove-discount">Remove</button>
                </div>
            `);
                });
            }
        }
    </script>
@endsection
