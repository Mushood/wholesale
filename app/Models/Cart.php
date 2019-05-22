<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Cart extends Model
{
    const CART_STATUS = [
        'new'       => 1,
        'paid'      => 2,
        'processed' => 3,
        'delivered' => 4,
        'cancelled' => 5,
        'refund'    => 6
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

    public function getTotal()
    {
        $total = 0;

        foreach ($this->items as $item) {
            $total += ($item->product->price * $item->product->quantity);
        }

        return $total;
    }

    public static function generateIdentifier($length)
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
}
