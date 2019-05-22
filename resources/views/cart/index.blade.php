@extends('layout.app')

@section('content')
    <div class="container">
        <h3>My Cart</h3>
            <div class="row">
                <div class="col-xs-12">
                    <ul>
                        @foreach($cart->items as $item)
                            <li>{{ $item->product->title }} - {{ $item->product->quantity }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
    </div>
@endsection