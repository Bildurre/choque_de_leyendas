<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Traits\ValidatesTranslatableUniqueness;
use Illuminate\Foundation\Http\FormRequest;

class FactionRequest extends FormRequest
{
  use ValidatesTranslatableUniqueness;

  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    $factionId = $this->route('faction');
    $locales = config('app.available_locales', ['es']);
    
    $rules = [
      'name' => 'required|array',
      "name.{$locales[0]}" => 'required|string|max:255',
      'lore_text' => 'nullable|array',
      'color' => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6})$/'],
      'icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
    ];
    
    // Añadir reglas para cada idioma disponible
    foreach ($locales as $locale) {
      if ($locale !== $locales[0]) {
        $rules["name.{$locale}"] = 'nullable|string|max:255';
      }
      $rules["lore_text.{$locale}"] = 'nullable|string';
    }

    // Agregar reglas de unicidad para cada idioma
    $rules = array_merge(
      $rules, 
      $this->uniqueTranslatableRules('factions', 'name', $factionId, $locales)
    );

    return $rules;
  }

  public function messages(): array
  {
    $messages = [
      'name.required' => 'El nombre de la facción es obligatorio.',
      'name.*.required' => 'El nombre de la facción es obligatorio en este idioma.',
      'color.required' => 'El color de la facción es obligatorio.',
      'color.regex' => 'El color debe estar en formato hexadecimal (ej. #FFFFFF).',
      'icon.image' => 'El archivo debe ser una imagen.',
      'icon.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif, svg.',
      'icon.max' => 'La imagen no debe pesar más de 2MB.',
    ];
    
    // Mensajes para la unicidad en cada idioma
    foreach (config('app.available_locales', ['es']) as $locale) {
      $localeName = locale_name($locale);
      $messages["name.{$locale}.unique"] = "Ya existe una facción con este nombre en {$localeName}.";
    }
    
    return $messages;
  }
}