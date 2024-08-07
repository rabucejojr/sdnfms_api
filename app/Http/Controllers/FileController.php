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
        if ($request->hasFile('file')) {
            // Store the file
            $uploadedFile = $request->file('file');
            $file_ext = $uploadedFile->getClientOriginalExtension();
            $original_name = $uploadedFile->getClientOriginalName();
            $size = $file->getSize();

            $filePath = $uploadedFile->file('file')->storeAs('files', $original_name);
            $unique_name = $original_name . '.' . $file_ext;

            if ($size <= 10485760) {
                // Delete the old file if it exists
                if (Storage::disk('public')->exists($filePath)) {
                    (Storage::disk('public')->delete($filePath));
                }
                $file->update([
                    'name' => $original_name,
                    'path' => $filePath,
                    'size' => $size,
                    'file' => $unique_name,
                ]);
                return FileResource::make($file);
                return response()->json(['success' => 'saved'], 400);
            }
            return FileResource::make($file);
        }
    }

    // Remove the specified resource from storage.
    public function destroy(Request $request, File $file)
    {
        $filePath = $file->path;
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);

            $file->delete();
            return response()->noContent();
        } else {
            return response()->json(['error' => 'File not found'], 404);
        }
    }
}
