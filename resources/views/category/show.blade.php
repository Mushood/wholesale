@extends('layout.app')

@section('content')
    <div class="container">
        <h3>
            {{ $category->title }} of type {{ $category->type }}
        </h3>
        <p>{{ $category->description }}</p>
        @if (count($category->getMedia()) > 0)
            <img src="{{ $category->getMedia()[0]->getUrl() }}" alt="alt" />
        @endif
    </div>
@endsection