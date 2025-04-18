<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class HeroAttributesConfigurationRequest extends FormRequest
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
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'min_attribute_value' => 'required|integer|min:1',
      'max_attribute_value' => 'required|integer|min:1',
      'min_total_attributes' => 'required|integer|min:5',
      'max_total_attributes' => 'required|integer|min:5',
      'agility_multiplier' => 'required|integer',
      'mental_multiplier' => 'required|integer',
      'will_multiplier' => 'required|integer',
      'strength_multiplier' => 'required|integer',
      'armor_multiplier' => 'required|integer',
      'total_health_base' => 'required|integer|min:1'
    ];
  }

  /**
   * Custom validation messages
   */
  public function messages(): array
  {
    return [
      'min_attribute_value.required' => 'El valor mínimo de atributo es obligatorio',
      'max_attribute_value.required' => 'El valor máximo de atributo es obligatorio',
      'min_total_attributes.required' => 'El valor mínimo total de atributos es obligatorio',
      'max_total_attributes.required' => 'El valor máximo total de atributos es obligatorio',
      'min_attribute_value.min' => 'El valor mínimo de atributo debe ser al menos 1',
      'max_attribute_value.min' => 'El valor máximo de atributo debe ser al menos 1',
      'min_total_attributes.min' => 'El valor mínimo total de atributos debe ser al menos 5',
      'max_total_attributes.min' => 'El valor máximo total de atributos debe ser al menos 5',
      'total_health_base.min' => 'El valor base de salud debe ser al menos 1'
    ];
  }
}