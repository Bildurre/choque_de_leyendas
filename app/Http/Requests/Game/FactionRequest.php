<?php

namespace App\Http\Requests\Game;

use App\Http\Requests\Traits\ValidatesTranslatableUniqueness;
use Illuminate\Foundation\Http\FormRequest;

class FactionRequest extends FormRequest
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
    $factionId = $this->route('faction');
    $locales = config('app.available_locales', ['es']);
    
    $rules = [
      'name' => ['required', 'array'],
      'name.es' => ['required', 'string', 'max:255'],
      'lore_text' => ['nullable', 'array'],
      'color' => ['required', 'string', 'regex:/^#[a-fA-F0-9]{6}$/'],
      'icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
      'remove_icon' => ['nullable', 'boolean'],
    ];

    // Add uniqueness rules for each locale
    $rules = array_merge(
      $rules, 
      $this->uniqueTranslatableRules('factions', 'name', $factionId, $locales)
    );

    return $rules;
  }

  /**
   * Get custom validation messages.
   */
  public function messages(): array
  {
    $messages = [
      'name.required' => 'El nombre de la facción es obligatorio.',
      'name.array' => 'El nombre debe ser un array con traducciones.',
      'name.es.required' => 'El nombre en español es obligatorio.',
      'color.required' => 'El color es obligatorio.',
      'color.string' => 'El color debe ser una cadena de texto.',
      'color.regex' => 'El color debe tener un formato hexadecimal válido (p. ej. #FF5500).',
      'icon.image' => 'El archivo debe ser una imagen válida.',
      'icon.mimes' => 'El archivo debe ser de tipo: jpeg, png, jpg, gif, svg.',
      'icon.max' => 'La imagen no debe ser mayor de 2MB.',
    ];

    // Messages for uniqueness in each language
    foreach (config('app.available_locales', ['es']) as $locale) {
      $localeName = locale_name($locale);
      $messages["name.{$locale}.unique"] = "Ya existe una facción con este nombre en {$localeName}.";
    }

    return $messages;
  }
}