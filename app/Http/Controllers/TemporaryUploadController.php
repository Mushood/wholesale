<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\TemporaryUpload;
use Illuminate\Http\Response;

class TemporaryUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('media.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $file       = $request->file;

        $temp = TemporaryUpload::create([
            'name' => Carbon::now()->timestamp . '_' . ($file->getClientOriginalName()),
            'path' => $file->getRealPath()
        ]);

        $temp->addMedia($file->getRealPath())->toMediaCollection();

        return response()->json([], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TemporaryUpload  $temporaryUpload
     * @return \Illuminate\Http\Response
     */
    public function show(TemporaryUpload $temporaryUpload)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TemporaryUpload  $temporaryUpload
     * @return \Illuminate\Http\Response
     */
    public function edit(TemporaryUpload $temporaryUpload)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TemporaryUpload  $temporaryUpload
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TemporaryUpload $temporaryUpload)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TemporaryUpload  $temporaryUpload
     * @return \Illuminate\Http\Response
     */
    public function destroy(TemporaryUpload $temporaryUpload)
    {
        //
    }
}
