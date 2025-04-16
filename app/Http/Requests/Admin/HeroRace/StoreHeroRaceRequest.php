<?php

namespace App\Http\Requests\Admin\HeroRace;

use Illuminate\Foundation\Http\FormRequest;

class StoreHeroRaceRequest extends FormRequest
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
      'name' => 'required|string|max:255|unique:hero_races',
      'agility_modifier' => 'required|integer|between:-3,3',
      'mental_modifier' => 'required|integer|between:-3,3',
      'will_modifier' => 'required|integer|between:-3,3',
      'strength_modifier' => 'required|integer|between:-3,3',
      'armor_modifier' => 'required|integer|between:-3,3'
    ];
  }
  
  /**
   * Get custom messages for validator errors.
   */
  public function messages(): array
  {
    return [
      'name.required' => 'El nombre de la raza es obligatorio.',
      'name.unique' => 'Ya existe una raza con este nombre.',
      // Mensajes para los modificadores
      'agility_modifier.between' => 'El modificador de agilidad debe estar entre -3 y 3.',
      'mental_modifier.between' => 'El modificador mental debe estar entre -3 y 3.',
      'will_modifier.between' => 'El modificador de voluntad debe estar entre -3 y 3.',
      'strength_modifier.between' => 'El modificador de fuerza debe estar entre -3 y 3.',
      'armor_modifier.between' => 'El modificador de armadura debe estar entre -3 y 3.',
    ];
  }
  
  /**
   * Configure the validator instance.
   */
  public function withValidator($validator)
  {
    $validator->after(function ($validator) {
      // Validar que la suma de los valores absolutos de los modificadores no supere 3
      $totalModifiers = abs($this->agility_modifier) +
        abs($this->mental_modifier) +
        abs($this->will_modifier) +
        abs($this->strength_modifier) +
        abs($this->armor_modifier);
        
      if ($totalModifiers > 3) {
        $validator->errors()->add(
          'modifiers',
          'La suma total de los valores absolutos de los modificadores no puede superar 3.'
        );
      }
    });
  }
}