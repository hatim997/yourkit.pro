<div class="row mt-3">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-datatable">
                <table class="datatables-order-details table mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">Sr. No.</th>
                            <th class="text-center">Images</th>
                            <th class="text-center">Comments</th>
                            <th class="text-center">Approval Status</th>
                            <th class="text-center">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logos as $key => $logo)
                            <tr>
                                <td class="text-center">{{ ++$key }}</td>

                                <td class="text-center">
                                    <img src="{{ url(asset('storage/' . $logo->image)) }}"
                                        class="img-thumbnail" alt="Image"
                                        style="width: 150px; height: auto;" />
                                </td>

                                <td>{{ $logo->comment ?? '' }}</td>

                                <td class="text-center">
                                    @if ($logo->approval_status == 1)
                                        <span class="badge bg-success">Approved</span>
                                    @elseif (is_null($logo->approval_status))
                                        <span class="badge bg-primary">Pending Approval</span>
                                    @else
                                        <span class="badge bg-danger">Not Approved</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($logo->created_at)->format('d/m/Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No data found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>


    </div>
</div>
