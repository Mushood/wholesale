<?php

namespace App\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use Translatable;

    /**
     * The attributes that are translated.
     *
     * @var array
     */
    public $translatedAttributes = [
        'title', 'subtitle', 'meta_description', 'introduction', 'body'
    ];

    /**
     * @var array
     */
    protected $with = ['translations'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
