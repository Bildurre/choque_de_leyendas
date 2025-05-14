<?php

namespace App\Http\Requests\Game;

use App\Http\Requests\Traits\ValidatesTranslatableUniqueness;
use Illuminate\Foundation\Http\FormRequest;

class GameModeRequest extends FormRequest
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
    $gameModeId = $this->route('game_mode');
    $locales = config('app.available_locales', ['es']);
    
    $rules = [
      'name' => ['required', 'array'],
      'name.es' => ['required', 'string', 'max:255'],
      'description' => ['nullable', 'array'],
    ];

    // Add uniqueness rules for each locale
    $rules = array_merge(
      $rules, 
      $this->uniqueTranslatableRules('game_modes', 'name', $gameModeId, $locales)
    );

    return $rules;
  }

  /**
   * Get custom validation messages.
   */
  public function messages(): array
  {
    $messages = [
      'name.required' => 'El nombre del modo de juego es obligatorio.',
      'name.array' => '__('validation.array', ['attribute' => __('common.name')])',
      'name.es.required' => '__('validation.required', ['attribute' => __('common.name'). ' ' . __('in_spanish')])',
    ];

    // Messages for uniqueness in each language
    foreach (config('app.available_locales', ['es']) as $locale) {
      $localeName = locale_name($locale);
      $messages["name.{$locale}.unique"] = "Ya existe un modo de juego con este nombre en {$localeName}.";
    }

    return $messages;
  }
}