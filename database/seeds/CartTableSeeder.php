<?php

use App\Models\User;
use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Database\Seeder;

class CartTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lastProductId = Product::latest()->first()->id;

        //Authenticated User
        $cart = new Cart();
        $cart->user_id = User::find(1)->id;
        $cart->save();

        for ($i = 0; $i < 3; $i++) {
            $cartItem = new CartItem();
            $cartItem->cart_id = $cart->id;
            $cartItem->product_id = Product::find(rand(1, $lastProductId))->id;
            $cartItem->save();

            $cartItem = new CartItem();
            $cartItem->cart_id = $cart->id;
            $cartItem->product_id = Product::find(rand(1, $lastProductId))->id;
            $cartItem->save();
        }

        //Unauth User
        $cart = new Cart();
        $cart->unauth_identifier = rand(1000000, 10000000);
        $cart->save();

        for ($i = 0; $i < 3; $i++) {
            $cartItem = new CartItem();
            $cartItem->cart_id = $cart->id;
            $cartItem->product_id = Product::find(rand(1, $lastProductId))->id;
            $cartItem->save();

            $cartItem = new CartItem();
            $cartItem->cart_id = $cart->id;
            $cartItem->product_id = Product::find(rand(1, $lastProductId))->id;
            $cartItem->save();
        }

    }
}
