<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Spatie\Permission\Models\Role::create(['name' => 'admin']);
        factory(App\Models\User::class, 1)->create()->each(function ($user) {
            $user->assignRole('admin');
        });

        \Spatie\Permission\Models\Role::create(['name' => 'wholesaler']);
        factory(App\Models\User::class, 1)->create()->each(function ($user) {
            $user->assignRole('wholesaler');
        });

        factory(App\Models\User::class, 10)->create();
    }
}
