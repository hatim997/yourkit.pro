<div class="col-12 col-lg-12">
    <label for="name" class="col-form-label">Product Name<span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('name') is-invalid @enderror " placeholder="Product Name" name="name"
        value="{{ $product->name ?? old('name') }}">

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
            <option value="{{ $category->id }}"
                {{ isset($product) && $product?->category_id == $category->id ? 'selected' : '' }}>
                {{ $category->name }}</option>
        @endforeach

    </select>

    @error('category_id')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div>

<div class="col-12 col-lg-12">
    <label for="sub_category_id" class="col-form-label">Sub Category<span class="text-danger">*</span></label>
    <select name="sub_category_id" class="form-select @error('sub_category_id') is-invalid @enderror ">
        <option value="">--Select--</option>
        @foreach ($subcategories->where('status',1) as $subcategory)
            <option value="{{ $subcategory->id }}"
                {{ isset($product) && $product->sub_category_id == $subcategory->id ? 'selected' : '' }}>
                {{ $subcategory->name }}</option>
        @endforeach

    </select>

    @error('category_id')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div>

<div class="col-12 col-lg-12">
    <label for="description" class="col-form-label">Description<span class="text-danger">*</span></label>
    <textarea class="form-control @error('description') is-invalid @enderror " name="description" id="tinymce-editor"
        cols="30" rows="10">{{ $product->description ?? old('description') }}</textarea>

    @error('description')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div>
<div class="col-12 col-lg-12">
    <label for="size_chart" class="col-form-label">Size Chart<span class="text-danger"></span></label>
    <input class="form-control @error('size_chart') is-invalid @enderror" type="file" name="size_chart"   accept="application/pdf">

    @error('size_chart')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
   
</div>

<div class="col-12 col-lg-12">
    <label for="size_chart" class="col-form-label"><span class="text-danger"></span></label>
    @if (!empty($product->size_chart))
    <a href="{{ url('storage/' . $product->size_chart) }}" 
       target="_blank" 
       class="btn btn-sm btn-primary ms-2">
        View Size Chart
    </a>
@endif

</div>
{{-- <div class="col-12 col-lg-12">
    <label for="price" class="col-form-label">Price<span class="text-danger">*</span></label>
    <input type="number" class="form-control @error('price') is-invalid @enderror "
        placeholder="Enter no of stock" name="price" value="{{ $product->price ?? old('price') }}">

    @error('price')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div> --}}

<div class="col-12 col-lg-12">
    <label for="status" class="col-form-label">Status<span class="text-danger">*</span></label>
    <select name="status" class="form-select @error('status') is-invalid @enderror ">
        <option value="">--Select--</option>
        <option value="1" {{ isset($product) && $product->status == 1 ? 'selected' : '' }}> Active </option>
        <option value="0" {{ isset($product) && $product->status == 0 ? 'selected' : '' }}> Inactive </option>


    </select>

    @error('category_id')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div>

<div class="col-12 col-lg-12 my-2">

    @include('admin.ecommerce.attributes_fields')

</div>

<div class="col-12 col-lg-12 my-2">
    <a href="{{ route('admin.ecommerce.index') }}" class="btn btn-secondary waves-effect waves-light">Back</a>
    <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
</div>

