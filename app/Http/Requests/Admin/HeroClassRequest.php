<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HeroClassRequest extends FormRequest
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
    $rules = [
      'name' => [
        'required',
        'string',
        'max:255',
      ],
      'passive' => 'nullable|string',
      'hero_superclass_id' => 'required|exists:hero_superclasses,id'
    ];

    // Add unique rule for creation or update differently
    if ($this->isMethod('post')) {
      $rules['name'][] = 'unique:hero_classes';
    } else {
      $rules['name'][] = Rule::unique('hero_classes')->ignore($this->hero_class);
    }

    return $rules;
  }

  /**
   * Get custom messages for validator errors.
   */
  public function messages(): array
  {
    return [
      'name.required' => 'El nombre de la clase es obligatorio.',
      'name.unique' => 'Ya existe una clase con este nombre.',
      'hero_superclass_id.required' => 'La superclase es obligatoria.',
      'hero_superclass_id.exists' => 'La superclase seleccionada no es válida.'
    ];
  }
}