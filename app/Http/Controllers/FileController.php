<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileValidationRequest;
use App\Http\Requests\StoreRequest;
use App\Http\Requests\UpdateRequest;
use App\Http\Resources\FileResource;
use App\Models\File;

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
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $request->validateResolved();
        if ($request->hasFile('file')) {
            // Store the file
            $file = $request->file('file');
            $file_ext = $file->getClientOriginalExtension();
            $original_name = $file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('files', $original_name);
            $unique_name = $original_name . '.' . $file_ext;
            $size = $file->getSize();

            if ($size <= 10240) {
                $file = File::create([
                    'name' => $original_name, // Retain the original filename
                    'path' => $filePath, // Store the path to access the file
                    'size' => $size, // File size
                    'file' => $unique_name,
                ]);
                return FileResource::make($file);
            } else {
                return response()->json(['error' => 'File exceeds the maximum allowed size of 10MB'], 400);
            }
        }
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
