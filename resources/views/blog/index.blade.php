@extends('layout.app')

@section('content')
    <!-- Page info -->
    <div class="page-top-info">
        <div class="container">
            <h4>All Blogs
                {{ isset($category) ? ' - ' .$category->title : ''}}
            </h4>
            <p>Discover testimonials, informative articles, product reviews and so much more to help you get the best of out of your purchases</p>
        </div>
    </div>
    <!-- Page info end -->
    <div class="container content-padding">
        <div class="row">
            <div class="col-lg-8">
                @foreach($blogs as $blog)
                    <hr />
                    <div class="row">
                        <div class="col-sm-4">
                            <img src="{{ $blog->media->first()->name ?? "No Picture"}}" alt="{{ $blog->title }}" class="img-fluid">
                        </div>
                        <div class="col-sm-8">
                            <h4>{{ $blog->title }}</h4>
                            <p>{{ $blog->introduction }}</p>
                            <a class="btn btn-primary site-btn col-xs-6 pull-right" href="{{ route('blog.show', ['blog' => $blog->id]) }}">
                                Read More
                            </a>
                        </div>
                    </div>
                @endforeach
                {{ $blogs->links() }}
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