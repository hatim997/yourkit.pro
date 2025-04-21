@extends('layouts.master')

@section('title', __('Contact Messages'))

@section('css')
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Contact Messages') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Contact Messages List Table -->
        <div class="card">
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top custom-datatables">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Message') }}</th>
                            <th>{{ __('Received Date') }}</th>
                            @canany(['view contact message'])<th>{{ __('Action') }}</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contacts as $index => $contact)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $contact->name }}</td>
                                <td>{{ $contact->email }}</td>
                                <td>{{ $contact->phone }}</td>
                                <td>{{ \Illuminate\Support\Str::limit(strip_tags($contact->message), 35, '...') }}</td>
                                <td>{{ $contact->created_at->format('Y-m-d') }}</td>
                                @canany(['view contact message'])
                                    <td class="d-flex">
                                        @canany(['view contact message'])
                                            <span class="text-nowrap">
                                                <a href="{{route('dashboard.contact-messages.show', $contact->id)}}"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('View Message') }}">
                                                    <i class="ti ti-eye ti-md"></i>
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
