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
      'name' => ['required', 'string', 'max:255'],
      'category' => ['required', 'in:weapon,armor'],
    ];

    // Para actualización, asegurarse de que el nombre sea único excluyendo el registro actual
    if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
      // Obtenemos el ID de la ruta
      $equipmentTypeId = $this->route('equipment_type');
      
      $rules['name'][] = Rule::unique('equipment_types')->ignore($equipmentTypeId);
    } else {
      $rules['name'][] = 'unique:equipment_types,name';
    }

    return $rules;
  }
  
  /**
   * Get custom messages for validator errors.
   */
  public function messages(): array
  {
    return [
      'name.required' => 'El nombre del tipo de equipo es obligatorio.',
      'name.unique' => 'Ya existe un tipo de equipo con este nombre.',
      'category.required' => 'La categoría del tipo de equipo es obligatoria.',
      'category.in' => 'La categoría debe ser arma o armadura.',
    ];
  }
}