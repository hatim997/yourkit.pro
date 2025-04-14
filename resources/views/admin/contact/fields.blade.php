<div class="col-12 col-lg-12">
    <label for="name" class="col-form-label"> Name</label>
    <p  class="form-control @error('name') is-invalid @enderror " >{{ $contact->name ?? '' }}</p>

 

</div>


<div class="col-12 col-lg-12">
    <label for="name" class="col-form-label"> Email</label>
    <p  class="form-control @error('name') is-invalid @enderror "  >{{ $contact->email ?? '' }}</p>


</div>

<div class="col-12 col-lg-12">
    <label for="name" class="col-form-label"> Phone</label>
    <p  class="form-control @error('name') is-invalid @enderror "  >{{ $contact->phone ?? '' }}</p>


</div>


<div class="col-12 col-lg-12">
    <label for="name" class="col-form-label"> Email</label>
    <p  class="form-control @error('name') is-invalid @enderror "  >{{ $contact->message ?? '' }}</p>


</div>



<div class="col-12 col-lg-12 my-2">
    <a href="{{ route('admin.contact.index') }}" class="btn btn-secondary waves-effect waves-light">Back</a>

</div>
