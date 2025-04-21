@extends('layouts.master')

@section('title', __('Create Product Bundle'))

@section('css')
    <style>
    </style>
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item">{{ __('Kit Product Bundles') }}</li>
    <li class="breadcrumb-item active">{{ __('Create') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Categories List Table -->

        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Add New Kit Product Bundle</h5>
            </div>
            <form action="{{ route('dashboard.kit-product-bundles.store') }}" method="POST" enctype="multipart/form-data">
                <div class="card-body row">
                    @csrf
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="name" class="form-label">Bundle Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="price" class="form-label">Bundle Price <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('price') is-invalid @enderror" id="price"
                                name="price" value="{{ old('price') }}" required>
                            @error('price')
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
                    <div class="col-md-12">
                        <div class="mb-3">
                            <table class="table table-sm" id="bundleTable">
                                <thead>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    <tr id="row-0">
                                        <td>
                                            <select name="products[0][product_id]"
                                                class="form-select select2 @error('product_id') is-invalid @enderror " required>
                                                <option value="" selected disabled>Select Value</option>
                                                @foreach ($products as $j => $product)
                                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" name="products[0][quantity]"
                                                min="1" required>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-xs btn-warning" id="addRow"><i
                                                    class="ti ti-plus me-0 me-sm-1 ti-xs"></i> Add</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" accept="image/*"
                                id="image" name="image">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">Add Product Bundle</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#addRow').click(function() {
                counter = $('#bundleTable').length;
                let lastTrId = $('#bundleTable tr:last').attr('id');
                lastTrId = lastTrId.substring(4);
                counter = parseInt(lastTrId) + 1
                var optionsHtml = $('#bundleTable tbody>tr:first select').html();

                console.log('options', optionsHtml)

                html = '<tr id="row-' + counter + '">' +
                    '<td><select name="products[' + counter +
                    '][product_id]" class="form-select select2" required=""><option value="" selected disabled>Select Value</option>' +
                    optionsHtml +
                    '</select></td><td><input type="number" class="form-control" name="products[' +
                    counter + '][quantity]" min="1" required=""></td>' + '<td><button type="button"' +
                    'class="btn btn-danger btn-xs" onclick="removeRow(' + counter +
                    ')"><i class="fa fa-minus"></i></button></td>'

                '</tr>';

                $('#bundleTable tbody').append(html);
                $('.select2').select2();
                $('#bundleTable tr:last select').prop('selectedIndex', 0);

            });


        });

        function removeRow(row) {
            $(`#row-${row}`).remove();
        }
    </script>
@endsection
