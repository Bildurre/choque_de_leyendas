<?php

namespace App\Http\Requests\Admin\HeroSuperclass;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateHeroSuperclassRequest extends FormRequest
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
        'name' => [
          'required',
          'string',
          'max:255',
          Rule::unique('hero_superclasses')->ignore($this->heroSuperclass->id)
        ],
        'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'remove_icon' => 'nullable|boolean',
      ];
    }
}