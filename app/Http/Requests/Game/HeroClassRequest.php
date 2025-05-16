<?php

namespace App\Http\Requests\Game;

use App\Http\Requests\Traits\ValidatesTranslatableUniqueness;
use Illuminate\Foundation\Http\FormRequest;

class HeroClassRequest extends FormRequest
{
  use ValidatesTranslatableUniqueness;

  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    $heroClassId = $this->route('hero_class');
    $locales = config('app.available_locales', ['es']);
    
    $rules = [
      'name' => ['required', 'array'],
      'name.es' => ['required', 'string', 'max:255'],
      'passive' => ['nullable', 'array'],
      'hero_superclass_id' => ['required', 'exists:hero_superclasses,id'],
    ];

    // Agregar reglas de unicidad para cada idioma
    $rules = array_merge(
        $rules, 
        $this->uniqueTranslatableRules('hero_classes', 'name', $heroClassId, $locales)
    );

    return $rules;
  }

  public function messages(): array
  {
    $messages = [
      'name.required' => __('validation.required', ['attribute' => __('entities.hero_classes.name')]),
      'name.array' => __('validation.array', ['attribute' => __('common.name')]),
      'name.es.required' => __('validation.required', ['attribute' => __('common.name'). ' ' . __('in_spanish')]),
      'hero_superclass_id.required' => __('validation.required', ['attribute' => __('entities.hero_superclasses.singular')]),
      'hero_superclass_id.exists' => __('validation.exists', ['attribute' => __('entities.hero_superclasses.singular')]),
    ];

    // Mensajes para la unicidad en cada idioma
    foreach (config('app.available_locales', ['es']) as $locale) {
      $localeName = locale_name($locale);
      $messages["name.{$locale}.unique"] = "Ya existe una clase con este nombre en {$localeName}.";
    }

    return $messages;
  }
}