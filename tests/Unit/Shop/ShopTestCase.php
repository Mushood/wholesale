<?php

namespace Tests;

use App\Models\Shop;
use App\Models\Category;
use Illuminate\Support\Facades\Session;
use Tests\TestCase as BaseTestCase;

abstract class ShopTestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $shop;

    public function setUp() : void
    {
        parent::setUp();

        $this->shop = Shop::where('published', true)->first();

        Session::start();
    }

    public function getData()
    {

        return [
            'title' => 'title',
            'ref'   => 'ref'
        ];
    }
}
