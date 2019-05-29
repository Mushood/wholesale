<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function welcome(Request $request)
    {

        return view('welcome');
    }

    public function category(Request $request, $categoryTrans)
    {
        $products = $categoryTrans->category->products()->latest()->paginate(SELF::PAGINATION);

        if ($request->wantsJson()) {

            return ProductResource::collection($products);
        }

        return view('category.index', [
            'products' => $products,
            'category' => $categoryTrans->category
        ]);
    }

    public function contact(Request $request)
    {

        return view('contact');
    }
}
