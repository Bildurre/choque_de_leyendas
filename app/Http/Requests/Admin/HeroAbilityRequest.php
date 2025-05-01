<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Traits\ValidatesTranslatableUniqueness;
use Illuminate\Foundation\Http\FormRequest;

class HeroAbilityRequest extends FormRequest
{
  use ValidatesTranslatableUniqueness;

  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    $heroAbilityId = $this->route('hero_ability');
    $locales = config('app.available_locales', ['es']);
    
    $rules = [
      'name' => ['required', 'array'],
      'name.es' => ['required', 'string', 'max:255'],
      'description' => ['nullable', 'array'],
      'attack_range_id' => ['required', 'exists:attack_ranges,id'],
      'attack_subtype_id' => ['required', 'exists:attack_subtypes,id'],
      'area' => ['boolean'],
      'cost' => ['required', 'string', 'max:5', 'regex:/^[RGBrgb]*$/'],
    ];

    // Agregar reglas de unicidad para cada idioma
    $rules = array_merge(
      $rules, 
      $this->uniqueTranslatableRules('hero_abilities', 'name', $heroAbilityId, $locales)
    );

    return $rules;
  }

  public function messages(): array
  {
    $messages = [
      'name.required' => 'El nombre de la habilidad es obligatorio.',
      'name.array' => 'El nombre debe ser un array con traducciones.',
      'name.es.required' => 'El nombre en español es obligatorio.',
      'attack_range_id.required' => __('abilities.attack_range') . ' ' . __('validation.required'),
      'attack_range_id.exists' => __('abilities.attack_range') . ' ' . __('validation.exists'),
      'attack_subtype_id.required' => __('abilities.attack_subtype') . ' ' . __('validation.required'),
      'attack_subtype_id.exists' => __('abilities.attack_subtype') . ' ' . __('validation.exists'),
      'cost.required' => __('abilities.cost') . ' ' . __('validation.required'),
      'cost.regex' => __('abilities.invalid_cost'),
      'cost.max' => __('abilities.cost') . ' ' . __('validation.max.string', ['max' => 5]),
    ];
    
    // Mensajes para la unicidad en cada idioma
    foreach ($locales as $locale) {
      $localeName = locale_name($locale);
      $messages["name.{$locale}.unique"] = "Ya existe una habilidad con este nombre en {$localeName}.";
    }

    return $messages;
  }
}