<?php

namespace App\Models;

use App\Scopes\PublishedScope;
use Illuminate\Support\Facades\Auth;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use App\Services\Searchable\SearchableInterface;

class Product extends Model implements HasMedia, SearchableInterface
{
    use Translatable, HasMediaTrait, SoftDeletes;

    protected $fillable =['image', 'category_id', 'brand_id', 'shop_id', 'min_quantity', 'measure'];

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
        'title', 'subtitle', 'introduction', 'body'
    ];

    /**
     * @var array
     */
    protected $with = ['translations', 'prices', 'category', 'category.translations', 'brand', 'shop'];

    /**
     * @var array
     */
    protected $casts = [
        'published'  =>  'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shop()
    {
        return $this->belongsTo('App\Models\Shop');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo('App\Models\Brand');
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function prices()
    {
        return $this->hasMany('App\Models\ProductPrice');
    }

    /**
     * Add attribute search criteria
     *
     * @param array $criteria
     * @return array
     */
    public static function getCriteria(array &$criteria)
    {
        $criteria['name'] = "";

        $minPrice = ProductPrice::orderBy('price', 'ASC')->first()->price;
        $maxPrice = ProductPrice::orderBy('price', 'DESC')->first()->price;
        $criteria['price']['min'] = $minPrice;
        $criteria['price']['max'] = $maxPrice;

        return $criteria;
    }

    public static function filter($products, $search)
    {
        if (isset($search['price'])) {
            $products = $products->join('product_prices', 'products.id', '=', 'product_prices.product_id')
                ->where('product_prices.price', '>=', $search['price']['min'])
                ->where('product_prices.price', '<=', $search['price']['max']);
        }

        if (isset($search['name'])) {
            $products = $products->join('product_translations', 'products.id', '=', 'product_translations.product_id')
                ->where('product_translations.title', 'LIKE', '%' . $search['name'] .'%');
        }

        return $products;
    }
}
