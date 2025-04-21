@extends('layouts.master')

@section('title', __('Pages'))

@section('css')
    <style>
    </style>
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Pages') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Pages List Table -->
        <div class="card">
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top custom-datatables">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.') }}</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Created Date') }}</th>
                            <th>{{ __('Status') }}</th>
                            @canany(['update page'])<th>{{ __('Action') }}</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pages as $index => $page)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $page->name }}</td>
                                <td>{{ $page->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <span
                                        class="badge me-4 bg-label-{{ $page->status == 1 ? 'success' : 'danger' }}">{{ $page->status == 1 ? 'Active' : 'Inactive' }}</span>
                                </td>
                                @canany(['update page'])
                                    <td class="d-flex">
                                        @canany(['update page'])
                                            <span class="text-nowrap">
                                                <a href="{{route('dashboard.pages.edit', $page->id)}}"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit Page Content') }}">
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
