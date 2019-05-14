<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogRequest;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use App\Traits\Publishable;
use Illuminate\Http\Request;

class BlogController extends BaseController
{
    use Publishable;

    public function __construct()
    {
        $this->setClassModel();
        $this->setClassResource();
        $this->setViewFolder();
        $this->setVariableNameSingular();
        $this->setVariableNamePlural();
    }

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
     * Store a newly created resource in storage.
     *
     * @param BlogRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(BlogRequest $request)
    {
        $validated = $request->validated();

        $blog = Blog::create($validated);
        $blog->addMedia($validated['image'])->toMediaCollection();

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
    public function update(BlogRequest $request, Blog $blog)
    {
        $validated = $request->validated();

        $blog->update($validated);
        $blog->addMedia($validated['image'])->toMediaCollection();

        if ($request->wantsJson()) {

            return new BlogResource($blog);
        }

        return redirect()->route('blog.index');
    }

}
