<?php

namespace App\Http\Requests\Admin\HeroClass;

use Illuminate\Foundation\Http\FormRequest;

class StoreHeroClassRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'name' => 'required|string|max:255|unique:hero_classes',
      'passive' => 'nullable|string',
      'hero_superclass_id' => 'required|exists:hero_superclasses,id',
    ];
  }
  
  public function messages(): array
  {
    return [
      'name.required' => 'El nombre de la clase es obligatorio.',
      'name.unique' => 'Ya existe una clase con este nombre.',
      'hero_superclass_id.required' => 'La superclase es obligatoria.',
      'hero_superclass_id.exists' => 'La superclase seleccionada no es válida.',
    ];
  }
}