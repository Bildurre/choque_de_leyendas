<?php

namespace App\Http\Requests\Admin;

use App\Models\AttackRange;
use Illuminate\Foundation\Http\FormRequest;

class AttackRangeRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    $rules = [];
    
    // Validación para cada locale disponible
    foreach (available_locales() as $locale) {
      $rules[$locale . '.name'] = [
        'required', 
        'string', 
        'max:255',
        function ($attribute, $value, $fail) use ($locale) {
          $excludeId = $this->route('attack_range') ? $this->route('attack_range')->id : null;
          
          if (!AttackRange::isNameUniqueInLocale($locale, $value, $excludeId)) {
            $localeName = locale_name($locale);
            $fail("El nombre '{$value}' en {$localeName} ya está en uso.");
          }
        }
      ];
    }
    
    // Otras reglas no relacionadas con traducciones
    $rules['icon'] = ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'];
    
    return $rules;
  }

  public function messages(): array
  {
    $messages = [];
    
    foreach (available_locales() as $locale) {
      $localeName = locale_name($locale);
      $messages[$locale . '.name.required'] = "El nombre en {$localeName} es obligatorio.";
      $messages[$locale . '.name.max'] = "El nombre en {$localeName} no debe exceder los 255 caracteres.";
    }
    
    return $messages;
  }
}