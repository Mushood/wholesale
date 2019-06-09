<?php

namespace App\Models;

use App\Scopes\PublishedScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use App\Services\Searchable\SearchableInterface;

class Brand extends Model implements HasMedia, SearchableInterface
{
    use HasMediaTrait, SoftDeletes;

    protected $fillable =['image', 'title'];

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

        static::deleting(function($brand) {
            $brand->products()->delete();
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
    public function users()
    {
        return $this->hasMany('App\Models\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    /**
     * Add brand search criteria
     *
     * @param array $criteria
     * @return array
     */
    public static function getCriteria(array &$criteria)
    {
        $criteria['brands'] = [];
        $brands = self::where('published', true)->orderBy('title')->get();
        foreach ($brands as $brand) {
            array_push($criteria['brands'], [
                'id'    => $brand->id,
                'name'  => $brand->title
            ]);
        }

        return $criteria;
    }

    public static function filter($products, $search)
    {
        if (isset($search['brands']) && count($search['brands']) > 0) {
            $brandIds = collect($search['brands'])->pluck('id');
            $products = $products->whereIn('brand_id', $brandIds);
        }

        return $products;
    }
}
