<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    protected $with = ['items', 'user'];

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
}
