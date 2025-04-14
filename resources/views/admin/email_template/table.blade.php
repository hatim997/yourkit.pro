@push('third_party_stylesheets')
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.css" />
@endpush

<div class="card-body pt-0 pb-4 pl-4 pr-4">
    {!! $dataTable->table(['width' => '100%', 'class' => 'table table-striped table-bordered table-sm']) !!}
</div>

@push('third_party_scripts')
<script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
{!! $dataTable->scripts() !!}
@endpush