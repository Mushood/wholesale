@extends('layout.app')

@section('content')

    <!-- Page info -->
    <div class="page-top-info">
        <div class="container">
            <h4>Category Page</h4>
            <div class="site-pagination">
                <a href="">Home</a> /
                <a href="">Shop</a> /
            </div>
        </div>
    </div>
    <!-- Page info end -->


    <!-- Category section -->
    <section class="category-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 order-2 order-lg-1">
                    <product-filter
                        :default="{{ $defaultSearch }}"
                    ></product-filter>
                </div>

                <div class="col-lg-9  order-1 order-lg-2 mb-5 mb-lg-0">
                    <products-landing
                        route_search="{{ route('product.search') }}"
                    ></products-landing>
                </div>
            </div>
        </div>
    </section>
    <!-- Category section end -->
@endsection