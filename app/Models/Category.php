<?php

namespace App\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Category extends Model implements HasMedia
{
    use Translatable, HasMediaTrait;

    protected $fillable = [ 'type', 'image'];
    /**
     * The attributes that are translated.
     *
     * @var array
     */
    public $translatedAttributes = [
        'title', 'description',
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
}
