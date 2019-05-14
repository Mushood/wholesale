<?php

namespace Tests;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Facades\Session;
use Tests\TestCase as BaseTestCase;

abstract class BrandTestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $brand;

    public function setUp() : void
    {
        parent::setUp();

        $this->brand = Brand::where('published', true)->first();

        Session::start();
    }

    public function getData()
    {

        return [
            'title' => 'title',
        ];
    }
}
