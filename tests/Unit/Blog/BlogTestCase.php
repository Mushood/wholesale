<?php

namespace Tests;

use App\Models\Blog;
use Illuminate\Support\Facades\Session;
use Tests\TestCase as BaseTestCase;

abstract class BlogTestCase extends BaseTestCase
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
            'introduction'  => 'introduction'
        ];
    }
}
