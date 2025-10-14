<?php

namespace App\Http\Requests\Game;

use App\Http\Requests\Traits\ValidatesTranslatableUniqueness;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
    $locales = array_keys(config('laravellocalization.supportedLocales', ['es' => []]));
    
    $rules = [
      'name' => ['required', 'array'],
      'name.es' => ['required', 'string', 'max:255'],
      'description' => ['nullable', 'array'],
      'attack_type' => ['nullable', Rule::in(['physical', 'magical'])],
      'attack_range_id' => ['nullable', 'exists:attack_ranges,id'],
      'attack_subtype_id' => ['nullable', 'exists:attack_subtypes,id'],
      'area' => ['nullable', 'boolean'],
      'cost' => ['required', 'string', 'max:5', 'regex:/^[RGBrgb]*$/'],
    ];

    // Validation for attack fields consistency
    $rules['attack_type'][] = function ($attribute, $value, $fail) {
      $rangeId = $this->input('attack_range_id');
      $subtypeId = $this->input('attack_subtype_id');
      
      // If attack_type is set, both range and subtype should be set
      if ($value !== null && ($rangeId === null || $subtypeId === null)) {
        $fail(__('hero_abilities.validation.attack_type_requires_range_and_subtype'));
      }
      
      // If range or subtype are set, attack_type should be set
      if ($value === null && ($rangeId !== null || $subtypeId !== null)) {
        $fail(__('hero_abilities.validation.range_and_subtype_require_attack_type'));
      }
    };

    $rules['attack_subtype_id'][] = function ($attribute, $value, $fail) {
      $rangeId = $this->input('attack_range_id');
      
      // Both or none should be provided
      if (($value === null && $rangeId !== null) || ($value !== null && $rangeId === null)) {
        $fail(__('hero_abilities.validation.attack_range_and_subtype_together'));
      }
    };

    $rules = array_merge(
      $rules, 
      $this->uniqueTranslatableRules('hero_abilities', 'name', $heroAbilityId, $locales)
    );

    return $rules;
  }

  public function messages(): array
  {
    $messages = [
      'name.required' => __('validation.required', ['attribute' => __('entities.hero_abilities.name')]),
      'name.array' => __('validation.array', ['attribute' => __('common.name')]),
      'name.es.required' => __('validation.required', ['attribute' => __('common.name'). ' ' . __('in_spanish')]),
      'attack_type.in' => __('validation.in', ['attribute' => __('entities.hero_abilities.attack_type')]),
      'attack_range_id.exists' => __('validation.exists', ['attribute' => __('entities.attack_ranges.singular')]),
      'attack_subtype_id.exists' => __('validation.exists', ['attribute' => __('entities.attack_subtypes.singular')]),
      'area.boolean' => __('validation.boolean', ['attribute' => __('entities.hero_abilities.area')]),
      'cost.required' => __('validation.required', ['attribute' => __('entities.hero_abilities.cost')]),
      'cost.string' => __('validation.string', ['attribute' => __('entities.hero_abilities.cost')]),
      'cost.max' => __('validation.max.string', ['attribute' => __('entities.hero_abilities.cost'), 'max' => 5]),
      'cost.regex' => __('hero_abilities.validation.cost_format'),
    ];

    foreach (array_keys(config('laravellocalization.supportedLocales', ['es' => []])) as $locale) {
      $localeName = locale_name($locale);
      $messages["name.{$locale}.unique"] = __('hero_abilities.validation.name_unique', ['locale' => $localeName]);
    }

    return $messages;
  }
}