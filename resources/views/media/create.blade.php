@extends('layout.app')

@section('content')
    <h3>Create Blog</h3>

    <form method="POST" action="{{ route('temporaryUpload.store') }}" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="file">File</label>
            <input type="file" class="form-control" id="file" name="file" placeholder="file">
        </div>
        <button type="submit" class="btn btn-primary mb-2">Submit</button>
    </form>
@endsection