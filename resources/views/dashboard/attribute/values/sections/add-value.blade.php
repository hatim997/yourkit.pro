<!-- Modal -->
<div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-3">Add New Value</h5>
                    </div>
                    <form action="{{ route('dashboard.attribute-values.store') }}" method="POST"
                        enctype="multipart/form-data">
                        <div class="row">
                            @csrf
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="attribute">{{ __('Attribute') }}</label>
                                    <input type="text" hidden value="{{ $attribute->id }}" name="attribute_id">
                                    <select id="attribute" name="attribute"
                                        class="select2 form-select @error('attribute') is-invalid @enderror"
                                        disabled>
                                        <option value="{{ $attribute->id }}" selected>
                                            {{ $attribute->type }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            @if ($attribute->id == '2')
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="value" class="form-label">Value (Hex Code)</label>
                                        <input type="text" class="form-control @error('value') is-invalid @enderror"
                                            id="value" name="value" value="{{ old('value', '#000000') }}" required>
                                        @error('value')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="color" class="form-label">Pick Color</label>
                                        <div class="nano col col-sm-3 col-lg-2">
                                            <div id="color-picker-nano"></div>
                                        </div>
                                    </div>
                                </div>
                            @else
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="value" class="form-label">Value (Size)</label>
                                    <input type="text" class="form-control @error('value') is-invalid @enderror"
                                        id="value" name="value" value="{{ old('value') }}" placeholder="i.e. X, XL, XXL" required>
                                    @error('value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            @endif

                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">Add Value</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
