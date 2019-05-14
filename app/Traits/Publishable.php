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
     * @param $model
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function publish(Request $request, $model)
    {
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
    public function unpublish(Request $request, $model)
    {
        $model->published = false;
        $model->save();

        if ($request->wantsJson()) {

            return response()->json([] , Response::HTTP_OK);
        }

        return redirect()->back();
    }
}