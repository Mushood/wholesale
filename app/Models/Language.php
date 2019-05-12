<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = ['code', 'name', 'native'];

    const LANGUAGE_SEED_FILE = 'languages.json';
}
