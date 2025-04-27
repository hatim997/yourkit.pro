@extends('layouts.master')

@section('title', __('Attribute Values'))

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <style>
    </style>
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.attributes.index') }}">{{ __('Attributes') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Attribute Values') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Attribute Values List Table -->
        <div class="card">
            <div class="card-header">
                @canany(['create attribute value'])
                    <button data-bs-toggle="modal" data-bs-target="#modalCenter"
                        class="add-new btn btn-primary waves-effect waves-light">
                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                            class="d-none d-sm-inline-block">{{ __('Add New Attribute Value') }}</span>
                    </button>
                @endcan
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top custom-datatables">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.') }}</th>
                            <th>{{ __('Value') }}</th>
                            @if ($attribute->id == '2')
                            <th>{{ __('Value Name') }}</th>
                            @endif
                            <th>{{ __('Attribute') }}</th>
                            <th>{{ __('Created Date') }}</th>
                            <th>{{ __('Status') }}</th>
                            @canany(['delete attribute value', 'update attribute value'])<th>{{ __('Action') }}</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attributeValues as $index => $value)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex">
                                        <div class="mx-3"
                                            style="width: 20px; height:20px; background-color:{{ $value->value }};"></div>
                                        <span>{{ $value->value }}</span>
                                    </div>
                                </td>
                                @if ($attribute->id == '2')
                                    <td>{{ $value->value_name }}</td>
                                @endif
                                <td>{{ $value->attribute->type }}</td>
                                <td>{{ $value->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <span
                                        class="badge me-4 bg-label-{{ $value->status == 1 ? 'success' : 'danger' }}">{{ $value->status == 1 ? 'Active' : 'Inactive' }}</span>
                                </td>
                                @canany(['delete attribute value', 'update attribute value'])
                                    <td class="d-flex">
                                        @canany(['delete attribute value'])
                                            <form action="{{ route('dashboard.attribute-values.destroy', $value->id) }}"
                                                method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <a href="#" type="submit"
                                                    class="btn btn-icon btn-text-danger waves-effect waves-light rounded-pill delete-record delete_confirmation"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete Attribute Value') }}">
                                                    <i class="ti ti-trash ti-md"></i>
                                                </a>
                                            </form>
                                        @endcan
                                        @canany(['update attribute value'])
                                            <span class="text-nowrap">
                                                <button data-bs-toggle="modal" data-bs-target="#modalCenterEdit"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1 editBtn"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" data-id="{{ $value->id }}"
                                                    data-value="{{ $value->value }}" data-value_name="{{ $value->value_name }}" title="{{ __('Edit Attribute Value') }}">
                                                    <i class="ti ti-edit ti-md"></i>
                                                </button>
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

        <!-- Add Value Modal -->
        @include('dashboard.attribute.values.sections.add-value')

        <!-- Update Value Modal -->
        @include('dashboard.attribute.values.sections.edit-value')
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script>
        @if ($attribute->id == '2')
            const nanoPicker = document.querySelector('#color-picker-nano');
            const nanoPickerEdit = document.querySelector('#color-picker-nano-edit');
            let pickrInstance; // To store the Pickr instance

            if (nanoPicker) {
                // Initialize Pickr with the input's initial value
                const initialColor = document.getElementById('value').value;
                pickrInstance = pickr.create({
                    el: nanoPicker,
                    theme: 'nano',
                    default: initialColor,
                    swatches: [
                        'rgba(102, 108, 232, 1)',
                        'rgba(40, 208, 148, 1)',
                        'rgba(255, 73, 97, 1)',
                        'rgba(255, 145, 73, 1)',
                        'rgba(30, 159, 242, 1)'
                    ],
                    components: {
                        preview: true,
                        opacity: true,
                        hue: true,
                        interaction: {
                            hex: true,
                            rgba: true,
                            input: true,
                            clear: true,
                            save: true
                        }
                    }
                });

                // Update input when Pickr changes
                pickrInstance.on('change', (color) => {
                    const hexColor = color.toHEXA().toString();
                    document.getElementById('value').value = hexColor;
                });
            }

            // Update Pickr when input changes (with validation)
            document.getElementById('value').addEventListener('input', function(e) {
                const value = e.target.value.trim();
                const hexRegex = /^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/i;

                if (hexRegex.test(value) && pickrInstance) {
                    pickrInstance.setColor(value);
                }
            });

            if (nanoPickerEdit) {
                // Initialize Pickr with the input's initial value
                const initialColor = document.getElementById('value').value;
                pickrInstance = pickr.create({
                    el: nanoPickerEdit,
                    theme: 'nano',
                    default: initialColor,
                    swatches: [
                        'rgba(102, 108, 232, 1)',
                        'rgba(40, 208, 148, 1)',
                        'rgba(255, 73, 97, 1)',
                        'rgba(255, 145, 73, 1)',
                        'rgba(30, 159, 242, 1)'
                    ],
                    components: {
                        preview: true,
                        opacity: true,
                        hue: true,
                        interaction: {
                            hex: true,
                            rgba: true,
                            input: true,
                            clear: true,
                            save: true
                        }
                    }
                });

                // Update input when Pickr changes
                pickrInstance.on('change', (color) => {
                    const hexColor = color.toHEXA().toString();
                    document.getElementById('value_edit').value = hexColor;
                });
            }

            // Update Pickr when input changes (with validation)
            document.getElementById('value_edit').addEventListener('input', function(e) {
                const value = e.target.value.trim();
                const hexRegex = /^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/i;

                if (hexRegex.test(value) && pickrInstance) {
                    pickrInstance.setColor(value);
                }
            });
        @endif

        $(document).ready(function() {
            $('.editBtn').on('click', function() {
                var id = $(this).data('id');
                var value = $(this).data('value');

                // Set the value
                $('#modalCenterEdit input[name="value_edit"]').val(value);
                @if ($attribute->id == '2')
                    var value_name = $(this).data('value_name');
                    $('#modalCenterEdit input[name="value_name_edit"]').val(value_name);
                @endif

                // Update the form action
                var updateUrl = "{{ url('admin/dashboard/attribute-values') }}/" + id;
                $('#editForm').attr('action', updateUrl);

                // Update Pickr color if available
                if (typeof pickrInstance !== 'undefined') {
                    pickrInstance.setColor(value);
                }
            });
        });
    </script>
@endsection
