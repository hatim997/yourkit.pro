@push('third_party_stylesheets')
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.css" />
@endpush

<div class="table-responsive">
    {{$dataTable->table(['class'=> 'table table-striped', 'style' => 'width:100%'])}}
</div>


@push('third_party_scripts')
<script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
    {!! $dataTable->scripts() !!}
@endpush