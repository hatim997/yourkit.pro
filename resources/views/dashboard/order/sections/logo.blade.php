<div class="row mt-3">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-datatable">
                <table class="datatables-order-details table mb-0">
                    <thead>
                        <tr>
                            <th scope="col">Comment</th>
                            <th scope="col">Image</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>{{ $odr->comment ?? '' }}</strong></td>

                            <td>
                                @if (!empty($odr->logo))
                                    <div class="d-flex flex-wrap gap-3">
                                        @foreach (json_decode($odr->logo) as $image)
                                            <div class="text-center">
                                                <img src="{{ url('storage/' . $image) }}" alt=""
                                                    class="img-thumbnail mb-1"
                                                    style="width: 150px; height: 150px;">
                                                <div>
                                                    <a href="{{ url('storage/' . $image) }}" download>
                                                        <i class="fa fa-download"></i> Download
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


    </div>
</div>
