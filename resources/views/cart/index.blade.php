@extends('layout.app')

@section('content')
    <!-- Page info -->
    <div class="page-top-info">
        <div class="container">
            <h4>Your cart</h4>
            <div class="site-pagination">
                <a href="">Home</a> /
                <a href="">Your cart</a>
            </div>
        </div>
    </div>
    <!-- Page info end -->


    <!-- cart section end -->
    <cart-landing></cart-landing>
    <!-- cart section end -->

    @include('product.related')

@endsection