@extends('layouts.master')

@section('title', __('Promo Codes'))

@section('css')
    <style>
    </style>
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Promo Codes') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Promo Codes List Table -->
        <div class="card">
            <div class="card-header">
                @canany(['create promo code'])
                    <a href="{{route('dashboard.promo-codes.create')}}" class="add-new btn btn-primary waves-effect waves-light">
                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                            class="d-none d-sm-inline-block">{{ __('Add New Promo Code') }}</span>
                    </a>
                @endcan
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top custom-datatables">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Code') }}</th>
                            <th>{{ __('Discount (%)') }}</th>
                            <th>{{ __('Valid From') }}</th>
                            <th>{{ __('Valid Until') }}</th>
                            <th>{{ __('Usage Limit') }}</th>
                            <th>{{ __('Used Count') }}</th>
                            <th>{{ __('Status') }}</th>
                            @canany(['delete promo code', 'update promo code'])<th>{{ __('Action') }}</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($promoCodes as $index => $promoCode)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $promoCode->name }}</td>
                                <td>{{ $promoCode->code }}</td>
                                <td>{{ $promoCode->discount_percentage }}%</td>
                                <td>{{ \Carbon\Carbon::parse($promoCode->valid_from)->format('Y-m-d') }}</td>
                                <td>{{ \Carbon\Carbon::parse($promoCode->valid_until)->format('Y-m-d') }}</td>
                                <td>{{ $promoCode->usage_limit }}</td>
                                <td>{{ $promoCode->used_count }}</td>
                                <td>
                                    <span
                                        class="badge me-4 bg-label-{{ $promoCode->status == 1 ? 'success' : 'danger' }}">{{ $promoCode->status == 1 ? 'Active' : 'Inactive' }}</span>
                                </td>
                                @canany(['delete promo code', 'update promo code'])
                                    <td class="d-flex">
                                        @canany(['delete promo code'])
                                            <form action="{{ route('dashboard.promo-codes.destroy', $promoCode->id) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <a href="#" type="submit"
                                                    class="btn btn-icon btn-text-danger waves-effect waves-light rounded-pill delete-record delete_confirmation"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete Promo Code') }}">
                                                    <i class="ti ti-trash ti-md"></i>
                                                </a>
                                            </form>
                                        @endcan
                                        @canany(['update promo code'])
                                            <span class="text-nowrap">
                                                <a href="{{route('dashboard.promo-codes.edit', $promoCode->id)}}"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit Promo Code') }}">
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
