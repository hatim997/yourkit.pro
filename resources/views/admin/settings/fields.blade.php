@if ($setting->input_type == 'text')

<div class="col-12 col-lg-12">
    <label for="value" class="col-form-label">Value<span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('value') is-invalid @enderror " placeholder="Value" name="value" value="{{ $setting->value ?? old('value') }}" required>

    @error('value')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div>
    
@endif

@if ($setting->input_type == 'select')

<div class="col-12 col-lg-12">
    <label for="status" class="col-form-label">Value<span class="text-danger">*</span></label>
    <select name="value" class="form-select @error('value') is-invalid @enderror " required>
        <option value="">--Select--</option>
        @foreach($options as $key => $val)
            <option value="{{$key}}" {{ ( old('value') ?? $setting->value ) == $key ? 'selected' : ''  }}> {{$val}} </option>
        @endforeach
        

    </select>

    @error('value')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div>
    
@endif

@if ($setting->input_type == 'textarea')

<div class="col-12 col-lg-12">
    <label for="value" class="col-form-label">Description<span class="text-danger">*</span></label>
    <textarea name="value" class="form-control @error('value') is-invalid @enderror " id="" cols="30" rows="10" required>{{ $setting->value ?? old('value') }}</textarea>

    @error('value')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div>
    
@endif

@if ($setting->input_type == 'file')

<div class="col-12 col-lg-12">
    <label for="image" class="col-form-label">Image<span class="text-danger">*</span></label>
    <input type="file" class="form-control @error('image') is-invalid @enderror " name="image">

    @error('image')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

    @if (isset($setting->value) && file_exists(public_path('storage/' . $setting->value)))
        <img style="height: 150px; width:150px;" src="{{ url('storage/'.$setting->value) }}" class="img-thumbnail" alt="">
    @endif

</div>

@endif



<div class="col-12 col-lg-12 my-2">
    <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary waves-effect waves-light">Back</a>
    <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
</div>
