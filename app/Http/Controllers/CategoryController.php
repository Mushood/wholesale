<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\Publishable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends BaseController
{
    use Publishable;

    public function setClassModel()
    {
        $this->className = Category::class;
    }

    public function setClassResource()
    {
        $this->classResource = CategoryResource::class;
    }

    public function setViewFolder()
    {
        $this->viewFolder = 'category';
    }

    public function setVariableNameSingular()
    {
        $this->variableNameSingular = 'category';
    }

    public function setVariableNamePlural()
    {
        $this->variableNamePlural = 'allCategories';
    }


    /**
     * @param CategoryRequest $request
     * @return BlogResource|\Illuminate\Http\RedirectResponse
     */
    public function store(CategoryRequest $request)
    {
        $validated = $request->validated();

        $image = $validated['image'];
        unset($validated['image']);
        $category = Category::create($validated);
        $category->addMedia($image)->toMediaCollection();

        if ($request->wantsJson()) {

            return new CategoryResource($category);
        }

        return redirect()->route('category.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Blog $blog
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(CategoryRequest $request, $category)
    {
        $validated = $request->validated();

        $image = $validated['image'];
        unset($validated['image']);
        $category->update($validated);
        $category->addMedia($image)->toMediaCollection();

        if ($request->wantsJson()) {

            return new CategoryResource($category);
        }

        return redirect()->route('category.index');
    }
}
