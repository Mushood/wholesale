<?php

namespace App\Traits;

use App\Models\Blog;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

trait Publishable
{
    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     * @throws \Exception
     */
    public function publish(Request $request, $id)
    {
        $model = $this->getModel($request, $id);
        $model->published = true;
        $model->save();

        if ($request->wantsJson()) {

            return response()->json([] , Response::HTTP_OK);
        }

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     * @throws \Exception
     */
    public function unpublish(Request $request, $id)
    {
        $model = $this->getModel($request, $id);
        $model->published = false;
        $model->save();

        if ($request->wantsJson()) {

            return response()->json([] , Response::HTTP_OK);
        }

        return redirect()->back();
    }

    /**
     * @param $request
     * @param $id
     * @return |null
     * @throws \Exception
     */
    public function getModel($request, $id)
    {
        $url    = $request->getPathInfo();
        $model  = null;

        if (Str::contains($url, 'blog')) {
            $model = Blog::find($id);
        }

        if (Str::contains($url, 'category')) {
            $model = Category::find($id);
        }

        if ($model === null) {
            throw new \Exception('No Model found');
        }

        return $model;
    }
}