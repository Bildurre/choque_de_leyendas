<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HeroRaceRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    $rules = [];
    
    // Obtenemos los locales disponibles
    $locales = config('app.available_locales', ['es']);
    
    // Creamos reglas para cada locale
    foreach ($locales as $locale) {
      $rules["name.{$locale}"] = [
        'required', 
        'string', 
        'max:255'
      ];
      
      // Para validar unicidad, usamos una validación personalizada
      if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
        $heroRaceId = $this->route('hero_race');
        $rules["name.{$locale}"][] = Rule::unique('hero_races', 'name->' . $locale)->ignore($heroRaceId);
      } else {
        $rules["name.{$locale}"][] = Rule::unique('hero_races', 'name->' . $locale);
      }
    }

    return $rules;
  }

  public function messages(): array
  {
    $messages = [];
    
    // Obtenemos los locales disponibles
    $locales = config('app.available_locales', ['es']);
    
    // Creamos mensajes para cada locale
    foreach ($locales as $locale) {
      $localeName = locale_name($locale);
      
      $messages["name.{$locale}.required"] = "El nombre en {$localeName} es obligatorio.";
      $messages["name.{$locale}.unique"] = "Ya existe una raza con este nombre en {$localeName}.";
    }

    return $messages;
  }
}