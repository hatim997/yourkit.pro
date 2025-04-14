<div class="col-12 col-lg-12">
    <label for="title" class="col-form-label">Title<span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('title') is-invalid @enderror " placeholder="Title" name="title" value="{{ $faq->title ?? old('title') }}" required>

    @error('title')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div>


<div class="col-12 col-lg-12">
    <label for="description" class="col-form-label">Description<span class="text-danger">*</span></label>
    <textarea class="form-control @error('description') is-invalid @enderror " name="description" id="tinymce-editor"
        cols="30" rows="10">{{ $faq->description ?? old('description') }}</textarea>

    @error('description')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div>

<div class="col-12 col-lg-12 my-2">
    <a href="{{ route('admin.faq.index') }}" class="btn btn-secondary waves-effect waves-light">Back</a>
    <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
</div>

