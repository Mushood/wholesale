@extends('layout.app')

@section('content')
    <div class="container">
        <h3>
            {{ $brand->title }}
        </h3>
        @if (count($brand->getMedia()) > 0)
            <img src="{{ $brand->getMedia()[0]->getUrl() }}" alt="alt" />
        @endif
    </div>
@endsection