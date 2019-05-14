<?php

use Illuminate\Database\Seeder;

class ShopTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Shop::class, 15)->create(['published' => true]);

        factory(App\Models\Shop::class, 15)->create();

        //Assign shops to users
        for ($i = 3; $i < 10; $i++) {
            $user = \App\Models\User::find($i);
            $user->shop_id = $i;
            $user->save();
        }
    }
}
