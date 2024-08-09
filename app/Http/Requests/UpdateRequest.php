<?php

namespace App\Http\Requests;

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
            'file' => 'required|mimes:jpg,jpeg,png,pdf,xlsx|max:10485760',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'file.required' => 'The file field is required.',
            'file.file' => 'The file must be a valid file.',
            'file.mimes' => 'The file must be a file of type: jpeg, png, pdf, docx , xlsx',
            'file.max' => 'The file may not be greater than 10MB.',
        ];
    }
}
