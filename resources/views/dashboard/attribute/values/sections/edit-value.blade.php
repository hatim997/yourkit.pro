<!-- Modal -->
<div class="modal fade" id="modalCenterEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-3">Edit Value</h5>
                    </div>
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            @csrf
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="attribute_edit">{{ __('Attribute') }}</label>
                                    <input type="text" hidden value="{{ $attribute->id }}" name="attribute_id">
                                    <select id="attribute_edit" name="attribute_edit"
                                        class="select2 form-select @error('attribute') is-invalid @enderror" disabled>
                                        <option value="{{ $attribute->id }}" selected>
                                            {{ $attribute->type }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            @if ($attribute->id == '2')
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="value_edit" class="form-label">Value (Hex Code)</label>
                                        <input type="text"
                                            class="form-control @error('value_edit') is-invalid @enderror"
                                            id="value_edit" name="value_edit" value="{{ old('value_edit', '#000000') }}"
                                            required>
                                        @error('value_edit')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="color" class="form-label">Pick Color</label>
                                        <div class="nano col col-sm-3 col-lg-2">
                                            <div id="color-picker-nano-edit"></div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="value_edit" class="form-label">Value (Size)</label>
                                        <input type="text" class="form-control @error('value_edit') is-invalid @enderror"
                                            id="value_edit" name="value_edit" value="{{ old('value_edit') }}"
                                            placeholder="i.e. X, XL, XXL" required>
                                        @error('value_edit')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">Update Value</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
