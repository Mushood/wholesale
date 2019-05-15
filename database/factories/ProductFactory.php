<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Shop;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\Currency;
use App\Models\ProductPrice;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {

    $lastCatId      = Category::latest()->first()->id;
    $lastBrandId    = Brand::latest()->first()->id;
    $lastShopId     = Shop::latest()->first()->id;

    return [
        'title'         => $faker->word,
        'subtitle'      => $faker->sentence,
        'introduction'  => $faker->sentence,
        'body'          => $faker->text,
        'category_id'   => $faker->numberBetween(1, $lastCatId),
        'brand_id'      => $faker->numberBetween(1, $lastBrandId),
        'shop_id'       => $faker->numberBetween(1, $lastShopId),
        'min_quantity'  => $faker->numberBetween(1, 100),
        'measure'       => 'unit',
    ];
});

$factory->define(ProductPrice::class, function (Faker $faker) {
    $lastCurId      = Currency::latest()->first()->id;

    return [
        'price'         => $faker->numberBetween(100, 1000),
        'quantity'      => $faker->numberBetween(1, 10),
        'currency_id'   => $faker->numberBetween(1, $lastCurId),
    ];
});