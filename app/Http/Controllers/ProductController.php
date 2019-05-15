<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Traits\Publishable;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    use Publishable;

    public function setClassModel()
    {
        $this->className = Product::class;
    }

    public function setClassResource()
    {
        $this->classResource = ProductResource::class;
    }

    public function setViewFolder()
    {
        $this->viewFolder = 'product';
    }

    public function setVariableNameSingular()
    {
        $this->variableNameSingular = 'product';
    }

    public function setVariableNamePlural()
    {
        $this->variableNamePlural = 'products';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(ProductRequest $request)
    {
        $validated = $request->validated();

        $image = $validated['image'];
        unset($validated['image']);
        $product = Product::create($validated);
        $product->addMedia($image)->toMediaCollection();

        if ($request->wantsJson()) {

            return new ProductResource($product);
        }

        return redirect()->route('product.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(ProductRequest $request, $product)
    {
        $validated = $request->validated();

        $image = $validated['image'];
        unset($validated['image']);
        $product->update($validated);
        $product->addMedia($image)->toMediaCollection();

        if ($request->wantsJson()) {

            return new ProductResource($product);
        }

        return redirect()->route('product.index');
    }

}
