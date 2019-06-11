@extends('layout.app')

@section('content')
    <!-- Page info -->
    <div class="page-top-info">
        <div class="container">
            <h4>Category PAge</h4>
            <div class="site-pagination">
                <a href="">Home</a> /
                <a href="">Shop</a>
            </div>
        </div>
    </div>
    <!-- Page info end -->


    <!-- product section -->
    <product
        :product="{{ $product }}"
    ></product>
    <!-- product section end -->

    @include('product.related')

@endsection