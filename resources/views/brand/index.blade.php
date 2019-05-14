@extends('layout.app')

@section('content')
    <div class="container">
        <h3>All Brands</h3>

        @foreach($brands as $brand)
            <div class="row">
                <div class="col-xs-12">
                    <h3>
                        <a href="{{ route('brand.show', ['brand' => $brand->id]) }}">
                            {{ $brand->title }}
                        </a>
                    </h3>
                </div>
            </div>
        @endforeach
        {{ $brands->links() }}
    </div>
@endsection