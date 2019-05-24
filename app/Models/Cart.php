<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Cart extends Model
{
    const CART_STATUS = [
        'new'       => 1,
        'paid'      => 2,
        'processed' => 3,
        'delivered' => 4,
        'cancelled' => 5,
        'refund'    => 6,
        'saved'     => 7
    ];

    const CART_IDENTIFIER_KEY = "cart_identifier";

    protected $with = ['items', 'user'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function($cart) {
            $user = Auth::user();

            if ($user !== null) {
                $cart->user_id = $user->id;
            }

            if ($user === null) {
                $cart->identifier = self::generateIdentifier(15);
            }

        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function items()
    {
        return $this->hasMany('App\Models\CartItem');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get total of cart
     *
     * @return float|int
     */
    public function getTotal()
    {
        $total = 0;

        foreach ($this->items as $item) {
            $total += ($item->product->price * $item->product->quantity);
        }

        return $total;
    }

    /**
     * Generate a random string on length specified
     * @param int $length
     * @return string
     */
    public static function generateIdentifier(int $length)
    {
        do {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
        } while(self::where('identifier', $randomString)->exists());

        return $randomString;
    }

    /**
     * Retrieve cart instance
     *
     * @param Request $request
     * @return Cart
     */
    public static function get(Request $request) : self
    {
        $user = Auth::user();
        $cart = null;

        if ($request->session()->has(self::CART_IDENTIFIER_KEY)) {
            $cart = Cart::where('identifier', $request->session()->get(self::CART_IDENTIFIER_KEY))->first();

            if ($user !== null) {
                $cart->user()->associate($user);
                $cart->save();
            }
        }

        if ($user !== null && $cart === null) {
            $cart = self::where('user_id', $user->id)->where('active', true)->first();
        }

        if ($cart === null) {
            $cart = self::create([]);
        }

        if ($user === null) {
            $request->session()->put(self::CART_IDENTIFIER_KEY, $cart->identifier);
        }

        return $cart;
    }

    /**
     * Check if product exists in cart
     *
     * @param $product
     * @return bool|int|string
     */
    public function checkIfExists($product)
    {
        foreach ($this->items as $key => $item) {
            if ($item->product_id === $product->id) {
                return $key;
            }
        }

        return false;
    }
}
