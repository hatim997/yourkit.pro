@extends('admin.master')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">

            <div class="mb-3 d-flex justify-content-between">
                <h1 class="h3 d-inline align-middle">Settings</h1>
            </div>

            <div class="row">

                <div class="card">
                    <div class="card-body">
                        <div class="col-12 col-lg-12 col-xxl-12">

                            <div class="table-responsive comon-table1">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Setting Name</th>
                                            <th>Value</th>
                                            <th colspan="3">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($settings))
                                            @foreach ($settings as $setting)
                                                <tr>
                                                    <td>{{ $setting->display_name }}</td>
                                                    <td>
                                                        @if ($setting->input_type == 'file' && !is_null($setting->value) && file_exists(public_path('storage/' . $setting->value)))
                                                                <img src="{{ url('storage/' . $setting->value) }}"
                                                                alt="" height="50px" width="100px">
                                                        @endif

                                                      @if(in_array($setting->input_type, ['text', 'textarea', 'select']))
                                                            {!! $setting->value ?? '' !!}
                                                        @endif
                                                    </td>

                                                    <td style="width: 120px">
                                                        <div class='btn-group'>
                                                            <a href="{{ route('admin.settings.edit', [$setting->id]) }}"
                                                                class='btn btn-primary btn-xs me-2'>
                                                                <i class="far fa-edit"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection
