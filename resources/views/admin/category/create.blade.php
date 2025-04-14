@extends('admin.master')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">

            <div class="mb-3 ">
                <h1 class="h3 d-inline align-middle">Create Category</h1>
            </div>
            <div class="row">

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @include('admin.category.fields')
                    </form>
                    </div>
                </div>
            </div>

        </div>
    </main>
@endsection
