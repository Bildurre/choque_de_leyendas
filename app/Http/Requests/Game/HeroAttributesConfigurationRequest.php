<?php

namespace App\Http\Requests\Game;

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
   */
  public function rules(): array
  {
    return [
      'min_attribute_value' => ['required', 'integer', 'min:1', 'max:3'],
      'max_attribute_value' => ['required', 'integer', 'min:3', 'max:10', 'gte:min_attribute_value'],
      'min_total_attributes' => ['required', 'integer', 'min:5', 'max:20'],
      'max_total_attributes' => ['required', 'integer', 'min:10', 'max:50', 'gte:min_total_attributes'],
      'agility_multiplier' => ['required', 'integer', 'min:-5', 'max:5'],
      'mental_multiplier' => ['required', 'integer', 'min:-5', 'max:5'],
      'will_multiplier' => ['required', 'integer', 'min:-5', 'max:5'],
      'strength_multiplier' => ['required', 'integer', 'min:-5', 'max:5'],
      'armor_multiplier' => ['required', 'integer', 'min:-5', 'max:5'],
      'total_health_base' => ['required', 'integer', 'min:10', 'max:100'],
    ];
  }

  /**
   * Get custom messages for validator errors.
   *
   * @return array
   */
  public function messages(): array
  {
    return [
      'min_attribute_value.required' => 'El valor mínimo de atributo es obligatorio.',
      'min_attribute_value.integer' => 'El valor mínimo de atributo debe ser un número entero.',
      'min_attribute_value.min' => 'El valor mínimo de atributo debe ser al menos 1.',
      'min_attribute_value.max' => 'El valor mínimo de atributo no puede ser mayor que 3.',
      
      'max_attribute_value.required' => 'El valor máximo de atributo es obligatorio.',
      'max_attribute_value.integer' => 'El valor máximo de atributo debe ser un número entero.',
      'max_attribute_value.min' => 'El valor máximo de atributo debe ser al menos 3.',
      'max_attribute_value.max' => 'El valor máximo de atributo no puede ser mayor que 10.',
      'max_attribute_value.gte' => 'El valor máximo de atributo debe ser mayor o igual que el valor mínimo.',
      
      'min_total_attributes.required' => 'El total mínimo de atributos es obligatorio.',
      'min_total_attributes.integer' => 'El total mínimo de atributos debe ser un número entero.',
      'min_total_attributes.min' => 'El total mínimo de atributos debe ser al menos 5.',
      'min_total_attributes.max' => 'El total mínimo de atributos no puede ser mayor que 20.',
      
      'max_total_attributes.required' => 'El total máximo de atributos es obligatorio.',
      'max_total_attributes.integer' => 'El total máximo de atributos debe ser un número entero.',
      'max_total_attributes.min' => 'El total máximo de atributos debe ser al menos 10.',
      'max_total_attributes.max' => 'El total máximo de atributos no puede ser mayor que 50.',
      'max_total_attributes.gte' => 'El total máximo de atributos debe ser mayor o igual que el total mínimo.',
      
      'agility_multiplier.required' => 'El multiplicador de agilidad es obligatorio.',
      'agility_multiplier.integer' => 'El multiplicador de agilidad debe ser un número entero.',
      'agility_multiplier.min' => 'El multiplicador de agilidad debe ser al menos -5.',
      'agility_multiplier.max' => 'El multiplicador de agilidad no puede ser mayor que 5.',
      
      'mental_multiplier.required' => 'El multiplicador de mente es obligatorio.',
      'mental_multiplier.integer' => 'El multiplicador de mente debe ser un número entero.',
      'mental_multiplier.min' => 'El multiplicador de mente debe ser al menos -5.',
      'mental_multiplier.max' => 'El multiplicador de mente no puede ser mayor que 5.',
      
      'will_multiplier.required' => 'El multiplicador de voluntad es obligatorio.',
      'will_multiplier.integer' => 'El multiplicador de voluntad debe ser un número entero.',
      'will_multiplier.min' => 'El multiplicador de voluntad debe ser al menos -5.',
      'will_multiplier.max' => 'El multiplicador de voluntad no puede ser mayor que 5.',
      
      'strength_multiplier.required' => 'El multiplicador de fuerza es obligatorio.',
      'strength_multiplier.integer' => 'El multiplicador de fuerza debe ser un número entero.',
      'strength_multiplier.min' => 'El multiplicador de fuerza debe ser al menos -5.',
      'strength_multiplier.max' => 'El multiplicador de fuerza no puede ser mayor que 5.',
      
      'armor_multiplier.required' => 'El multiplicador de armadura es obligatorio.',
      'armor_multiplier.integer' => 'El multiplicador de armadura debe ser un número entero.',
      'armor_multiplier.min' => 'El multiplicador de armadura debe ser al menos -5.',
      'armor_multiplier.max' => 'El multiplicador de armadura no puede ser mayor que 5.',
      
      'total_health_base.required' => 'La base de salud total es obligatoria.',
      'total_health_base.integer' => 'La base de salud total debe ser un número entero.',
      'total_health_base.min' => 'La base de salud total debe ser al menos 10.',
      'total_health_base.max' => 'La base de salud total no puede ser mayor que 100.',
    ];
  }
}