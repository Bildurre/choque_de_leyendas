<?php

namespace App\Http\Requests\Game;

use App\Http\Requests\Traits\ValidatesTranslatableUniqueness;
use Illuminate\Foundation\Http\FormRequest;

class HeroRaceRequest extends FormRequest
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
    $heroRaceId = $this->route('hero_race');
    $locales = config('app.available_locales', ['es']);
    
    $rules = [
      'name' => ['required', 'array'],
      'name.es' => ['required', 'string', 'max:255'],
    ];

    // Agregar reglas de unicidad para cada idioma
    $rules = array_merge(
      $rules, 
      $this->uniqueTranslatableRules('hero_races', 'name', $heroRaceId, $locales)
    );

    return $rules;
  }

  /**
   * Get custom validation messages.
   */
  public function messages(): array
  {
    $messages = [
      'name.required' => 'El nombre de la raza es obligatorio.',
      'name.array' => '__('validation.array', ['attribute' => __('common.name')])',
      'name.es.required' => '__('validation.required', ['attribute' => __('common.name'). ' ' . __('in_spanish')])',
    ];

    // Mensajes para la unicidad en cada idioma
    foreach (config('app.available_locales', ['es']) as $locale) {
      $localeName = locale_name($locale);
      $messages["name.{$locale}.unique"] = "Ya existe una raza con este nombre en {$localeName}.";
    }

    return $messages;
  }
}