<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Brand;
use Faker\Generator as Faker;

$factory->define(Brand::class, function (Faker $faker) {
    return [
        'title'         => $faker->word,
    ];
});
