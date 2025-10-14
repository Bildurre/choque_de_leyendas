<?php

namespace App\Http\Requests\Game;

use App\Http\Requests\Traits\ValidatesTranslatableUniqueness;
use Illuminate\Foundation\Http\FormRequest;

class CardSubtypeRequest extends FormRequest
{
  use ValidatesTranslatableUniqueness;

  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    $cardSubtypeId = $this->route('card_subtype');
    $locales = array_keys(config('laravellocalization.supportedLocales', ['es' => []]));
    
    $rules = [
      'name' => ['required', 'array'],
      'name.es' => ['required', 'string', 'max:255'],
    ];

    $rules = array_merge(
      $rules, 
      $this->uniqueTranslatableRules('card_subtypes', 'name', $cardSubtypeId, $locales)
    );

    return $rules;
  }

  public function messages(): array
  {
    $messages = [
      'name.required' => __('validation.required', ['attribute' => __('entities.card_subtypes.name')]),
      'name.array' => __('validation.array', ['attribute' => __('common.name')]),
      'name.es.required' => __('validation.required', ['attribute' => __('common.name'). ' ' . __('in_spanish')]),
    ];

    foreach (array_keys(config('laravellocalization.supportedLocales', ['es' => []])) as $locale) {
      $localeName = locale_name($locale);
      $messages["name.{$locale}.unique"] = __('entities.card_subtypes.validation.name_unique', ['locale' => $localeName]);
    }

    return $messages;
  }
}