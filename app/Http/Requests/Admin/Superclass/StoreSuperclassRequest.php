<?php

namespace App\Http\Requests\Admin\Superclass;

use Illuminate\Foundation\Http\FormRequest;

class StoreSuperclassRequest extends FormRequest
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
        'name' => 'required|string|max:255|unique:superclasses',
        'description' => 'nullable|string',
        'color' => 'required|string|max:7|regex:/^#[0-9A-F]{6}$/i',
      ];
    }
}
