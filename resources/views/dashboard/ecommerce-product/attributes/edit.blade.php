@extends('layouts.master')

@section('title', __('Edit Attribute'))

@section('css')
    <style>
    </style>
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.ecommerce-products.index') }}">{{ __('Ecommerce Products') }}</a>
    </li>
    <li class="breadcrumb-item"><a
            href="{{ route('dashboard.ecommerce-product-attributes.index', $ecommerceAttribute->product_id) }}">{{ __('Attributes') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Edit Attribute') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Categories List Table -->

        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Edit Ecommerce Product Attribute</h5>
            </div>
            <form action="{{ route('dashboard.ecommerce-product-attributes.update', $ecommerceAttribute->id) }}"
                method="POST" enctype="multipart/form-data">
                <div class="card-body row">
                    @csrf
                    @method('PUT')
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Size <span class="text-danger">*</span></label>
                            <select class="form-select select2 @error('size_value_id') is-invalid @enderror"
                                name="size_value_id" id="size_value_id" required>
                                <option value="" selected disabled>Select Value</option>
                                @foreach ($sizeAttributes->attributeValues as $sz)
                                    <option value="{{ $sz->id }}"
                                        {{ $ecommerceAttribute->size_value_id == $sz->id ? 'selected' : '' }}>
                                        {{ $sz->value }}</option>
                                @endforeach
                            </select>
                            @error('size_value_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="slug" class="form-label">Color <span class="text-danger">*</span></label>
                            <select class="form-select select2" name="color_value_id" id="color_value_id" required>
                                <option value="" selected disabled>Select Value</option>
                                @foreach ($colorAttributes->attributeValues as $cl)
                                    <option value="{{ $cl->id }}" style="background-color: {{ $cl->value }}"
                                        {{ $ecommerceAttribute->color_value_id == $cl->id ? 'selected' : '' }}>
                                        {{ $cl->value }}</option>
                                @endforeach
                            </select>
                            @error('color_value_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price"
                                name="price" value="{{ old('price', $ecommerceAttribute->price) }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="quantity" class="form-label">No. of Stocks <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                                id="quantity" name="quantity" value="{{ old('quantity', $ecommerceAttribute->quantity) }}"
                                required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="images" class="form-label">Images (Image resolution must be 350px × 300px)</label>
                            <input type="file" class="form-control @error('images') is-invalid @enderror"
                                accept="image/*" id="images" name="images[]" multiple>
                            @if (isset($ecommerceAttribute->images) && count($ecommerceAttribute->images) > 0)
                                <div class="mt-3">
                                    @foreach ($ecommerceAttribute->images as $imageItem)
                                        <img style="height: 35px;" src="{{ asset('storage/' . $imageItem->image) }}"
                                            alt="">
                                    @endforeach
                                </div>
                            @endif
                            @error('images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">Edit Attribute</button>
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
