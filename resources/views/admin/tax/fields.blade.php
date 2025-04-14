<div class="col-12 col-lg-12">
    <label for="tax_type" class="col-form-label">Tax Type<span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('tax_type') is-invalid @enderror " placeholder="Tax Type" name="tax_type" value="{{ $tax->tax_type ?? old('tax_type') }}">

    @error('tax_type')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div>

<div class="col-12 col-lg-12">
    <label for="tax_code" class="col-form-label">Tax Code<span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('tax_code') is-invalid @enderror " placeholder="Tax Code" name="tax_code" value="{{ $tax->tax_code ?? old('tax_type') }}">

    @error('tax_code')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div>

<div class="col-12 col-lg-12">
    <label for="percentage" class="col-form-label">Tax Percentage<span class="text-danger">*</span></label>
    <input type="number" class="form-control @error('percentage') is-invalid @enderror " name="percentage" value="{{ $tax->percentage ?? old('tax_type') }}" step=".001">

    @error('percentage')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div>


<div class="col-12 col-lg-12 my-2">
    <a href="{{ route('admin.faq.index') }}" class="btn btn-secondary waves-effect waves-light">Back</a>
    <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
</div>
