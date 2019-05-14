<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Shop;
use Faker\Generator as Faker;

$factory->define(Shop::class, function (Faker $faker) {
    return [
        'title'     => $faker->word,
        'ref'       => $faker->unique()->numberBetween(0,5147483647),
        'rating'    => $faker->numberBetween(0,10),
    ];
});
