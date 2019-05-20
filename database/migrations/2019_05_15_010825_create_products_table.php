<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('views')->default(0);
            $table->boolean('published')->default(false);
            $table->integer('category_id')->unsigned()->nullable();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->integer('brand_id')->unsigned()->nullable();
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->integer('shop_id')->unsigned()->nullable();
            $table->foreign('shop_id')->references('id')->on('shops');
            $table->integer('min_quantity')->unsigned()->nullable();
            $table->string('measure')->nullable();

            $table->softDeletes();

            $table->timestamps();
        });

        Schema::create('product_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned()->nullable();
            $table->foreign('product_id')->references('id')->on('currencies');
            $table->integer('price')->unsigned();
            $table->integer('tax')->default(10);
            $table->integer('quantity')->unsigned();
            $table->integer('currency_id')->unsigned()->nullable();
            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->timestamps();
        });

        Schema::create('product_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->string('locale')->index();

            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('introduction')->nullable();
            $table->text('body')->nullable();

            $table->unique(['product_id','locale']);
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
