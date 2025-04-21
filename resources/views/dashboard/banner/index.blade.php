@extends('layouts.master')

@section('title', __('Banners'))

@section('css')
    <style>
    </style>
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Banners') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Banners List Table -->
        <div class="card">
            <div class="card-header">
                @canany(['create banner'])
                    <a href="{{route('dashboard.banners.create')}}" class="add-new btn btn-primary waves-effect waves-light">
                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                            class="d-none d-sm-inline-block">{{ __('Add New Banner') }}</span>
                    </a>
                @endcan
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top custom-datatables">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.') }}</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('Created Date') }}</th>
                            <th>{{ __('Status') }}</th>
                            @canany(['delete banner', 'update banner'])<th>{{ __('Action') }}</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($banners as $index => $banner)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $banner->title }}</td>
                                <td>{{ ucfirst($banner->type) }}</td>
                                <td>{{ $banner->description }}</td>
                                <td><img style="height: 35px;" src="{{ asset('storage/' . $banner->media->path) }}" alt="Banner Image"></td>
                                <td>{{ $banner->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <span
                                        class="badge me-4 bg-label-{{ $banner->status == 1 ? 'success' : 'danger' }}">{{ $banner->status == 1 ? 'Active' : 'Inactive' }}</span>
                                </td>
                                @canany(['delete banner', 'update banner'])
                                    <td class="d-flex">
                                        @canany(['delete banner'])
                                            <form action="{{ route('dashboard.banners.destroy', $banner->id) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <a href="#" type="submit"
                                                    class="btn btn-icon btn-text-danger waves-effect waves-light rounded-pill delete-record delete_confirmation"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete Banner') }}">
                                                    <i class="ti ti-trash ti-md"></i>
                                                </a>
                                            </form>
                                        @endcan
                                        @canany(['update banner'])
                                            <span class="text-nowrap">
                                                <a href="{{route('dashboard.banners.edit', $banner->id)}}"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit Banner') }}">
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
