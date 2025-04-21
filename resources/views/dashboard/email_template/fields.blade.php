<!-- Subject Field -->
<div class="form-group col-sm-12">
    <label for="subject">Subject:</label>
    <input type="text" name="subject" class="form-control" id = "subject" required value="{{old("subject") ?? ($email->subject ?? "")}}"/>
    @error('subject')
        <span class="text text-danger">{{ $message }}</span>
    @enderror
</div>

<!-- Contents Field -->
<div class="form-group col-sm-12">
    <label for="content">Content:</label>
    <textarea class="form-control textarea-custom-text" required name="content" cols="50" rows="10" id="tinymce-editor" >{{old("content") ?? ($email->content ?? "")}}</textarea>
    @error('content')
        <span class="text text-danger">{{ $message }}</span>
    @enderror
</div>




<!-- Status Field -->
{{-- <div class="form-group col-sm-6">
    <label for="status">Status:</label>
   

    <select class="form-select @error('status') is-invalid @enderror" name="status">
        <option selected="" value="">--Select Status--</option>
        <option value="1" {{ isset($email) && $email->status == 1 ? 'selected' : '' }}>Active</option>
        <option value="0" {{ isset($email) && $email->status == 0 ? 'selected' : '' }}> Disable</option>
    </select>

    @error('status')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div> --}}

<div class="col-12 col-lg-12 my-2">
    <a href="{{ route('admin.email-templates.index') }}" class="btn btn-secondary waves-effect waves-light"> Back </a>
    <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
    
</div>
