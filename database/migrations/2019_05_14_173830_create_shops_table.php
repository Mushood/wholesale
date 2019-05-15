<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('ref')->unique();
            $table->integer('rating')->nullable();
            $table->integer('views')->default(0);
            $table->boolean('published')->default(false);

            $table->softDeletes();
            
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('shop_id')->unsigned()->nullable()->after('phone');
            $table->foreign('shop_id')->references('id')->on('shops');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shops');
    }
}
