@extends('layouts.master')

@section('title', __('Kit Products'))

@section('css')
    <style>
    </style>
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Kit Products') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Kit Product List Table -->
        <div class="card">
            <div class="card-header">
                @canany(['create kit product'])
                    <a href="{{route('dashboard.kit-products.create')}}" class="add-new btn btn-primary waves-effect waves-light">
                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                            class="d-none d-sm-inline-block">{{ __('Add New Kit Product') }}</span>
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
                            <th>{{ __('Price') }}</th>
                            <th>{{ __('Created Date') }}</th>
                            <th>{{ __('Status') }}</th>
                            @canany(['delete kit product', 'update kit product'])<th>{{ __('Action') }}</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kitProducts as $index => $product)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->category->name ?? 'No Category' }}</td>
                                <td>{{ $product->subcategory->name ?? 'No Sub Category' }}</td>
                                <td>{{ $product->price }}</td>
                                <td>{{ $product->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <span
                                        class="badge me-4 bg-label-{{ $product->status == 1 ? 'success' : 'danger' }}">{{ $product->status == 1 ? 'Active' : 'Inactive' }}</span>
                                </td>
                                @canany(['delete kit product', 'update kit product'])
                                    <td class="d-flex">
                                        @canany(['delete kit product'])
                                            <form action="{{ route('dashboard.kit-products.destroy', $product->id) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <a href="#" type="submit"
                                                    class="btn btn-icon btn-text-danger waves-effect waves-light rounded-pill delete-record delete_confirmation"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete Kit Product') }}">
                                                    <i class="ti ti-trash ti-md"></i>
                                                </a>
                                            </form>
                                        @endcan
                                        @canany(['update kit product'])
                                            <span class="text-nowrap">
                                                <a href="{{route('dashboard.kit-products.edit', $product->id)}}"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit Kit Product') }}">
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
    <script>
    </script>
@endsection
