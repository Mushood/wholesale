<?php

use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Product::class, 100)->create(['published' => true])->each(function ($product) {
            $product->prices()->save(factory(App\Models\ProductPrice::class)->make());
        });

        factory(App\Models\Product::class, 1)->create(['published' => false])->each(function ($product) {
            $product->prices()->save(factory(App\Models\ProductPrice::class)->make());
        });
    }
}
