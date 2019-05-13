<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\Publishable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    use Publishable;

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $categories = Category::latest()->paginate(SELF::PAGINATION);

        if ($request->wantsJson()) {

            return CategoryResource::collection($categories);
        }

        return view('category.index', compact('categories'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {

        return view('category.create');
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
     * @param Request $request
     * @param Category $category
     * @return CategoryResource|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, Category $category)
    {
        if ($request->wantsJson()) {

            return new CategoryResource($category);
        }

        return view('category.show', compact('category'));
    }

    /**
     * @param Request $request
     * @param Category $category
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, Category $category)
    {

        return view('category.create', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Blog $blog
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(CategoryRequest $request, Category $category)
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

    /**
     * @param Request $request
     * @param Category $category
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Request $request, Category $category)
    {
        $category->delete();

        if ($request->wantsJson()) {

            return response()->json([] , Response::HTTP_OK);
        }

        return redirect()->route('category.index');
    }
}
