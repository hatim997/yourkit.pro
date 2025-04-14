<div class="col-12 col-lg-12">
    <label for="type" class="col-form-label">Attribute Name<span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('type') is-invalid @enderror " placeholder="Attribute"
        name="type" value="{{ $attribute->type ?? old('type') }}">

    @error('name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div>

<div class="col-12 col-lg-12 my-2">
    @include('admin.attributes.attributes_fields')
</div>

<div class="col-12 col-lg-12 my-2">
    <a href="{{ route('admin.attributes.index') }}" class="btn btn-secondary waves-effect waves-light">Back</a>
    <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
</div>

