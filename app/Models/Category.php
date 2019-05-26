<?php

namespace App\Models;

use App\Scopes\PublishedScope;
use Illuminate\Support\Facades\Auth;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Category extends Model implements HasMedia
{
    use Translatable, HasMediaTrait, SoftDeletes;

    protected $fillable = [ 'type', 'image'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        if (!Auth::user() || !Auth::user()->hasRole('admin')) {
            static::addGlobalScope(new PublishedScope());
        }

        static::deleting(function($category) {
            $category->products()->delete();
            $category->blogs()->delete();
        });
    }

    /**
     * The attributes that are translated.
     *
     * @var array
     */
    public $translatedAttributes = [
        'title', 'description', 'slug'
    ];

    /**
     * @var array
     */
    protected $with = ['translations'];

    /**
     * @var array
     */
    protected $casts = [
        'published'  =>  'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function translation()
    {
        return $this->hasOne('App\Models\BlogTranslation');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function blogs()
    {
        return $this->hasMany('App\Models\Blog');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }
}
