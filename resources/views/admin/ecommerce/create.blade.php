@extends('admin.master')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">

            <div class="mb-3 ">
                <h1 class="h3 d-inline align-middle">Create Ecommerce Product</h1>
            </div>
            <div class="row">

                <div class="card">
                    <div class="card-body">
                    <form id="myForm" action="{{ route('admin.ecommerce.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @include('admin.ecommerce.fields')
                    </form>
                    </div>
                </div>
            </div>

        </div>
    </main>
@endsection
