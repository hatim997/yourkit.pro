<div class="col-12 col-lg-12">
    <label for="name" class="col-form-label">Sub Category Name<span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('name') is-invalid @enderror " placeholder="Sub Category"
        name="name" value="{{ $subcategory->name ?? old('name') }}">

    @error('name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div>

<div class="col-12 col-lg-12">
    <label for="category_id" class="col-form-label">Category<span class="text-danger">*</span></label>
    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror ">
        <option value="">--Select--</option>
        @foreach ($categories->where('status',1) as $category)
            <option value="{{ $category->id }}" {{ isset($subcategory) && $subcategory->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
        @endforeach

    </select>

    @error('category_id')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div>

<div class="col-12 col-lg-12">
    <label for="image" class="col-form-label">Image<span class="text-danger">*</span><small>(Images Must be 55 x 50 px)</small></label>
    <input type="file" class="form-control @error('image') is-invalid @enderror " name="image">

    @error('image')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

    @if (isset($subcategory->media->path))

    <img src="{{ url(asset('storage/'. $subcategory->media->path)) }}" alt="" style="height: 100px; width: 100px;">
        
    @endif

</div>

<div class="col-12 col-lg-12">
    <label for="description" class="col-form-label">Description<span class="text-danger">*</span></label>
    <textarea class="form-control @error('description') is-invalid @enderror " name="description" id=""
        cols="30" rows="10">{{ $subcategory->description ?? old('description') }}</textarea>

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
    <a href="{{ route('admin.sub-categories.index') }}" class="btn btn-secondary waves-effect waves-light">Back</a>
    <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
</div>
