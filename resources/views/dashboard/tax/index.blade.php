@extends('layouts.master')

@section('title', __('Taxes'))

@section('css')
    <style>
    </style>
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Taxes') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Taxes List Table -->
        <div class="card">
            {{-- <div class="card-header">
                @canany(['create category'])
                    <a href="{{route('dashboard.taxes.create')}}" class="add-new btn btn-primary waves-effect waves-light">
                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                            class="d-none d-sm-inline-block">{{ __('Add New Category') }}</span>
                    </a>
                @endcan
            </div> --}}
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top custom-datatables">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.') }}</th>
                            <th>{{ __('Tax Type') }}</th>
                            <th>{{ __('Tax Code') }}</th>
                            <th>{{ __('Percentage') }}</th>
                            <th>{{ __('Created Date') }}</th>
                            <th>{{ __('Status') }}</th>
                            @canany(['update tax'])<th>{{ __('Action') }}</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($taxes as $index => $tax)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $tax->tax_type }}</td>
                                <td>{{ $tax->tax_code }}</td>
                                <td>{{ $tax->percentage }}</td>
                                <td>{{ $tax->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <span
                                        class="badge me-4 bg-label-{{ $tax->status == 1 ? 'success' : 'danger' }}">{{ $tax->status == 1 ? 'Active' : 'Inactive' }}</span>
                                </td>
                                @canany(['update tax'])
                                    <td class="d-flex">
                                        @canany(['update tax'])
                                            <span class="text-nowrap">
                                                <a href="{{route('dashboard.taxes.edit', $tax->id)}}"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit Tax') }}">
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
