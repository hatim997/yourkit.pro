@extends('admin.master')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">

            <div class="mb-3">
                <h1 class="h3 d-inline align-middle">Update Banner</h1>
            </div>
            <div class="row">

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @include('admin.banner.fields')
                    </form>
                    </div>
                </div>
            </div>

        </div>
    </main>
@endsection
