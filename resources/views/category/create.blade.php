@extends('layout.app')

@section('content')
    <h3>Create Category</h3>

    <form method="POST" action="{{ route('category.store') }}" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="title">Title</label>
            <input type="title" class="form-control" id="title" name="title" placeholder="title" required>
        </div>
        <div class="form-group">
            <label for="type">Type</label>
            <select class="form-control" id="type" name="type">
                <option value="category">Category</option>
                <option value="tag">Tag</option>
            </select>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" placeholder="description"></textarea>
        </div>
        <div class="form-group">
            <label for="image">File</label>
            <input type="file" class="form-control" id="image" name="image" placeholder="image">
        </div>
        <button type="submit" class="btn btn-primary mb-2">Submit</button>
    </form>
@endsection