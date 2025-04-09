<?php

namespace App\Http\Requests\Admin\HeroSuperclass;

use Illuminate\Foundation\Http\FormRequest;

class StoreHeroSuperclassRequest extends FormRequest
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
        'name' => 'required|string|max:255|unique:hero_superclasses',
        'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      ];
    }
}