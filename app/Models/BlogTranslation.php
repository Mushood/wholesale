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
}
