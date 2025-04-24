@extends('layouts.master')

@section('title', __('Shuffle Bundles'))

@section('css')
    <style>
        .sortable {
            min-height: 100px;
        }

        .sortable li {
            cursor: grab;
            transition: transform 0.2s, box-shadow 0.2s;
            border-left: 4px solid transparent;
        }

        .sortable li:hover {
            transform: translateX(5px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .sortable li.sortable-ghost {
            opacity: 0.5;
            background: #f8f9fa;
            border-left-color: #7367f0;
        }

        .sortable li.sortable-dragging {
            cursor: grabbing;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            z-index: 1000;
        }

        .drag-handle {
            cursor: grab;
            margin-right: 12px;
            color: #7367f0;
            transition: transform 0.2s;
        }

        .drag-handle:hover {
            transform: scale(1.2);
        }
    </style>
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item"><a
            href="{{ route('dashboard.kit-product-bundles.index') }}">{{ __('Kit Product Bundles') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Shuffle') }}</li>
@endsection
{{-- @dd($bundles) --}}
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Categories List Table -->

        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Shuffle Bundles</h5>
            </div>
            <div class="card-body row">
                <div class="col-md-12">
                    <div class="demo-inline-spacing mt-4">
                        <ul class="list-group sortable">
                            @foreach ($bundles as $bundle)
                                <li class="list-group-item d-flex justify-content-between align-items-center"
                                    data-id="{{ $bundle->id }}">
                                    <div>
                                        <i class="fas fa-grip-lines drag-handle"></i>
                                        <span class="badge me-4 bg-label-{{ $bundle->status == 1 ? 'success' : 'danger' }}">
                                            {{ $bundle->status == 1 ? 'Active' : 'Inactive' }}
                                        </span>
                                        <span class="bundle-name">{{ $bundle->name }}</span>
                                    </div>
                                    <span
                                        class="badge badge-center bg-primary position-badge">{{ $bundle->position }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".sortable").sortable({
                update: function(event, ui) {
                    let orderedIDs = [];
                    $('.sortable li').each(function(index) {
                        orderedIDs.push($(this).data('id'));
                    });

                    $.ajax({
                        url: '{{ route('dashboard.kit-product-bundles.shuffle-store') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            ids: orderedIDs
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: 'Success!',
                                    text: response.message,
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                location.reload(); // reload to refresh the positions
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: response.message,
                                    icon: 'error',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection

