<?php

namespace App\Http\Requests;

use App\Http\Resources\FileResource;
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
     * Get the new file input name.
     *
     * @return int
     */
    public function getId(File $file)
    {
        $file = FileResource::make($file);
        $fileId = $file->id;
        return $fileId;
    }
}
