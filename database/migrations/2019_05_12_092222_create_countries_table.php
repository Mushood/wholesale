<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code');
            $table->string('capital');
            $table->string('region');
            $table->string('subregion');
            $table->string('timezone');
            $table->string('flag');

            $table->integer('currency_id')->unsigned()->nullable();
            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->integer('language_id')->unsigned()->nullable();
            $table->foreign('language_id')->references('id')->on('languages');

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
        Schema::dropIfExists('countries');
    }
}
