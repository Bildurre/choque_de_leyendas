<?php

namespace App\Http\Requests\Admin\HeroClass;

use Illuminate\Foundation\Http\FormRequest;

class StoreHeroClassRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'name' => 'required|string|max:255|unique:hero_classes',
      'description' => 'nullable|string',
      'passive' => 'nullable|string',
      'superclass_id' => 'required|exists:superclasses,id',
      'agility_modifier' => 'required|integer|between:-2,2',
      'mental_modifier' => 'required|integer|between:-2,2',
      'will_modifier' => 'required|integer|between:-2,2',
      'strength_modifier' => 'required|integer|between:-2,2',
      'armor_modifier' => 'required|integer|between:-2,2'
    ];
  }
  
  public function messages(): array
  {
    return [
      'name.required' => 'El nombre de la clase es obligatorio.',
      'name.unique' => 'Ya existe una clase con este nombre.',
      'superclass_id.required' => 'La superclase es obligatoria.',
      'superclass_id.exists' => 'La superclase seleccionada no es válida.',
      // Mensajes para los modificadores
      'agility_modifier.between' => 'El modificador de agilidad debe estar entre -2 y 2.',
      'mental_modifier.between' => 'El modificador mental debe estar entre -2 y 2.',
      'will_modifier.between' => 'El modificador de voluntad debe estar entre -2 y 2.',
      'strength_modifier.between' => 'El modificador de fuerza debe estar entre -2 y 2.',
      'armor_modifier.between' => 'El modificador de armadura debe estar entre -2 y 2.',
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