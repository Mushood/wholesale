<?php

namespace App\Models;

use App\Scopes\PublishedScope;
use Illuminate\Support\Facades\Auth;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Blog extends Model implements HasMedia
{
    use Translatable, HasMediaTrait, SoftDeletes;

    protected $fillable =['image', 'category_id'];

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
    }

    /**
     * The attributes that are translated.
     *
     * @var array
     */
    public $translatedAttributes = [
        'title', 'subtitle', 'meta_description', 'introduction', 'body', 'slug'
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function translation()
    {
        return $this->hasOne('App\Models\BlogTranslation');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    /**
     * Get blog's author's name
     *
     * @return string
     */
    public function author()
    {
        return $this->user()->exists() ? $this->user->name() : "Anonymous";
    }
}
