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
}
