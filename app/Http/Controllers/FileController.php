<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Http\Requests\UpdateRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Http\Request;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return FileResource::collection(File::all());
    }

    /**
     * Show the form for creating a new resource.
     */

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $task = File::create($request->validated());
        return FileResource::make($task);
    }

    /**
     * Display the specified resource.
     */
    public function show(File $file,)
    {
        //
        return FileResource::make($file);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, File $file)
    {
        //
        $file->update($request->validated());
        return FileResource::make($file);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        //
        $file->delete();
        return response()->noContent();
    }
}
