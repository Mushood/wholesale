<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogRequest;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $blogs = Blog::latest()->paginate(SELF::PAGINATION);

        if ($request->wantsJson()) {

            return BlogResource::collection($blogs);
        }

        return view('blog.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {

        return view('blog.create');
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

        if ($request->wantsJson()) {

            return new BlogResource($blog);
        }

        return redirect()->route('blog.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Blog $blog
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function show(Request $request, Blog $blog)
    {
        if ($request->wantsJson()) {

            return new BlogResource($blog);
        }

        return view('blog.show', compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param BlogRequest $request
     * @param Blog $blog
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function edit(BlogRequest $request, Blog $blog)
    {

        return view('blog.create', compact('blog'));
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

        if ($request->wantsJson()) {

            return new BlogResource($blog);
        }

        return redirect()->route('blog.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Blog $blog
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Request $request, Blog $blog)
    {
        $blog->delete();

        if ($request->wantsJson()) {

            return response()->json([] , Response::HTTP_OK);
        }


        return redirect()->route('blog.index');
    }
}
