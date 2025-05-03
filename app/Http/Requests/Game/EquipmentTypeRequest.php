<?php

namespace App\Http\Requests\Game;

use App\Http\Requests\Traits\ValidatesTranslatableUniqueness;
use Illuminate\Foundation\Http\FormRequest;

class EquipmentTypeRequest extends FormRequest
{
  use ValidatesTranslatableUniqueness;

  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   */
  public function rules(): array
  {
    $equipmentTypeId = $this->route('equipment_type');
    $locales = config('app.available_locales', ['es']);
    
    $rules = [
      'name' => ['required', 'array'],
      'name.es' => ['required', 'string', 'max:255'],
      'category' => ['required', 'in:weapon,armor'],
    ];

    // Agregar reglas de unicidad para cada idioma
    $rules = array_merge(
      $rules, 
      $this->uniqueTranslatableRules('equipment_types', 'name', $equipmentTypeId, $locales)
    );

    return $rules;
  }
  
  /**
   * Get custom messages for validator errors.
   */
  public function messages(): array
  {
    $messages = [
      'name.required' => 'El nombre del tipo de equipo es obligatorio.',
      'name.array' => 'El nombre debe ser un array con traducciones.',
      'name.es.required' => 'El nombre en español es obligatorio.',
      'category.required' => 'La categoría del tipo de equipo es obligatoria.',
      'category.in' => 'La categoría debe ser arma o armadura.',
    ];

    // Mensajes para la unicidad en cada idioma
    foreach (config('app.available_locales', ['es']) as $locale) {
      $localeName = locale_name($locale);
      $messages["name.{$locale}.unique"] = "Ya existe un tipo de equipo con este nombre en {$localeName}.";
    }

    return $messages;
  }
}