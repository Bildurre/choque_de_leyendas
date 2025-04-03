<?php

namespace App\Http\Requests\Admin\HeroClass;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateHeroClassRequest extends FormRequest
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
            Rule::unique('hero_classes')->ignore($this->heroClass->id)
          ]
        ];
    }

      /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
      return [
        'passive' => 'nullable|string',
        'superclass_id' => 'required|exists:superclasses,id',
        'agility_modifier' => 'required|integer|between:-2,2',
        'mental_modifier' => 'required|integer|between:-2,2',
        'will_modifier' => 'required|integer|between:-2,2',
        'strength_modifier' => 'required|integer|between:-2,2',
        'armor_modifier' => 'required|integer|between:-2,2'
      ];
    }
}