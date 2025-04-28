<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Translatable\Rules\TranslatableRequiredRule;

class CardRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    $rules = [
      'name' => ['required', new TranslatableRequiredRule(['es']), 'array'],
      'name.es' => [
        'string', 
        'max:255',
        Rule::unique('cards', 'name->es')->ignore($this->route('card')),
      ],
      'name.en' => [
        'nullable',
        'string', 
        'max:255',
        Rule::unique('cards', 'name->en')->ignore($this->route('card')),
      ],
      'faction_id' => ['nullable', 'exists:factions,id'],
      'card_type_id' => ['required', 'exists:card_types,id'],
      'lore_text' => ['nullable', 'array'],
      'lore_text.es' => ['nullable', 'string'],
      'lore_text.en' => ['nullable', 'string'],
      'is_attack' => ['boolean'],
      'has_hero_ability' => ['boolean'],
      'cost' => ['nullable', 'string', 'max:5', 'regex:/^[RGBrgb]*$/'],
      'effect' => ['nullable', 'array'],
      'effect.es' => ['nullable', 'string'],
      'effect.en' => ['nullable', 'string'],
      'restriction' => ['nullable', 'array'],
      'restriction.es' => ['nullable', 'string'],
      'restriction.en' => ['nullable', 'string'],
      'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
    ];

    // Rules that depend on conditional fields
    if ($this->input('is_attack')) {
      $rules['attack_range_id'] = ['required', 'exists:attack_ranges,id'];
      $rules['attack_subtype_id'] = ['required', 'exists:attack_subtypes,id'];
      $rules['blast'] = ['boolean'];
    } else {
      $rules['attack_range_id'] = ['nullable'];
      $rules['attack_subtype_id'] = ['nullable'];
      $rules['blast'] = ['nullable'];
    }
    
    if ($this->input('has_hero_ability')) {
      $rules['hero_ability_id'] = ['required', 'exists:hero_abilities,id'];
    } else {
      $rules['hero_ability_id'] = ['nullable'];
    }

    // Determine if this is an equipment card
    $cardTypeId = $this->input('card_type_id');
    $isEquipmentCard = false;
    
    if ($cardTypeId) {
      $cardType = \App\Models\CardType::find($cardTypeId);
      $isEquipmentCard = $cardType && $cardType->name === 'Equipo';
    }

    if ($isEquipmentCard) {
      $rules['equipment_type_id'] = ['required', 'exists:equipment_types,id'];
      
      // If equipment type is weapon, require hands
      if ($this->input('equipment_type_id')) {
        $equipmentType = \App\Models\EquipmentType::find($this->input('equipment_type_id'));
        if ($equipmentType && $equipmentType->isWeapon()) {
          $rules['hands'] = ['required', 'integer', 'in:1,2'];
        } else {
          $rules['hands'] = ['nullable'];
        }
      }
    } else {
      $rules['equipment_type_id'] = ['nullable'];
      $rules['hands'] = ['nullable'];
    }

    return $rules;
  }

  public function messages(): array
  {
    return [
      'name.required' => 'El nombre de la carta es obligatorio.',
      'name.es.required' => 'El nombre de la carta en español es obligatorio.',
      'name.es.unique' => 'Ya existe una carta con este nombre en español.',
      'name.en.unique' => 'Ya existe una carta con este nombre en inglés.',
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