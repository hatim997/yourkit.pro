@extends('layouts.master')

@section('title', __('Attributes'))

@section('css')
    <style>
    </style>
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Attributes') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Attributes List Table -->
        <div class="card">
            {{-- <div class="card-header">
                @canany(['create attribute'])
                    <a href="{{route('dashboard.attribute.create')}}" class="add-new btn btn-primary waves-effect waves-light">
                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                            class="d-none d-sm-inline-block">{{ __('Add New Attribute') }}</span>
                    </a>
                @endcan
            </div> --}}
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top custom-datatables">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Created Date') }}</th>
                            <th>{{ __('Status') }}</th>
                            @canany(['delete attribute value'])<th>{{ __('Action') }}</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attributes as $index => $attribute)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $attribute->type }}</td>
                                <td>{{ $attribute->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <span
                                        class="badge me-4 bg-label-{{ $attribute->status == 1 ? 'success' : 'danger' }}">{{ $attribute->status == 1 ? 'Active' : 'Inactive' }}</span>
                                </td>
                                @canany(['view attribute value'])
                                    <td class="d-flex">
                                        @canany(['view attribute value'])
                                            <a href="{{ route('dashboard.attributes.show', $attribute->id) }}" class="btn btn-icon btn-text-warning waves-effect waves-light rounded-pill me-1"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('View Attribute Values') }}">
                                                <i class="ti ti-eye ti-md"></i>
                                            </a>
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
