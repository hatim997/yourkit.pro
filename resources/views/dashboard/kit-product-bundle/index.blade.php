@extends('layouts.master')

@section('title', __('Kit Product Bundles'))

@section('css')
    <style>
    </style>
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Kit Product Bundles') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Categories List Table -->
        <div class="card">
            <div class="card-header">
                @canany(['create kit product bundle'])
                    <a href="{{route('dashboard.kit-product-bundles.create')}}" class="add-new btn btn-primary waves-effect waves-light">
                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                            class="d-none d-sm-inline-block">{{ __('Add New Product Bundle') }}</span>
                    </a>
                @endcan
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top custom-datatables">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Price') }}</th>
                            <th>{{ __('Disc. (%)') }}</th>
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('Created Date') }}</th>
                            <th>{{ __('Status') }}</th>
                            @canany(['delete kit product bundle', 'update kit product bundle'])<th>{{ __('Action') }}</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bundles as $index => $bundle)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $bundle->name }}</td>
                                <td>{{ $bundle->price }}</td>
                                <td>{{ $bundle->discount_percentage }}%</td>
                                <td><img style="height: 35px;" src="{{ asset('storage/' . $bundle->image) }}" alt=""></td>
                                <td>{{ $bundle->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <span
                                        class="badge me-4 bg-label-{{ $bundle->status == 1 ? 'success' : 'danger' }}">{{ $bundle->status == 1 ? 'Active' : 'Inactive' }}</span>
                                </td>
                                @canany(['delete kit product bundle', 'update kit product bundle'])
                                    <td class="d-flex">
                                        @canany(['delete kit product bundle'])
                                            <form action="{{ route('dashboard.kit-product-bundles.destroy', $bundle->id) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <a href="#" type="submit"
                                                    class="btn btn-icon btn-text-danger waves-effect waves-light rounded-pill delete-record delete_confirmation"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete Product Bundle') }}">
                                                    <i class="ti ti-trash ti-md"></i>
                                                </a>
                                            </form>
                                        @endcan
                                        @canany(['update kit product bundle'])
                                            <span class="text-nowrap">
                                                <a href="{{route('dashboard.kit-product-bundles.edit', $bundle->id)}}"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit Product Bundle') }}">
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
