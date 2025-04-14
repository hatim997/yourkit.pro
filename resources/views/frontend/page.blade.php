@extends('frontend.layouts')

@section('title', $page->name ?? '')

@section('content')

    <section class="main-wrap">
        <div class="container-xxl">
            <div class="row align-items-center flex-row-reverse">
                <div class="col-md-12">
                    <div class="title-prt">
                        {{-- <h2>{{ $page->name ?? '' }}</h2> --}}
                    </div>

                    <p>{!! $page->content->description ?? '' !!}</p>
                </div>
            </div>
        </div>
    </section>

@endsection