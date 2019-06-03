<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\Searchable\SearchableManager;

class SearchController extends Controller
{
    protected $manager;

    /**
     * SearchController constructor.
     * @param $manager
     */
    public function __construct(SearchableManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Get Search Criteria
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSearchCriteria()
    {
        $searchCriteria = $this->manager->getCriteria();

        return response()->json($searchCriteria, Response::HTTP_OK);
    }

    public function search(Request $request)
    {
        $search     = $request->search;
        $products   = $this->manager->filter($search);

        return response()->json($products, Response::HTTP_OK);
    }
}
