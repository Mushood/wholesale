@extends('layout.app')

@section('content')
    <div class="container">
        <h3>All Categories</h3>

        @foreach($categories as $category)
            <div class="row">
                <div class="col-xs-12">
                    <h3>
                        <a href="{{ route('category.show', ['category' => $category->id]) }}">
                            {{ $category->title }}
                        </a>
                    </h3>
                </div>
            </div>
        @endforeach
        {{ $categories->links() }}
    </div>
@endsection