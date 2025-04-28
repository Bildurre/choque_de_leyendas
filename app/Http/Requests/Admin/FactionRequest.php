<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FactionRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    $availableLocales = config('app.available_locales', ['es']);
    $currentLocale = app()->getLocale();
    
    $rules = [
      'name' => 'required|array',
      "name.{$currentLocale}" => 'required|string|max:255',
      'lore_text' => 'nullable|array',
      'color' => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6})$/'],
      'icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
    ];
    
    // Añadir reglas para cada idioma disponible
    foreach ($availableLocales as $locale) {
      if ($locale !== $currentLocale) {
        $rules["name.{$locale}"] = 'nullable|string|max:255';
      }
      $rules["lore_text.{$locale}"] = 'nullable|string';
    }

    // Regla de unicidad para el nombre (por idioma)
    if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
      $factionId = $this->route('faction');
      foreach ($availableLocales as $locale) {
        if ($this->has("name.{$locale}")) {
          $rules["name.{$locale}"][] = Rule::unique('factions', 'name->' . $locale)
            ->ignore($factionId);
        }
      }
    } else {
      foreach ($availableLocales as $locale) {
        if ($this->has("name.{$locale}")) {
          $rules["name.{$locale}"][] = Rule::unique('factions', 'name->' . $locale);
        }
      }
    }

    return $rules;
  }

  public function messages(): array
  {
    $messages = [
      'name.required' => 'El nombre de la facción es obligatorio.',
      'name.*.required' => 'El nombre de la facción es obligatorio en este idioma.',
      'name.*.unique' => 'Ya existe una facción con este nombre en este idioma.',
      'color.required' => 'El color de la facción es obligatorio.',
      'color.regex' => 'El color debe estar en formato hexadecimal (ej. #FFFFFF).',
      'icon.image' => 'El archivo debe ser una imagen.',
      'icon.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif, svg.',
      'icon.max' => 'La imagen no debe pesar más de 2MB.',
    ];
    
    return $messages;
  }
}