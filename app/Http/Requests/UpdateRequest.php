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
     * Get the file input from the request.
     *
     * @return string
     */
    public function getName()
    {
        return $this->input('name');
    }

    /**
     * Get the file input from the request.
     *
     * @return \Illuminate\Http\UploadedFile|null
     */
    public function getFile()
    {
        return $this->file('file');
    }
}
