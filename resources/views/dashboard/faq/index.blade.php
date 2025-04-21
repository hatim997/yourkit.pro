@extends('layouts.master')

@section('title', __('FAQs'))

@section('css')
    <style>
    </style>
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('FAQs') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- FAQs List Table -->
        <div class="card">
            <div class="card-header">
                @canany(['create faq'])
                    <a href="{{route('dashboard.faqs.create')}}" class="add-new btn btn-primary waves-effect waves-light">
                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                            class="d-none d-sm-inline-block">{{ __('Add New FAQ') }}</span>
                    </a>
                @endcan
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top custom-datatables">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.') }}</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Created Date') }}</th>
                            <th>{{ __('Status') }}</th>
                            @canany(['delete faq', 'update faq'])<th>{{ __('Action') }}</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($faqs as $index => $faq)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $faq->title }}</td>
                                <td>{{ \Illuminate\Support\Str::limit(strip_tags($faq->description), 35, '...') }}</td>
                                <td>{{ $faq->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <span
                                        class="badge me-4 bg-label-{{ $faq->status == 1 ? 'success' : 'danger' }}">{{ $faq->status == 1 ? 'Active' : 'Inactive' }}</span>
                                </td>
                                @canany(['delete faq', 'update faq'])
                                    <td class="d-flex">
                                        @canany(['delete faq'])
                                            <form action="{{ route('dashboard.faqs.destroy', $faq->id) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <a href="#" type="submit"
                                                    class="btn btn-icon btn-text-danger waves-effect waves-light rounded-pill delete-record delete_confirmation"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete FAQ') }}">
                                                    <i class="ti ti-trash ti-md"></i>
                                                </a>
                                            </form>
                                        @endcan
                                        @canany(['update faq'])
                                            <span class="text-nowrap">
                                                <a href="{{route('dashboard.faqs.edit', $faq->id)}}"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit FAQ') }}">
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
