@extends('layout.app')

@section('content')
    <div class="container">
        <h3>All Blogs</h3>

        @foreach($blogs as $blog)
            <div class="row">
                <div class="col-xs-12">
                    <h3>
                        <a href="{{ route('blog.show', ['blog' => $blog->id]) }}">
                            {{ $blog->title }}
                        </a>
                    </h3>
                </div>
            </div>
        @endforeach
        {{ $blogs->links() }}
    </div>
@endsection