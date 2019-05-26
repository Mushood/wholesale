@extends('layout.app')

@section('content')
    <!-- Page info -->
    <div class="page-top-info">
        <div class="container">
            <h4>All Blogs
                {{ $blog->title }}
            </h4>
            <p>{{ $blog->subtitle }}</p>
        </div>
    </div>
    <!-- Page info end -->

    <div class="container content-padding">
        <div class="row">
            <div class="col-lg-8">
                <img src="{{ $blog->media->first()->name ?? "No Picture"}}" alt="{{ $blog->title }}" class="img-fluid">
                <h5>{{ $blog->introduction }}</h5>
                <p>{{ $blog->body }}</p>
                <a class="btn btn-primary site-btn col-xs-6 pull-right" href="{{ route('blog.index') }}">
                    Back
                </a>
            </div>
            <div class="col-lg-4 blog-categories">
                <h4>Categories</h4>
                @foreach($categories as $category)
                    <h5>
                        <a href="{{ route('blog.category', ['category' => $category->id]) }}">
                            {{ $category->title }}
                        </a>
                    </h5>
                @endforeach
            </div>
        </div>
    </div>
@endsection