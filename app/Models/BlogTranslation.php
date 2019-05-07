<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogTranslation extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['title', 'subtitle', 'introduction', 'body'];

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function blog()
    {
        return $this->belongsTo('App\Models\Blog');
    }
}
