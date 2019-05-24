<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CartResource;

class ShoppingController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $cart = Cart::get($request);

        if ($request->wantsJson()) {
            return new CartResource($cart);
        }

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, $product)
    {
        $cart = Cart::get($request);
        $cart->items()->create([
            'product_id' => $product->id,
        ]);
        $cart->save();

        return new CartResource($cart);
    }

    public function set(Request $request, $product, $quantity)
    {
        if ($quantity < 1) {
            throw (new \Exception('quantity cannot be negative'));
        }

        $cart = Cart::get($request);
        $key = $cart->checkIfExists($product);

        if ($key === false) {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity'   => $quantity
            ]);
        }

        if (is_numeric($key)) {
            $cart->items[$key]->update([
                'quantity'   => $quantity
            ]);
        }

        return new CartResource($cart);
    }

    public function update(Request $request, $product, $quantity)
    {
        $cart = Cart::get($request);

        $key = $cart->checkIfExists($product);

        if ($key === false) {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity'   => $quantity
            ]);
        }

        if (is_numeric($key)) {
            $quantity += $cart->items[$key]->quantity;

            if ($quantity < 1) {
                $cart->items[$key]->delete();
            } else {
                $cart->items[$key]->update([
                    'quantity'   => $quantity
                ]);
            }
        }

        return new CartResource($cart);
    }
}
