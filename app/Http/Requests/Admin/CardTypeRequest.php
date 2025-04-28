<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\App;

class CardTypeRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    $locales = config('app.available_locales', ['es']);
    $rules = [];
    
    // Reglas para cada idioma
    foreach ($locales as $locale) {
      $rules["name.{$locale}"] = ['required', 'string', 'max:255'];
      
      // Regla de unicidad por idioma
      if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
        $cardTypeId = $this->route('card_type');
        $rules["name.{$locale}"][] = Rule::unique('card_types', 'name->' . $locale)->ignore($cardTypeId);
      } else {
        $rules["name.{$locale}"][] = Rule::unique('card_types', 'name->' . $locale);
      }
    }
    
    // Reglas adicionales
    $rules['hero_superclass_id'] = ['nullable', 'exists:hero_superclasses,id'];
    
    return $rules;
  }

  public function messages(): array
  {
    $messages = [];
    $locales = config('app.available_locales', ['es']);
    
    foreach ($locales as $locale) {
      $langName = locale_name($locale);
      
      $messages["name.{$locale}.required"] = "El nombre en {$langName} es obligatorio.";
      $messages["name.{$locale}.unique"] = "Ya existe un tipo de carta con este nombre en {$langName}.";
    }
    
    $messages['hero_superclass_id.exists'] = 'La superclase seleccionada no existe.';
    
    return $messages;
  }
}