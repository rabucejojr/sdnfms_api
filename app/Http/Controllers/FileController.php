<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Http\Requests\UpdateRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $file = File::all();
        if ($file->isEmpty()) {
            return response()->json(['message' => 'No files found.'], 404);
        }
        return FileResource::collection($file);
    }

    // Store a newly created resource in storage.
    public function store(StoreRequest $request)
    {
        if ($request->hasFile('file')) {
            // Store the file
            $file = $request->file('file');
            $file_ext = $file->getClientOriginalExtension();
            $name = $request->name;
            $original_name = $file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('files', $original_name);
            $unique_name = $original_name . '.' . $file_ext;
            $size = $file->getSize();

            // Check if filesize is greater than 10mb (10485760 bytes)
            if ($size > 10485760 and Storage::exists($filePath)) {
                return response()->json(['error' => 'File exists/too large...'], 400);
            } else {
                $file = File::create([
                    'name' => $name, // Retain the original filename
                    'path' => $filePath, // Store the path to access the file
                    'size' => $size, // File size
                    'file' => $original_name,
                ]);
                return FileResource::make($file);
            }
        }
    }

    // Display the specified resource.
    public function show(File $file)
    {
        return FileResource::make($file);
    }

    // Update the specified resource in storage.
    public function update(UpdateRequest $request, File $file)
    {
        // Get new file
        $newFile = $request->file('file');
        $new_name = $request->name;

        // Get the original file path before update
        $originalFilePath = $file->path;

        // Delete the old file from storage if it exists
        if (Storage::exists($originalFilePath)) {
            Storage::delete($originalFilePath);
        }

        // Store the new file and update file details
        $original_name = $newFile->getClientOriginalName();
        $new_filepath = $newFile->storeAs('files', $original_name);

        $size = $newFile->getSize();

        // Validate file size
        if ($size > 10485760) {
            return response()->json(['error' => 'File too large...'], 400);
        }

        // Update file details in the database
        $file->name = $new_name;
        $file->path = $new_filepath;
        $file->size = $size;

        $file->save();

        return FileResource::make($file);
    }


    // Remove the specified resource from storage.
    public function destroy(File $file)
    {
        // Retrieve the file path from the database
        $filePath = $file->path;
        // Delete the file from storage
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        // Delete the file record from the database
        $file->delete();
        return response()->json(['success' => 'Deleted successfully']);
    }
}
