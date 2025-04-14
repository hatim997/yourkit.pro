<div class="col-12 col-lg-12">
    <label for="title" class="col-form-label">Title<span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('title') is-invalid @enderror " placeholder="Title" name="title" value="{{ $banner->title ?? old('title') }}">

    @error('title')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div>
<div class="col-12 col-lg-12">
    <label for="url_name" class="col-form-label">Button Text</label>
    <input type="text" class="form-control @error('url_name') is-invalid @enderror " placeholder="Button Text" name="url_name" value="{{ $banner->url_name ?? old('url_name') }}">

    @error('url_name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div>
<div class="col-12 col-lg-12">
    <label for="url" class="col-form-label">URL</label>
    <input type="text" class="form-control @error('url') is-invalid @enderror " placeholder="URL" name="url" value="{{ $banner->url ?? old('url') }}">

    @error('url')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div>

<div class="col-12 col-lg-12">
    <label for="description" class="col-form-label">Description<span class="text-danger">*</span></label>
    <textarea name="description" class="form-control @error('description') is-invalid @enderror " id="" cols="30" rows="10">{{ $banner->description ?? old('description') }}</textarea>

    @error('description')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div>

<div class="col-12 col-lg-12">
    <label for="image" class="col-form-label">Image<span class="text-danger">*</span></label>
 @if(isset($banner) && $banner->type='bottom')
    <small>(Image must be 650 x 350)</small>
    @elseif(isset($banner) && $banner->type='middle')
    <small>(Image must be 1250 x 550)</small>
    @else<small>(Image must be 1920 x 850px)</small>
    @endif
    <input type="file" class="form-control @error('image') is-invalid @enderror " name="image">

    @error('image')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

    @if (isset($banner->media) && !is_null($banner->media->path) && file_exists(public_path('storage/' . $banner->media->path)))
        <img style="height: 150px; width:150px;" src="{{ url('storage/'.$banner->media->path) }}" class="img-thumbnail" alt="">
    @endif

</div>
<div class="col-12 col-lg-12">
    <label for="status" class="col-form-label">Status<span class="text-danger">*</span></label>
    <select name="status" class="form-select @error('status') is-invalid @enderror " required>
        <option value="">--Select--</option>
        <option value="1" {{ isset($banner) && $banner->status == 1 ? 'selected' : ''  }}> Active </option>
        <option value="0" {{ isset($banner) && $banner->status == 0 ? 'selected' : ''  }}> Inactive </option>
        

    </select>

    @error('category_id')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div>

<div class="col-12 col-lg-12 my-2">
    <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary waves-effect waves-light">Back</a>
    <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
</div>
