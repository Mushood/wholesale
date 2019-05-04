@extends('layout.app')

@section('content')
    <h3>Create Blog</h3>

    <form method="POST" action="{{ route('blog.store') }}">
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
        <button type="submit" class="btn btn-primary mb-2">Submit</button>
    </form>
@endsection