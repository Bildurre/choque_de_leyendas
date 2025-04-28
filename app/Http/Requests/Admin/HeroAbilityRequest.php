<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HeroAbilityRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    $locales = config('app.available_locales', ['es']);
    $rules = [
      'attack_range_id' => ['required', 'exists:attack_ranges,id'],
      'attack_subtype_id' => ['required', 'exists:attack_subtypes,id'],
      'blast' => ['boolean'],
      'cost' => ['required', 'string', 'max:5', 'regex:/^[RGBrgb]*$/'],
    ];

    // Agregar reglas de validación para cada idioma
    foreach ($locales as $locale) {
      $rules["name_translations.{$locale}"] = ['required', 'string', 'max:255'];
      $rules["description_translations.{$locale}"] = ['nullable', 'string'];
      
      // Regla unique para el nombre en cada idioma
      if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
        $heroAbilityId = $this->route('hero_ability');
        $rules["name_translations.{$locale}"][] = Rule::unique('hero_abilities', 'name->'. $locale)
          ->ignore($heroAbilityId)
          ->where(function ($query) use ($locale) {
            $query->whereRaw("JSON_EXTRACT(name, '$.{$locale}') IS NOT NULL");
          });
      } else {
        $rules["name_translations.{$locale}"][] = Rule::unique('hero_abilities', 'name->'. $locale)
          ->where(function ($query) use ($locale) {
            $query->whereRaw("JSON_EXTRACT(name, '$.{$locale}') IS NOT NULL");
          });
      }
    }

    return $rules;
  }

  public function messages(): array
  {
    $messages = [
      'attack_range_id.required' => __('abilities.attack_range') . ' ' . __('validation.required'),
      'attack_range_id.exists' => __('abilities.attack_range') . ' ' . __('validation.exists'),
      'attack_subtype_id.required' => __('abilities.attack_subtype') . ' ' . __('validation.required'),
      'attack_subtype_id.exists' => __('abilities.attack_subtype') . ' ' . __('validation.exists'),
      'cost.required' => __('abilities.cost') . ' ' . __('validation.required'),
      'cost.regex' => __('abilities.invalid_cost'),
      'cost.max' => __('abilities.cost') . ' ' . __('validation.max.string', ['max' => 5]),
    ];
    
    // Agregar mensajes de validación para cada idioma
    foreach (config('app.available_locales', ['es']) as $locale) {
      $localeName = locale_name($locale);
      
      $messages["name_translations.{$locale}.required"] = __('abilities.name') . " ({$localeName}) " . __('validation.required');
      $messages["name_translations.{$locale}.max"] = __('abilities.name') . " ({$localeName}) " . __('validation.max.string', ['max' => 255]);
      $messages["name_translations.{$locale}.unique"] = __('abilities.name') . " ({$localeName}) " . __('validation.unique');
    }

    return $messages;
  }
}