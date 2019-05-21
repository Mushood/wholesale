<?php

namespace App\Models;

use App\Scopes\PublishedScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Shop extends Model implements HasMedia
{
    use HasMediaTrait, SoftDeletes;

    protected $fillable =['image', 'title', 'ref'];


    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        if (
            (!Auth::user() || !Auth::user()->hasRole('admin'))
        ) {
            static::addGlobalScope(new PublishedScope());
        }

        static::deleting(function($shop) {
            $shop->products()->delete();
        });
    }

    /**
     * @var array
     */
    protected $casts = [
        'published'  =>  'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany('App\Models\User');
    }
}
