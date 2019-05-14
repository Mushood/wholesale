@extends('layout.app')

@section('content')
    <div class="container">
        <h3>All Shops</h3>

        @foreach($shops as $shop)
            <div class="row">
                <div class="col-xs-12">
                    <h3>
                        <a href="{{ route('shop.show', ['shop' => $shop->id]) }}">
                            {{ $shop->title }}
                        </a>
                    </h3>
                </div>
            </div>
        @endforeach
        {{ $shops->links() }}
    </div>
@endsection