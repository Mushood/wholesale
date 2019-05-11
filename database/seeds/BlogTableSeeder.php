<?php

use Illuminate\Database\Seeder;

class BlogTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Blog::class, 25)->create();

        factory(App\Models\Blog::class, 10)->create(['published' => true]);
    }
}
