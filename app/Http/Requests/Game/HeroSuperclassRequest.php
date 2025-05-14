<?php

namespace App\Http\Requests\Game;

use App\Http\Requests\Traits\ValidatesTranslatableUniqueness;
use Illuminate\Foundation\Http\FormRequest;

class HeroSuperclassRequest extends FormRequest
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
    $heroSuperclassId = $this->route('hero_superclass');
    $locales = config('app.available_locales', ['es']);
    
    $rules = [
      'name' => ['required', 'array'],
      'name.es' => ['required', 'string', 'max:255'],
    ];

    // Agregar reglas de unicidad para cada idioma
    $rules = array_merge(
      $rules, 
      $this->uniqueTranslatableRules('hero_superclasses', 'name', $heroSuperclassId, $locales)
    );

    return $rules;
  }

  /**
   * Get custom validation messages.
   */
  public function messages(): array
  {
    $messages = [
      'name.required' => 'El nombre de la superclase es obligatorio.',
      'name.array' => 'El nombre debe ser un array con traducciones.',
      'name.es.required' => 'El nombre en español es obligatorio.',
    ];

    // Mensajes para la unicidad en cada idioma
    foreach (config('app.available_locales', ['es']) as $locale) {
      $localeName = locale_name($locale);
      $messages["name.{$locale}.unique"] = "Ya existe una superclase con este nombre en {$localeName}.";
    }

    return $messages;
  }
}