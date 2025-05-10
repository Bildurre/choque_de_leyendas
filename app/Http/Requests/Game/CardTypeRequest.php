<?php

namespace App\Http\Requests\Game;

use App\Http\Requests\Traits\ValidatesTranslatableUniqueness;
use Illuminate\Foundation\Http\FormRequest;

class CardTypeRequest extends FormRequest
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
    $cardTypeId = $this->route('card_type');
    $locales = config('app.available_locales', ['es']);
    
    $rules = [
      'name' => ['required', 'array'],
      'name.es' => ['required', 'string', 'max:255'],
      'hero_superclass_id' => ['nullable', 'exists:hero_superclasses,id'],
    ];

    // Validación única para superclase
    if ($this->hero_superclass_id) {
      // Validamos que no exista otro tipo de carta con esta superclase (excluyendo el actual)
      $rules['hero_superclass_id'][] = function ($attribute, $value, $fail) use ($cardTypeId) {
        $query = \App\Models\CardType::where('hero_superclass_id', $value);
        
        if ($cardTypeId) {
          $query->where('id', '!=', $cardTypeId);
        }
        
        if ($query->exists()) {
          $fail('La superclase seleccionada ya está asignada a otro tipo de carta.');
        }
      };
    }

    // Agregar reglas de unicidad para cada idioma
    $rules = array_merge(
      $rules, 
      $this->uniqueTranslatableRules('card_types', 'name', $cardTypeId, $locales)
    );

    return $rules;
  }

  /**
   * Get custom validation messages.
   */
  public function messages(): array
  {
    $messages = [
      'name.required' => 'El nombre del tipo de carta es obligatorio.',
      'name.array' => 'El nombre debe ser un array con traducciones.',
      'name.es.required' => 'El nombre en español es obligatorio.',
      'hero_superclass_id.exists' => 'La superclase seleccionada no existe.',
    ];

    // Mensajes para la unicidad en cada idioma
    foreach (config('app.available_locales', ['es']) as $locale) {
      $localeName = locale_name($locale);
      $messages["name.{$locale}.unique"] = "Ya existe un tipo de carta con este nombre en {$localeName}.";
    }

    return $messages;
  }
}