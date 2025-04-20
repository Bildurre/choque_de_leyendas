<?php

namespace App\Http\Requests\Admin;

use App\Services\HeroAttributesConfigurationService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HeroRequest extends FormRequest
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
    $attributesConfig = app(HeroAttributesConfigurationService::class)->getConfiguration();
    
    $rules = [
      'name' => ['required', 'string', 'max:255'],
      'description' => 'nullable|string',
      'passive_name' => 'nullable|string|max:255',
      'passive_description' => 'nullable|string',
      'faction_id' => 'nullable|exists:factions,id',
      'hero_race_id' => 'required|exists:hero_races,id',
      'hero_class_id' => 'required|exists:hero_classes,id',
      'gender' => ['required', Rule::in(['male', 'female'])],
      'agility' => "required|integer|min:{$attributesConfig->min_attribute_value}|max:{$attributesConfig->max_attribute_value}",
      'mental' => "required|integer|min:{$attributesConfig->min_attribute_value}|max:{$attributesConfig->max_attribute_value}",
      'will' => "required|integer|min:{$attributesConfig->min_attribute_value}|max:{$attributesConfig->max_attribute_value}",
      'strength' => "required|integer|min:{$attributesConfig->min_attribute_value}|max:{$attributesConfig->max_attribute_value}",
      'armor' => "required|integer|min:{$attributesConfig->min_attribute_value}|max:{$attributesConfig->max_attribute_value}",
      'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      'remove_image' => 'nullable|boolean',
    ];

    return $rules;
  }

  /**
   * Get custom messages for validator errors.
   */
  public function messages(): array
  {
    return [
      'name.required' => 'El nombre del héroe es obligatorio.',
      'hero_race_id.required' => 'La raza del héroe es obligatoria.',
      'hero_class_id.required' => 'La clase del héroe es obligatoria.',
      'gender.required' => 'El género del héroe es obligatorio.',
      'agility.required' => 'El valor de agilidad es obligatorio.',
      'mental.required' => 'El valor de mente es obligatorio.',
      'will.required' => 'El valor de voluntad es obligatorio.',
      'strength.required' => 'El valor de fuerza es obligatorio.',
      'armor.required' => 'El valor de armadura es obligatorio.',
      'image.image' => 'El archivo debe ser una imagen.',
      'image.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif, svg.',
      'image.max' => 'La imagen no debe superar los 2MB.'
    ];
  }

  /**
   * Configure the validator instance.
   *
   * @param \Illuminate\Validation\Validator $validator
   * @return void
   */
  public function withValidator($validator)
  {
    $validator->after(function ($validator) {
      $attributesConfig = app(HeroAttributesConfigurationService::class)->getConfiguration();
      
      // Calculate total attributes
      $total = $this->agility + $this->mental + $this->will + $this->strength + $this->armor;
      
      // Check if total is within allowed range
      if ($total < $attributesConfig->min_total_attributes || $total > $attributesConfig->max_total_attributes) {
        $validator->errors()->add(
          'total_attributes', 
          "La suma total de atributos debe estar entre {$attributesConfig->min_total_attributes} y {$attributesConfig->max_total_attributes}."
        );
      }
    });
  }
}