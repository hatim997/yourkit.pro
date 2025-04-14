<div class="col-12 col-lg-12">
    <label for="name" class="col-form-label">Product Name<span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('name') is-invalid @enderror " placeholder="Product Name" name="name"
        value="{{ $product->name ?? old('name') }}" required>

    @error('name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div>


<div class="col-12 col-lg-12">
    <label for="category_id" class="col-form-label">Category<span class="text-danger">*</span></label>
    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
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
    <select name="sub_category_id" class="form-select @error('sub_category_id') is-invalid @enderror" required>
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
    <label for="mrp" class="col-form-label">MRP<span class="text-danger">*</span></label>
    <input type="number" class="form-control @error('mrp') is-invalid @enderror " placeholder="Enter MRP" name="mrp"
        value="{{ $product->mrp ?? old('mrp') }}" required>

    @error('mrp')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div>

<div class="col-12 col-lg-12">
    <label for="price" class="col-form-label">Price<span class="text-danger">*</span></label>
    <input type="number" class="form-control @error('price') is-invalid @enderror " placeholder="Enter Price" name="price"
        value="{{ $product->price ?? old('price') }}" required>

    @error('price')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div>

<div class="col-12 col-lg-12">
    <label for="no_of_stock" class="col-form-label">No of Stock<span class="text-danger">*</span></label>
    <input type="number" class="form-control @error('no_of_stock') is-invalid @enderror " placeholder="Enter no of stock" name="no_of_stock"
        value="{{ $product->no_of_stock ?? old('no_of_stock') }}" required>

    @error('no_of_stock')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div>

<div class="col-12 col-lg-12">
    <label for="status" class="col-form-label">Status<span class="text-danger">*</span></label>
    <select name="status" class="form-select @error('status') is-invalid @enderror " required>
        <option value="">--Select--</option>
        <option value="1" {{ isset($product) && $product->status == 1 ? 'selected' : ''  }}> Active </option>
        <option value="0" {{ isset($product) && $product->status == 0 ? 'selected' : ''  }}> Inactive </option>
        

    </select>

    @error('status')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div>

{{-- <div class="col-12 col-lg-12">
    <label for="attributes" class="col-form-label">Attribute<span class="text-danger">*</span></label>
    <select name="attributesId[]" class="form-select multiple @error('attributes') is-invalid @enderror" multiple="multiple" id="arrtibutesId">
        <option value="">--Select--</option>
        @foreach ($attributes as $attribute)
            <option value="{{ $attribute->id }}"
                {{ isset($product) && $product->category_id == $product->id ? 'selected' : '' }}>
                {{ $attribute->type }}</option>
        @endforeach

    </select>

    @error('attributes')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

</div> --}}

<div class="col-12 col-lg-12 my-2">
    @include('admin.products.attributes_fields')
</div>

<div class="col-12 col-lg-12 my-2">
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary waves-effect waves-light">Back</a>
    <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
</div>

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
    <script src = "https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>

        $(document).ready(function() {
            $('.multiple').select2();
        });

    </script>

@endpush
