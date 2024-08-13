<?php

namespace App\Http\Requests;

use App\Models\File;
use Illuminate\Foundation\Http\FormRequest;

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
            'name' => 'sometimes|string|max:255',
            'file' => 'sometimes|mimes:jpg,jpeg,png,pdf,xlsx,doc,docx|max:10485760',
        ];
    }
    /**
     * Get the old file content from storage for comparison.
     *
     * @return  File  $file
     */
    public function getNewFileContent()
    {
        return $this->file('file');
    }

    /* Get the old file content from storage for comparison.
     *
     * @param  File  $file
     * @return string|null
     */
    public function getOldFileContent(File $file)
    {
        $fileName = $file->name;
        return $fileName;

        return response()->json(['message' => 'File not found or path does not exist'], 404);
    }

    /**
     * Get the new file input name.
     *
     * @return string
     */
    public function getName()
    {
        // Return the 'name' input from the request
        return $this->input('name');
    }
}
