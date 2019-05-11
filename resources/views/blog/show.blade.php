@extends('layout.app')

@section('content')
    <div class="container">
        <h3>
            {{ $blog->title }} <br />
            <small>{{ $blog->subtitle }}</small>
        </h3>
        <p>{{ $blog->introduction }}</p>
        <p>{{ $blog->body }}</p>
        <img src="{{ $blog->getMedia()[0]->getUrl() }}" alt="alt" />
    </div>
@endsection