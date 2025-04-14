<div class="col-12 col-lg-12">
    <label for="name" class="col-form-label">Category Name<span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('name') is-invalid @enderror " placeholder="Category" name="name" value="{{ $category->name ?? old('name') }}">

    @error('name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div>

<div class="col-12 col-lg-12">
    <label for="image" class="col-form-label">Image</label>
    <input type="file" class="form-control @error('image') is-invalid @enderror " name="image">

    @error('image')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

    @if (isset($category->media) && !is_null($category->media->path) && file_exists(public_path('storage/' . $category->media->path)))
        <img style="height: 150px; width:150px;" src="{{ url('storage/'.$category->media->path) }}" class="img-thumbnail" alt="">
    @endif

</div>

<div class="col-12 col-lg-12">
    <label for="description" class="col-form-label">Description<span class="text-danger">*</span></label>
    <textarea class="form-control @error('description') is-invalid @enderror " name="description" id=""
        cols="30" rows="10">{{ $category->description ?? old('description') }}</textarea>

    @error('description')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div>

<div class="col-12 col-lg-12">
    <label for="status" class="col-form-label">Status<span class="text-danger">*</span></label>
    <select name="status" class="form-select @error('status') is-invalid @enderror " required>
        <option value="">--Select--</option>
        <option value="1" {{ isset($category) && $category->status == 1 ? 'selected' : ''  }}> Active </option>
        <option value="0" {{ isset($category) && $category->status == 0 ? 'selected' : ''  }}> Inactive </option>
        

    </select>

    @error('status')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div>

<div class="col-12 col-lg-12 my-2">
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary waves-effect waves-light">Back</a>
    <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
</div>
