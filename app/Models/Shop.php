<?php

namespace App\Models;

use App\Scopes\PublishedScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Shop extends Model implements HasMedia
{
    use HasMediaTrait;

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
    }

    /**
     * @var array
     */
    protected $casts = [
        'published'  =>  'boolean',
    ];
}
