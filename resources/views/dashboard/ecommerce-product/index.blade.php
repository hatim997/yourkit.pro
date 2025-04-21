@extends('layouts.master')

@section('title', __('Ecommerce Products'))

@section('css')
    <style>
    </style>
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Ecommerce Products') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Ecommerce Product List Table -->
        <div class="card">
            <div class="card-header">
                @canany(['create ecommerce product'])
                    <a href="{{ route('dashboard.ecommerce-products.create') }}"
                        class="add-new btn btn-primary waves-effect waves-light">
                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                            class="d-none d-sm-inline-block">{{ __('Add New Ecommerce Product') }}</span>
                    </a>
                @endcan
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top custom-datatables">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Category') }}</th>
                            <th>{{ __('Sub Category') }}</th>
                            <th>{{ __('Created Date') }}</th>
                            <th>{{ __('Status') }}</th>
                            @canany(['delete ecommerce product', 'update ecommerce product','view ecommerce product attribute'])<th>{{ __('Action') }}</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ecomProducts as $index => $product)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->category->name ?? 'No Category' }}</td>
                                <td>{{ $product->subcategory->name ?? 'No Sub Category' }}</td>
                                <td>{{ $product->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <span
                                        class="badge me-4 bg-label-{{ $product->status == 1 ? 'success' : 'danger' }}">{{ $product->status == 1 ? 'Active' : 'Inactive' }}</span>
                                </td>
                                @canany(['delete ecommerce product', 'update ecommerce product','view ecommerce product attribute'])
                                    <td class="d-flex">
                                        @canany(['delete ecommerce product'])
                                            <form action="{{ route('dashboard.ecommerce-products.destroy', $product->id) }}"
                                                method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <a href="#" type="submit"
                                                    class="btn btn-icon btn-text-danger waves-effect waves-light rounded-pill delete-record delete_confirmation"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete Ecommerce Product') }}">
                                                    <i class="ti ti-trash ti-md"></i>
                                                </a>
                                            </form>
                                        @endcan
                                        @canany(['update ecommerce product'])
                                            <span class="text-nowrap">
                                                <a href="{{ route('dashboard.ecommerce-products.edit', $product->id) }}"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Edit Ecommerce Product') }}">
                                                    <i class="ti ti-edit ti-md"></i>
                                                </a>
                                            </span>
                                        @endcan
                                        @canany(['view ecommerce product attribute'])
                                            <span class="text-nowrap">
                                                <a href="{{ route('dashboard.ecommerce-product-attributes.index', $product->id) }}"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('View Ecommerce Product Attributes') }}">
                                                    <i class="ti ti-adjustments ti-md"></i>
                                                </a>
                                            </span>
                                        @endcan
                                    </td>
                                @endcan
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script></script>
@endsection
