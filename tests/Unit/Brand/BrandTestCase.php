<?php

namespace Tests;

use App\Models\Brand;
use Illuminate\Support\Facades\Session;

abstract class BrandTestCase extends CrudTestCase
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
