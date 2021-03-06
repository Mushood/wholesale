@extends('layout.app')

@section('content')
    <h3>Create Product</h3>

    <form method="POST" action="{{ route('product.store') }}" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="title">Title</label>
            <input type="title" class="form-control" id="title" name="title" placeholder="title" required>
        </div>
        <div class="form-group">
            <label for="title">Subtitle</label>
            <input type="subtitle" class="form-control" id="subtitle" name="subtitle" placeholder="subtitle">
        </div>
        <div class="form-group">
            <label for="introduction">Introduction</label>
            <textarea class="form-control" id="introduction" name="introduction" rows="3" placeholder="introduction"></textarea>
        </div>
        <div class="form-group">
            <label for="body">Body</label>
            <textarea class="form-control" id="body" name="body" rows="3" placeholder="body"></textarea>
        </div>
        <div class="form-group">
            <label for="type">Type</label>
            <select class="form-control" id="type" name="type">
                @foreach($categories as $key => $category)
                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="brand">Brand</label>
            <select class="form-control" id="brand" name="brand">
                @foreach($brands as $key => $brand)
                    <option value="{{ $brand->id }}">{{ $brand->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="image">File</label>
            <input type="file" class="form-control" id="image" name="image" placeholder="image">
        </div>
        <button type="submit" class="btn btn-primary mb-2">Submit</button>
    </form>
@endsection