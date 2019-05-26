<?php

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Category::class, 5)->create();

        factory(App\Models\Category::class, 5)->create(['published' => true]);

        factory(App\Models\Category::class, 5)->create(['type' => 'tag']);

        factory(App\Models\Category::class, 5)->create(['type' => 'tag', 'published' => true]);
    }
}
