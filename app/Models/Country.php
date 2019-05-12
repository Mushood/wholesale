<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['code', 'name', 'capital', 'region', 'subregion', 'timezone', 'flag'];

    const SEED_FILE = 'countries.json';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language()
    {
        return $this->belongsTo('App\Models\Language');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency()
    {
        return $this->belongsTo('App\Models\Currency');
    }
}
