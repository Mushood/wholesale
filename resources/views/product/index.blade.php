@extends('layout.app')

@section('content')
    <div class="container">
        <h3>All Products</h3>

        @foreach($products as $product)
            <div class="row">
                <div class="col-xs-12">
                    <h3>
                        <a href="{{ route('product.show', ['product' => $product->id]) }}">
                            {{ $product->title }}
                        </a>
                    </h3>
                </div>
            </div>
        @endforeach
        {{ $products->links() }}
    </div>
@endsection