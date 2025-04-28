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
      // El nombre será un array con traducciones
      'name' => ['required', 'array'],
      'name.*' => ['string', 'max:255'],
      'name.es' => ['required', 'string', 'max:255'], // El español es obligatorio
      'passive' => ['nullable', 'array'],
      'passive.*' => ['nullable', 'string'],
      'hero_superclass_id' => ['required', 'exists:hero_superclasses,id'],
    ];

    // Para la validación de uniqueness, necesitamos un enfoque diferente 
    // cuando trabajamos con campos traducibles
    if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
      $heroClassId = $this->route('hero_class');
      // No podemos usar Rule::unique directamente con JSON
      // La validación de uniqueness se manejará a nivel de servicio
    }

    return $rules;
  }

  public function messages(): array
  {
    return [
      'name.required' => 'El nombre de la clase es obligatorio.',
      'name.*.max' => 'El nombre no puede tener más de 255 caracteres.',
      'name.es.required' => 'El nombre en español es obligatorio.',
      'hero_superclass_id.required' => 'La superclase es obligatoria.',
      'hero_superclass_id.exists' => 'La superclase seleccionada no existe.',
    ];
  }
}