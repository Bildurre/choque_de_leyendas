<?php

namespace App\Http\Requests\Game;

use App\Http\Requests\Traits\ValidatesTranslatableUniqueness;
use Illuminate\Foundation\Http\FormRequest;

class HeroAbilityRequest extends FormRequest
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
    $heroAbilityId = $this->route('hero_ability');
    $locales = config('app.available_locales', ['es']);
    
    $rules = [
      'name' => ['required', 'array'],
      'name.es' => ['required', 'string', 'max:255'],
      'description' => ['nullable', 'array'],
      'attack_range_id' => ['nullable', 'exists:attack_ranges,id'],
      'attack_subtype_id' => ['nullable', 'exists:attack_subtypes,id'],
      'area' => ['nullable', 'boolean'],
      'cost' => ['required', 'string', 'max:5', 'regex:/^[RGBrgb]*$/'],
    ];

    // Validation for attack_range_id and attack_subtype_id
    $rules['attack_subtype_id'][] = function ($attribute, $value, $fail) {
      $rangeId = $this->input('attack_range_id');
      
      // Both or none should be provided
      if (($value === null && $rangeId !== null) || ($value !== null && $rangeId === null)) {
        $fail(__('hero_abilities.validation.attack_range_and_subtype_together'));
      }
    };

    // Add uniqueness rules for each locale
    $rules = array_merge(
      $rules, 
      $this->uniqueTranslatableRules('hero_abilities', 'name', $heroAbilityId, $locales)
    );

    return $rules;
  }

  /**
   * Get custom validation messages.
   */
  public function messages(): array
  {
    $messages = [
      'name.required' => 'El nombre de la habilidad es obligatorio.',
      'name.array' => __('validation.array', ['attribute' => __('common.name')]),
      'name.es.required' => __('validation.required', ['attribute' => __('common.name'). ' ' . __('in_spanish')]),
      'attack_range_id.exists' => 'El rango de ataque seleccionado no existe.',
      'attack_subtype_id.exists' => 'El subtipo de ataque seleccionado no existe.',
      'area.boolean' => 'El campo de área debe ser verdadero o falso.',
      'cost.required' => 'El coste es obligatorio.',
      'cost.string' => 'El coste debe ser una cadena de texto.',
      'cost.max' => 'El coste no puede tener más de 5 caracteres.',
      'cost.regex' => 'El coste solo puede contener los caracteres R, G y B.',
    ];

    // Messages for uniqueness in each language
    foreach (config('app.available_locales', ['es']) as $locale) {
      $localeName = locale_name($locale);
      $messages["name.{$locale}.unique"] = "Ya existe una habilidad con este nombre en {$localeName}.";
    }

    return $messages;
  }
}