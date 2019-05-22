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
        $user = Auth::user();
        $cart = null;

        if ($user !== null) {
            $cart = Cart::where('user_id', $user->id)->where('active', true)->first();
        }

        if ($cart === null) {
            $cart = Cart::create([]);
        }

        if ($user === null) {
            $request->session()->put(Cart::CART_IDENTIFIER_KEY, $cart->identifier);
        }

        if ($request->wantsJson()) {
            return new CartResource($cart);
        }

        return view('cart.index', compact('cart'));
    }
}
