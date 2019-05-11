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
        \Spatie\Permission\Models\Role::create(['name' => \App\Models\User::ROLE_ADMIN]);
        factory(App\Models\User::class, 1)->create(['email' => \App\Models\User::ADMIN_EMAIL])->each(function ($user) {
            $user->assignRole(\App\Models\User::ROLE_ADMIN);
        });

        \Spatie\Permission\Models\Role::create(['name' => \App\Models\User::ROLE_WHOLESALER]);
        factory(App\Models\User::class, 1)->create(['email' => \App\Models\User::WHOLESALER_EMAIL])->each(function ($user) {
            $user->assignRole(\App\Models\User::ROLE_WHOLESALER);
        });

        factory(App\Models\User::class, 10)->create();
    }
}
