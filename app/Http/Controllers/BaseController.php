<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

abstract class BaseController extends Controller
{
    protected $className;
    protected $classResource;
    protected $viewFolder;
    protected $variableNameSingular;
    protected $variableNamePlural;

    abstract public function setClassModel();

    abstract public function setClassResource();

    abstract public function setViewFolder();

    abstract public function setVariableNameSingular();

    abstract public function setVariableNamePlural();

    public function __construct()
    {
        $this->setClassModel();
        $this->setClassResource();
        $this->setViewFolder();
        $this->setVariableNameSingular();
        $this->setVariableNamePlural();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $data = $this->className::latest()->paginate(SELF::PAGINATION);

        if ($request->wantsJson()) {

            return $this->classResource::collection($data);
        }

        return view($this->viewFolder . '.index', [
            $this->variableNamePlural => $data
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {

        return view($this->viewFolder . '.create');
    }

    /**
     * @param Request $request
     * @param $model
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, $model)
    {
        if ($request->wantsJson()) {

            return new $this->classResource($model);
        }

        return view($this->viewFolder . '.show', [
            $this->variableNameSingular => $model
        ]);
    }

    /**
     * @param Request $request
     * @param Blog $model
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $model)
    {

        return view($this->viewFolder . '.create', [
            $this->variableNameSingular => $model
        ]);
    }


    /**
     * @param Request $request
     * @param $model
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $model)
    {
        $model->delete();

        if ($request->wantsJson()) {

            return response()->json([] , Response::HTTP_OK);
        }

        return redirect()->route($this->viewFolder . '.index');
    }
}
