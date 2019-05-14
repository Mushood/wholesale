<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use App\Traits\Publishable;

class BrandController extends BaseController
{
    use Publishable;

    public function setClassModel()
    {
        $this->className = Brand::class;
    }

    public function setClassResource()
    {
        $this->classResource = BrandResource::class;
    }

    public function setViewFolder()
    {
        $this->viewFolder = 'brand';
    }

    public function setVariableNameSingular()
    {
        $this->variableNameSingular = 'brand';
    }

    public function setVariableNamePlural()
    {
        $this->variableNamePlural = 'brands';
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param BrandRequest $request
     * @return BrandResource|\Illuminate\Http\RedirectResponse
     */
    public function store(BrandRequest $request)
    {
        $validated = $request->validated();

        $image = $validated['image'];
        unset($validated['image']);
        $brand = Brand::create($validated);
        $brand->addMedia($image)->toMediaCollection();

        if ($request->wantsJson()) {

            return new BrandResource($brand);
        }

        return redirect()->route('brand.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BrandRequest $request
     * @param $brand
     * @return BlogResource|\Illuminate\Http\RedirectResponse
     */
    public function update(BrandRequest $request, $brand)
    {
        $validated = $request->validated();

        $image = $validated['image'];
        unset($validated['image']);
        $brand->update($validated);
        $brand->addMedia($image)->toMediaCollection();

        if ($request->wantsJson()) {

            return new BrandResource($brand);
        }

        return redirect()->route('brand.index');
    }

}
