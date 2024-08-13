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
        // // get old file and name
        // $oldFileResource = FileResource::make($file);
        // $oldName = $oldFileResource->name;
        // $oldFile = $oldFileResource->path;
        // return $oldName . " ; " . $oldFile;

        //get new file, name
        $newFile = $request->file('file');
        $newName = $request->input('name');
        return response()->json([
            'new file' => $newFile, 
            'new name' => $newName
        ]); 

        //get new name for update
        // $newName = $request->getName();
        // $name = FileResource::make($file);
        // $oldName = $name->name;
        // $file->name = $newName;
        // $file->save();

        // return response()->json(['oldname' => $oldName, 'new name' => $newName]);

        // if (!$request->hasFile('file') || !$request->input('name')) {
        //     return response()->json(['error' => 'Missing required fields'], 400);
        // }

        // get new input, file and name
        // $newFile = $request->getNewFileContent();
        // $newName = $request->getName();


        // return $newFile . ";" . $newName;
        // if ($request->hasFile('file')) {
        //     return response()->json([
        //         "new name" => $newName,
        //         "new file" => $newFile,
        //         "old name" => $oldName,
        //         "oldFile" => $oldFile,
        //     ]);
        // }


        // if ($newFile && $oldFile) {
        //     // Delete the old file if it exists
        //     if (Storage::exists($oldFile)) {
        //         Storage::delete($oldFile);
        //     }

        //     // Store the new file and get the new file path
        //     $newFilePath = $newFile->storeAs('files', $newFile);

        //     // Update the file record with the new file path or other details
        //     $file->name = $newName;
        //     $file->path = $newFilePath;
        //     $file->save();
        // }
        // Return a response
        // return response()->json([
        //     'message' => 'File updated successfully',
        //     'new_file_path' => $newFilePath ?? $oldFile
        // ]);
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
