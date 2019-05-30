@extends('layout.app')

@section('content')
    <!-- Hero section -->
    @include('welcome.hero')
    <!-- Hero section end -->

    <!-- Features section -->
    @include('welcome.features')
    <!-- Features section end -->

    <!-- latest product section -->
    @include('welcome.latest')
    <!-- latest product section end -->

    <!-- Product filter section -->
    @include('welcome.filter')
    <!-- Product filter section end -->

    <!-- Banner section -->
    @include('welcome.banner')
    <!-- Banner section end  -->

@endsection