<?php

namespace App\Http\Requests\Game;

use App\Http\Requests\Traits\ValidatesTranslatableUniqueness;
use Illuminate\Foundation\Http\FormRequest;

class CardRequest extends FormRequest
{
  use ValidatesTranslatableUniqueness;

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
    $cardId = $this->route('card');
    $locales = config('app.available_locales', ['es']);
    
    $rules = [
      'name' => ['required', 'array'],
      'name.es' => ['required', 'string', 'max:255'],
      'lore_text' => ['nullable', 'array'],
      'faction_id' => ['nullable', 'exists:factions,id'],
      'card_type_id' => ['required', 'exists:card_types,id'],
      'equipment_type_id' => ['nullable', 'exists:equipment_types,id'],
      'attack_range_id' => ['nullable', 'exists:attack_ranges,id'],
      'attack_subtype_id' => ['nullable', 'exists:attack_subtypes,id'],
      'hero_ability_id' => ['nullable', 'exists:hero_abilities,id'],
      'hands' => ['nullable', 'integer', 'in:1,2'],
      'cost' => ['nullable', 'string', 'max:5', 'regex:/^[RGBrgb]*$/'],
      'effect' => ['nullable', 'array'],
      'restriction' => ['nullable', 'array'],
      'area' => ['nullable', 'boolean'],
      'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
      'remove_image' => ['nullable', 'boolean'],
    ];

    // Validation for equipment_type_id and hands
    $rules['hands'][] = function ($attribute, $value, $fail) {
      $equipmentTypeId = $this->input('equipment_type_id');
      
      // If equipment type is weapon (category = weapon), hands is required
      if ($equipmentTypeId) {
        $equipmentType = \App\Models\EquipmentType::find($equipmentTypeId);
        if ($equipmentType && $equipmentType->category === 'weapon' && $value === null) {
          $fail(__('cards.validation.hands_required_for_weapon'));
        }
      } elseif ($value !== null) {
        // If not a weapon, hands should be null
        $fail(__('cards.validation.hands_only_for_weapon'));
      }
    };

    // Validation for attack_range_id and attack_subtype_id
    $rules['attack_subtype_id'][] = function ($attribute, $value, $fail) {
      $rangeId = $this->input('attack_range_id');
      
      // Both or none should be provided
      if (($value === null && $rangeId !== null) || ($value !== null && $rangeId === null)) {
        $fail(__('cards.validation.attack_range_and_subtype_together'));
      }
    };

    // Add uniqueness rules for each locale
    $rules = array_merge(
      $rules, 
      $this->uniqueTranslatableRules('cards', 'name', $cardId, $locales)
    );

    return $rules;
  }

  /**
   * Get custom validation messages.
   */
  public function messages(): array
  {
    $messages = [
      'name.required' => 'El nombre de la carta es obligatorio.',
      'name.array' => 'El nombre debe ser un array con traducciones.',
      'name.es.required' => 'El nombre en español es obligatorio.',
      'card_type_id.required' => 'El tipo de carta es obligatorio.',
      'card_type_id.exists' => 'El tipo de carta seleccionado no existe.',
      'faction_id.exists' => 'La facción seleccionada no existe.',
      'equipment_type_id.exists' => 'El tipo de equipo seleccionado no existe.',
      'attack_range_id.exists' => 'El rango de ataque seleccionado no existe.',
      'attack_subtype_id.exists' => 'El subtipo de ataque seleccionado no existe.',
      'hero_ability_id.exists' => 'La habilidad de héroe seleccionada no existe.',
      'hands.integer' => 'El número de manos debe ser un número entero.',
      'hands.in' => 'El número de manos debe ser 1 o 2.',
      'cost.string' => 'El coste debe ser una cadena de texto.',
      'cost.max' => 'El coste no puede tener más de 5 caracteres.',
      'cost.regex' => 'El coste solo puede contener los caracteres R, G y B.',
      'area.boolean' => 'El campo de área debe ser verdadero o falso.',
      'image.image' => 'El archivo debe ser una imagen válida.',
      'image.mimes' => 'El archivo debe ser de tipo: jpeg, png, jpg, gif, svg.',
      'image.max' => 'La imagen no debe ser mayor de 2MB.',
    ];

    // Messages for uniqueness in each language
    foreach (config('app.available_locales', ['es']) as $locale) {
      $localeName = locale_name($locale);
      $messages["name.{$locale}.unique"] = "Ya existe una carta con este nombre en {$localeName}.";
    }

    return $messages;
  }
}