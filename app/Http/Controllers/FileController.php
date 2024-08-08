<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Http\Requests\UpdateRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Http\Client\Request;
use Illuminate\Http\JsonResponse;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
            $original_name = $file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('files', $original_name);
            $unique_name = $original_name . '.' . $file_ext;
            $size = $file->getSize();

            // Check if filesize is greater than 10mb (10485760 bytes)
            if ($size > 10485760 and Storage::exists($filePath)) {
                return response()->json(['error' => 'File exists/too large...'], 400);
            } else {
                $file = File::create([
                    'name' => $original_name, // Retain the original filename
                    'path' => $filePath, // Store the path to access the file
                    'size' => $size, // File size
                    'file' => $unique_name,
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

        $file->name = $request->validated();
        // Log the request data for debugging
        Log::info('Request data:', $request->all());
        // delete file
        $oldFile = $file->name;
        Storage::disk('public')->delete($oldFile);

        // Store the new file
        $newFile = $request->file('file');
        return response()->json($newFile);
        // var_dump($newFile);
        
        // if ($newFile) {
        //     $newFileName = $newFile->getClientOriginalName();
        //     $newFilePath = $newFile->storeAs('files', $newFileName);

        //     $file->path = $newFilePath;
        //     return FileResource::make($file);
        //     return response()->json(['success' => 'info updated successfully']);
        // } else {
        //     return response()->json(['error' => 'File upload failed'], 400);
        // }
    }

    // Remove the specified resource from storage.
    public function destroy(File $file)
    {
        // Retrieve the file path from the database
        $filePath = 'files/' . $file->name;
        // Delete the file from storage
        Storage::disk('public')->delete($filePath);
        return response()->json(['success' => 'Deleted successfully']);
    }
}
