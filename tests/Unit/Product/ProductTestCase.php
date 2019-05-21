<?php

namespace Tests;

use App\Models\Shop;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

abstract class ProductTestCase extends CrudTestCase
{
    use CreatesApplication;

    protected $product;

    public function setUp() : void
    {
        parent::setUp();

        $this->product = Product::where('published', true)->first();

        Session::start();
    }

    public function getData()
    {

        return [
            'category_id'   => Category::where('published', true)->first()->id,
            'brand_id'      => Brand::where('published', true)->first()->id,
            'shop_id'       => Shop::where('published', true)->first()->id,
            'min_quantity'  => 1,
            'measure'       => 'unit',
            'title'         => 'title',
            'subtitle'      => 'subtitle',
            'introduction'  => 'introduction',
            'body'          => 'body',
        ];
    }
}
