<?php

namespace App\Services\Searchable;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

class SearchableManager
{
    protected $searchable;

    protected $criteria;

    /**
     * SearchableManager constructor.
     */
    public function __construct()
    {
        $this->criteria = [];

        $this->searchable = [
            Brand::class,
            Product::class,
            Category::class,
        ];
    }

    /**
     * Get Criteria
     *
     * @return array
     */
    public function getCriteria()
    {
        $this->buildCriteria();

        return $this->criteria;
    }

    public function filter($search)
    {
        $products = Product::where('published', true);

        foreach ($this->searchable as $searchable) {
            $products = $searchable::filter($products, $search);
        }

        return $products->paginate(Controller::PAGINATION);
    }

    /**
     * Call getCriteria on searchable classes
     */
    private function buildCriteria()
    {
        foreach ($this->searchable as $searchable) {
            $searchable::getCriteria($this->criteria);
        }
    }
}
