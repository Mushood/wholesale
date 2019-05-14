@extends('layout.app')

@section('content')
    <div class="container">
        <h3>
            {{ $shop->title }}
        </h3>
        @if (count($shop->getMedia()) > 0)
            <img src="{{ $shop->getMedia()[0]->getUrl() }}" alt="alt" />
        @endif
    </div>
@endsection