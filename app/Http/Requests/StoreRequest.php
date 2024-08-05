<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            // 'size' => 'required|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:10240', // 10MB limit
            // 'size' => 'required|string|max:10240', // 10MB limit
            'name' => 'required|string', // 10MB limit
            'path' => 'required|string', // 10MB limit
            'size' => 'required|file|max:10240', // 10MB limit
        ];
    }
}
