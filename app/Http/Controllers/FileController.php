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
        
        // $request = req::instance();
        // // $req = request();
        // }

        // $file = $request->user()->files()->create($request->validated());
        // return FileResource::make($file);

    }

    /**
     * Display the specified resource.
     */
    public function show(File $file)
    {
        //
        return FileResource::make($file);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
