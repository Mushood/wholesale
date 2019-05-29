<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogRequest;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use App\Models\BlogTranslation;
use App\Models\CategoryTranslation;
use App\Traits\Publishable;
use Illuminate\Http\Request;

class BlogController extends BaseController
{
    use Publishable;

    public function setClassModel()
    {
        $this->className = Blog::class;
    }

    public function setClassResource()
    {
        $this->classResource = BlogResource::class;
    }

    public function setViewFolder()
    {
        $this->viewFolder = 'blog';
    }

    public function setVariableNameSingular()
    {
        $this->variableNameSingular = 'blog';
    }

    public function setVariableNamePlural()
    {
        $this->variableNamePlural = 'blogs';
    }

    /**
     * @param Request $request
     * @param $model
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showSlug(Request $request, $slug)
    {
        $blog = BlogTranslation::where('slug', $slug)->first()->blog;
        if ($request->wantsJson()) {

            return new BlogResource($blog);
        }

        return view('blog.show', [
            'blog' => $blog
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BlogRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(BlogRequest $request)
    {
        $validated = $request->validated();

        $image = $validated['image'];
        unset($validated['image']);
        $blog = Blog::create($validated);
        $blog->addMedia($image)->toMediaCollection();

        if ($request->wantsJson()) {

            return new BlogResource($blog);
        }

        return redirect()->route('blog.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Blog $blog
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(BlogRequest $request, $blog)
    {
        $validated = $request->validated();

        $image = $validated['image'];
        unset($validated['image']);
        $blog->update($validated);
        $blog->addMedia($image)->toMediaCollection();

        if ($request->wantsJson()) {

            return new BlogResource($blog);
        }

        return redirect()->route('blog.index');
    }

    public function category(Request $request, $categoryTrans)
    {
        $blogs = $categoryTrans->category->blogs()->latest()->paginate(SELF::PAGINATION);

        if ($request->wantsJson()) {

            return BlogResource::collection($blogs);
        }

        return view('blog.index', [
            'blogs' => $blogs,
            'category' => $categoryTrans->category
        ]);
    }

}
