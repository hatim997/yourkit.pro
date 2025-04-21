@extends('layouts.master')

@section('title', __('Sub Categories'))

@section('css')
    <style>
    </style>
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Sub Categories') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Categories List Table -->
        <div class="card">
            <div class="card-header">
                @canany(['create sub category'])
                    <a href="{{route('dashboard.sub-categories.create')}}" class="add-new btn btn-primary waves-effect waves-light">
                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                            class="d-none d-sm-inline-block">{{ __('Add New Sub Category') }}</span>
                    </a>
                @endcan
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top custom-datatables">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Slug') }}</th>
                            <th>{{ __('Category') }}</th>
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('Created Date') }}</th>
                            <th>{{ __('Status') }}</th>
                            @canany(['delete sub category', 'update sub category'])<th>{{ __('Action') }}</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subCategories as $index => $subCategory)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $subCategory->name }}</td>
                                <td>{{ $subCategory->slug }}</td>
                                <td>{{ isset($subCategory->category) ? $subCategory->category->name : 'None' }}</td>
                                <td><img style="height: 35px;" src="{{ asset('storage/' . $subCategory->media->path) }}" alt=""></td>
                                <td>{{ $subCategory->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <span
                                        class="badge me-4 bg-label-{{ $subCategory->status == 1 ? 'success' : 'danger' }}">{{ $subCategory->status == 1 ? 'Active' : 'Inactive' }}</span>
                                </td>
                                @canany(['delete sub category', 'update sub category'])
                                    <td class="d-flex">
                                        @canany(['delete sub category'])
                                            <form action="{{ route('dashboard.sub-categories.destroy', $subCategory->id) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <a href="#" type="submit"
                                                    class="btn btn-icon btn-text-danger waves-effect waves-light rounded-pill delete-record delete_confirmation"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete Sub Category') }}">
                                                    <i class="ti ti-trash ti-md"></i>
                                                </a>
                                            </form>
                                        @endcan
                                        @canany(['update sub category'])
                                            <span class="text-nowrap">
                                                <a href="{{route('dashboard.sub-categories.edit', $subCategory->id)}}"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit SUb Category') }}">
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
