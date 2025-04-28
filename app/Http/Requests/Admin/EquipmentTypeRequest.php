<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EquipmentTypeRequest extends FormRequest
{
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
    $rules = [
      'name' => ['required', 'array'],
      'category' => ['required', 'in:weapon,armor'],
    ];

    // Validación para cada idioma disponible
    foreach (config('app.available_locales', ['es']) as $locale) {
      $rules["name.{$locale}"] = ['required', 'string', 'max:255'];
      
      // Para actualización, asegurarse de que el nombre es único excluyendo el registro actual
      if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
        // Obtenemos el ID de la ruta
        $equipmentTypeId = $this->route('equipment_type');
        
        // La validación unique debe incluir la columna locale en la base de datos
        $rules["name.{$locale}"][] = Rule::unique('equipment_types', 'name->' . $locale)
          ->ignore($equipmentTypeId);
      } else {
        $rules["name.{$locale}"][] = Rule::unique('equipment_types', 'name->' . $locale);
      }
    }

    return $rules;
  }
  
  /**
   * Get custom messages for validator errors.
   */
  public function messages(): array
  {
    $messages = [
      'name.required' => 'El nombre del tipo de equipo es obligatorio.',
      'name.array' => 'El nombre debe ser un array con traducciones.',
      'category.required' => 'La categoría del tipo de equipo es obligatoria.',
      'category.in' => 'La categoría debe ser arma o armadura.',
    ];

    // Mensajes personalizados para cada idioma
    foreach (config('app.available_locales', ['es']) as $locale) {
      $localeName = locale_name($locale);
      
      $messages["name.{$locale}.required"] = "El nombre en {$localeName} es obligatorio.";
      $messages["name.{$locale}.string"] = "El nombre en {$localeName} debe ser texto.";
      $messages["name.{$locale}.max"] = "El nombre en {$localeName} no puede superar los 255 caracteres.";
      $messages["name.{$locale}.unique"] = "Ya existe un tipo de equipo con este nombre en {$localeName}.";
    }

    return $messages;
  }
}