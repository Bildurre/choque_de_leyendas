<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CardRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    $rules = [
      'name' => ['required', 'string', 'max:255'],
      'faction_id' => ['nullable', 'exists:factions,id'],
      'card_type_id' => ['required', 'exists:card_types,id'],
      'equipment_type_id' => ['nullable', 'exists:equipment_types,id'],
      'attack_range_id' => ['nullable', 'exists:attack_ranges,id'],
      'attack_subtype_id' => ['nullable', 'exists:attack_subtypes,id'],
      'hero_ability_id' => ['nullable', 'exists:hero_abilities,id'],
      'hands' => ['nullable', 'integer', 'in:1,2'],
      'cost' => ['nullable', 'string', 'max:5', 'regex:/^[RGBrgb]*$/'],
      'effect' => ['nullable', 'string'],
      'restriction' => ['nullable', 'string'],
      'blast' => ['nullable', 'boolean'],
      'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
    ];

    // Añadir regla de unicidad de nombre para crear o actualizar
    if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
      $cardId = $this->route('card');
      $rules['name'][] = Rule::unique('cards')->ignore($cardId);
    } else {
      $rules['name'][] = 'unique:cards,name';
    }

    return $rules;
  }

  public function messages(): array
  {
    return [
      'name.required' => 'El nombre de la carta es obligatorio.',
      'name.unique' => 'Ya existe una carta con este nombre.',
      'card_type_id.required' => 'El tipo de carta es obligatorio.',
      'card_type_id.exists' => 'El tipo de carta seleccionado no existe.',
      'equipment_type_id.exists' => 'El tipo de equipo seleccionado no existe.',
      'attack_range_id.exists' => 'El rango de ataque seleccionado no existe.',
      'attack_subtype_id.exists' => 'El subtipo de ataque seleccionado no existe.',
      'hero_ability_id.exists' => 'La habilidad de héroe seleccionada no existe.',
      'hands.in' => 'El número de manos debe ser 1 o 2.',
      'cost.regex' => 'El coste solo puede contener los caracteres R, G, B.',
      'cost.max' => 'El coste no puede tener más de 5 caracteres.',
      'image.image' => 'El archivo debe ser una imagen.',
      'image.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif, svg.',
      'image.max' => 'La imagen no debe pesar más de 2MB.',
    ];
  }
}