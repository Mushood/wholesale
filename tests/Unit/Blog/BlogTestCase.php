<?php

namespace Tests;

use App\Models\Blog;
use App\Models\Category;
use Illuminate\Support\Facades\Session;

abstract class BlogTestCase extends CrudTestCase
{
    use CreatesApplication;

    protected $blog;

    public function setUp() : void
    {
        parent::setUp();

        $this->blog = Blog::first();

        Session::start();
    }

    public function getData()
    {

        return [
            'title'         => 'title',
            'body'          => 'body',
            'subtitle'      => 'subtitle',
            'introduction'  => 'introduction',
            'category_id'   => Category::where('published', true)->first()->id,
        ];
    }
}
