<?php

namespace Tests;

use App\Models\Category;
use Illuminate\Support\Facades\Session;
use Tests\TestCase as BaseTestCase;

abstract class CategoryTestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $category;

    public function setUp() : void
    {
        parent::setUp();

        $this->category = Category::first();

        Session::start();
    }

    public function getData()
    {

        return [
            'title'         => 'title',
            'description'   => 'description',
            'type'          => 'category'
        ];
    }
}
