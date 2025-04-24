<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HeroClassRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    $rules = [
      'name' => ['required', 'string', 'max:255'],
      'passive' => ['nullable', 'string'],
      'hero_superclass_id' => ['required', 'exists:hero_superclasses,id'],
    ];

    if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
      $heroClassId = $this->route('hero_class');
      $rules['name'][] = Rule::unique('hero_classes')->ignore($heroClassId);
    } else {
      $rules['name'][] = 'unique:hero_classes,name';
    }

    return $rules;
  }

  public function messages(): array
  {
    return [
      'name.required' => 'El nombre de la clase es obligatorio.',
      'name.unique' => 'Ya existe una clase con este nombre.',
      'hero_superclass_id.required' => 'La superclase es obligatoria.',
      'hero_superclass_id.exists' => 'La superclase seleccionada no existe.',
    ];
  }
}