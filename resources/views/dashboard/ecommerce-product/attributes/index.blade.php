@extends('layouts.master')

@section('title', __('Attributes'))

@section('css')
    <style>
    </style>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.ecommerce-products.index') }}">{{ __('Ecommerce Products') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Attributes') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Attributes List Table -->
        <div class="card">
            <div class="card-header">
                @canany(['create ecommerce product attribute'])
                    <a href="{{ route('dashboard.ecommerce-product-attributes.create', $product->id) }}" class="add-new btn btn-primary waves-effect waves-light">
                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                            class="d-none d-sm-inline-block">{{ __('Add New Ecommerce Product Attribute') }}</span>
                    </a>
                @endcan
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top custom-datatables">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.') }}</th>
                            <th>{{ __('Size') }}</th>
                            <th>{{ __('Color') }}</th>
                            <th>{{ __('Price') }}</th>
                            <th>{{ __('Quantity') }}</th>
                            <th>{{ __('Images') }}</th>
                            <th>{{ __('Created Date') }}</th>
                            @canany(['delete ecommerce product attribute', 'update ecommerce product attribute'])<th>
                                {{ __('Action') }}</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ecommerceAttributes as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ isset($item->sizeValue) ? $item->sizeValue->value : 'No Size' }}</td>
                                <td>
                                    @if ($item->colorValue)
                                        <div class="d-flex">
                                            <div class="mx-3"
                                                style="width: 20px; height:20px; background-color:{{ $item->colorValue->value }};"></div>
                                            <span>{{ $item->colorValue->value }}</span>
                                        </div>
                                    @else
                                        No Color
                                    @endif
                                </td>
                                <td>{{ \App\Helpers\Helper::formatCurrency($item->price) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>
                                    @if ($item->images)
                                        @foreach ($item->images as $imageItem)
                                            <img style="height: 35px;" src="{{ asset('storage/' . $imageItem->image) }}" alt="">
                                        @endforeach
                                    @endif
                                </td>
                                <td>{{ $item->created_at->format('Y-m-d') }}</td>
                                @canany(['delete ecommerce product attribute', 'update ecommerce product attribute'])
                                    <td class="d-flex">
                                        @canany(['delete ecommerce product attribute'])
                                            <form action="{{ route('dashboard.ecommerce-product-attributes.destroy', $item->id) }}"
                                                method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <a href="#" type="submit"
                                                    class="btn btn-icon btn-text-danger waves-effect waves-light rounded-pill delete-record delete_confirmation"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete Ecommerce Product Attribute') }}">
                                                    <i class="ti ti-trash ti-md"></i>
                                                </a>
                                            </form>
                                        @endcan
                                        @canany(['update ecommerce product attribute'])
                                            <span class="text-nowrap">
                                                <a href="{{ route('dashboard.ecommerce-product-attributes.edit', $item->id) }}"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Edit Ecommerce Product Attribute') }}">
                                                    <i class="ti ti-edit ti-md"></i>
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
