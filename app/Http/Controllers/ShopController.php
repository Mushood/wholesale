<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Traits\Publishable;
use App\Http\Requests\ShopRequest;
use App\Http\Resources\ShopResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ShopController extends BaseController
{
    use Publishable;

    public function setClassModel()
    {
        $this->className = Shop::class;
    }

    public function setClassResource()
    {
        $this->classResource = ShopResource::class;
    }

    public function setViewFolder()
    {
        $this->viewFolder = 'shop';
    }

    public function setVariableNameSingular()
    {
        $this->variableNameSingular = 'shop';
    }

    public function setVariableNamePlural()
    {
        $this->variableNamePlural = 'shops';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ShopRequest $request
     * @return ShopResource|\Illuminate\Http\RedirectResponse
     */
    public function store(ShopRequest $request)
    {
        $validated = $request->validated();

        $image = $validated['image'];
        unset($validated['image']);
        $shop = Shop::create($validated);
        $shop->addMedia($image)->toMediaCollection();

        if ($request->wantsJson()) {

            return new ShopResource($shop);
        }

        return redirect()->route('shop.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ShopRequest $request
     * @param $shop
     * @return BlogResource|\Illuminate\Http\RedirectResponse
     */
    public function update(ShopRequest $request, $shop)
    {
        $validated = $request->validated();

        $image = $validated['image'];
        unset($validated['image']);
        $shop->update($validated);
        $shop->addMedia($image)->toMediaCollection();

        if ($request->wantsJson()) {

            return new ShopResource($shop);
        }

        return redirect()->route('shop.index');
    }

    public function assignUser(Request $request, $shop, $user)
    {
        $user->shop_id = $shop->id;
        $user->save();

        if ($request->wantsJson()) {

            return response()->json([] , Response::HTTP_OK);
        }

        return redirect()->route($this->viewFolder . '.index');
    }

}
