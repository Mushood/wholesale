@extends('layout.app')

@section('content')
    <div class="container">
        <h3>
            {{ $product->title }} <br />
            <small>{{ $product->subtitle }}</small>
        </h3>
        <p>{{ $product->introduction }}</p>
        <p>{{ $product->body }}</p>
        @if (count($product->getMedia()) > 0)
            <img src="{{ $product->getMedia()[0]->getUrl() }}" alt="alt" />
        @endif
    </div>
@endsection