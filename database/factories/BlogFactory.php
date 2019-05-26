<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Blog;
use App\Models\Category;
use Faker\Generator as Faker;

$factory->define(Blog::class, function (Faker $faker) {
    $catIds = Category::where('published', true)->pluck('id')->toArray();

    return [
        'title'         => $faker->word,
        'subtitle'      => $faker->sentence,
        'introduction'  => $faker->sentence,
        'body'          => $faker->text,
        'category_id'   => $catIds[rand(0, count($catIds) - 1)],
    ];
});
