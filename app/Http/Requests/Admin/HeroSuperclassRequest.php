<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Translatable\Facades\Translatable;

class HeroSuperclassRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    $rules = [];
    
    // Reglas de validación para cada idioma
    foreach (config('app.available_locales', ['es']) as $locale) {
      $nameRules = ['required', 'string', 'max:255'];
      
      // Validación de unicidad para cada idioma
      if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
        $heroSuperclassId = $this->route('hero_superclass');
        $nameRules[] = Rule::unique('hero_superclasses', 'name->'. $locale)->ignore($heroSuperclassId);
      } else {
        $nameRules[] = Rule::unique('hero_superclasses', 'name->'. $locale);
      }
      
      $rules["name_{$locale}"] = $nameRules;
    }
    
    $rules['icon'] = ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'];
    
    return $rules;
  }

  public function messages(): array
  {
    $messages = [];
    
    foreach (config('app.available_locales', ['es']) as $locale) {
      $messages["name_{$locale}.required"] = "El nombre en " . locale_name($locale) . " es obligatorio.";
      $messages["name_{$locale}.unique"] = "Ya existe una superclase con este nombre en " . locale_name($locale) . ".";
    }
    
    $messages["icon.image"] = "El archivo debe ser una imagen.";
    $messages["icon.mimes"] = "La imagen debe ser de tipo: jpeg, png, jpg, gif, svg.";
    $messages["icon.max"] = "La imagen no debe pesar más de 2MB.";
    
    return $messages;
  }
}