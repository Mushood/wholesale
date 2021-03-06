<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');

            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->string('last_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('phone')->nullable();

            $table->text('api_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->datetime('token_expiration')->nullable();
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();

            $table->softDeletes();

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
