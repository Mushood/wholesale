<h4>Categories</h4>
@foreach($categories as $category)
    <h5>
        <a href="{{ route('blog.category', ['slug' => $category->slug]) }}">
            {{ $category->title }}
        </a>
    </h5>
@endforeach