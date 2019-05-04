<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Blog;
use Faker\Generator as Faker;

$factory->define(Blog::class, function (Faker $faker) {
    return [
        'title'         => $faker->word,
        'subtitle'      => $faker->sentence,
        'introduction'  => $faker->sentence,
        'body'          => $faker->text,
    ];
});
