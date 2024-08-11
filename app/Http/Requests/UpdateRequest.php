<?php

namespace App\Http\Requests;

use App\Models\File;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *  
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => 'sometimes|mimes:jpg,jpeg,png,pdf,xlsx,doc,docx|max:10485760',
        ];
    }
    /**
     * Get the old file content from storage for comparison.
     *
     * @param  File  $file
     * @return string|null
     */
    public function getOldFileContent(File $file)
    {
        if (Storage::disk('public')->exists($file->path)) {
            // Get the file's name from the path
            $fileName = basename($file->path);
            return response()->json(['name' => $fileName], 200);
        }

        return response()->json(['message' => 'File not found or path does not exist'], 404);
    }

    /**
     * Get the new file input.
     *
     * @return \Illuminate\Http\UploadedFile|null
     */
    public function getNewFile()
    {
        return $this->file('file');
    }
}
