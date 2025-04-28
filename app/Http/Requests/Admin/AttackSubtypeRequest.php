<?php

namespace App\Http\Requests\Admin;

use App\Models\Traits\ValidatesTranslatableUniqueness;
use Illuminate\Foundation\Http\FormRequest;

class AttackSubtypeRequest extends FormRequest
{
  use ValidatesTranslatableUniqueness;

  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    $attackSubtypeId = $this->route('attack_subtype');
    $locales = config('app.available_locales', ['es']);
    
    $rules = [
      'name' => ['required', 'array'],
      'name.es' => ['required', 'string', 'max:255'],
      'type' => ['required', 'in:physical,magical'],
    ];

    // Agregar reglas de unicidad para cada idioma
    $rules = array_merge(
      $rules, 
      $this->uniqueTranslatableRules('attack_subtypes', 'name', $attackSubtypeId, $locales)
    );

    return $rules;
  }

  public function messages(): array
  {
    $messages = [
      'name.required' => 'El nombre del subtipo de ataque es obligatorio.',
      'name.array' => 'El nombre debe ser un array con traducciones.',
      'name.es.required' => 'El nombre en español es obligatorio.',
      'type.required' => 'El tipo de ataque es obligatorio.',
      'type.in' => 'El tipo debe ser físico o mágico.',
    ];
    
    // Mensajes para la unicidad en cada idioma
    foreach (config('app.available_locales', ['es']) as $locale) {
      $localeName = locale_name($locale);
      $messages["name.{$locale}.unique"] = "Ya existe un subtipo de ataque con este nombre en {$localeName}.";
    }
    
    return $messages;
  }
}