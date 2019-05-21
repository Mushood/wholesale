<?php

namespace Tests;

use App\Models\Shop;
use Illuminate\Support\Facades\Session;

abstract class ShopTestCase extends CrudTestCase
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
