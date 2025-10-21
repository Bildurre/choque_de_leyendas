<?php

namespace App\Http\Requests\Game;

use App\Http\Requests\Traits\ValidatesTranslatableUniqueness;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Card;

class CardRequest extends FormRequest
{
  use ValidatesTranslatableUniqueness;

  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    $cardId = $this->route('card');
    $locales = array_keys(config('laravellocalization.supportedLocales', ['es' => []]));
    
    $rules = [
      'name' => ['required', 'array'],
      'name.es' => ['required', 'string', 'max:255'],
      'lore_text' => ['nullable', 'array'],
      'epic_quote' => ['nullable', 'array'],
      'faction_id' => ['nullable', 'exists:factions,id'],
      'card_type_id' => ['required', 'exists:card_types,id'],
      'card_subtype_id' => ['nullable', 'exists:card_subtypes,id'],
      'attack_type' => ['nullable', Rule::in(['physical', 'magical'])],
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
      'is_published' => ['nullable', 'boolean'],
      'is_unique' => ['nullable', 'boolean'],
    ];

    // Validation for card_subtype_id (only valid for spell and technique types)
    $rules['card_subtype_id'][] = function ($attribute, $value, $fail) {
      $cardTypeId = $this->input('card_type_id');
      
      if ($value !== null && !in_array($cardTypeId, [Card::SPELL_TYPE_ID, Card::TECHNIQUE_TYPE_ID, Card::LITANY_TYPE_ID])) {
        $fail(__('entities.cards.validation.subtype_only_for_spell_or_technique'));
      }
    };

    // Validation for attack_type (only valid for spell and technique types)
    $rules['attack_type'][] = function ($attribute, $value, $fail) {
      $cardTypeId = $this->input('card_type_id');
      
      if ($value !== null && !in_array($cardTypeId, [Card::SPELL_TYPE_ID, Card::TECHNIQUE_TYPE_ID,  Card::LITANY_TYPE_ID])) {
        $fail(__('entities.cards.validation.attack_type_only_for_spell_or_technique'));
      }
    };

    // Validation for equipment_type_id and hands
    $rules['hands'][] = function ($attribute, $value, $fail) {
      $equipmentTypeId = $this->input('equipment_type_id');
      
      if ($equipmentTypeId) {
        $equipmentType = \App\Models\EquipmentType::find($equipmentTypeId);
        if ($equipmentType && $equipmentType->category === 'weapon' && $value === null) {
          $fail(__('entities.cards.validation.hands_required_for_weapon'));
        }
      } elseif ($value !== null) {
        $fail(__('entities.cards.validation.hands_only_for_weapon'));
      }
    };

    // Validation for attack_range_id and attack_subtype_id
    $rules['attack_subtype_id'][] = function ($attribute, $value, $fail) {
      $rangeId = $this->input('attack_range_id');
      
      if (($value === null && $rangeId !== null) || ($value !== null && $rangeId === null)) {
        $fail(__('entities.cards.validation.attack_range_and_subtype_together'));
      }
    };

    $rules = array_merge(
      $rules, 
      $this->uniqueTranslatableRules('cards', 'name', $cardId, $locales)
    );

    return $rules;
  }

  public function messages(): array
  {
    $messages = [
      'name.required' => __('validation.required', ['attribute' => __('entities.cards.name')]),
      'name.array' => __('validation.array', ['attribute' => __('common.name')]),
      'name.es.required' => __('validation.required', ['attribute' => __('common.name'). ' ' . __('in_spanish')]),
      'card_type_id.required' => __('validation.required', ['attribute' => __('entities.card_types.singular')]),
      'card_type_id.exists' => __('validation.exists', ['attribute' => __('entities.card_types.singular')]),
      'card_subtype_id.exists' => __('validation.exists', ['attribute' => __('entities.card_subtypes.singular')]),
      'attack_type.in' => __('validation.in', ['attribute' => __('entities.cards.attack_type')]),
      'faction_id.exists' => __('validation.exists', ['attribute' => __('entities.factions.singular')]),
      'equipment_type_id.exists' => __('validation.exists', ['attribute' => __('entities.equipment_types.singular')]),
      'attack_range_id.exists' => __('validation.exists', ['attribute' => __('entities.attack_ranges.singular')]),
      'attack_subtype_id.exists' => __('validation.exists', ['attribute' => __('entities.attack_subtypes.singular')]),
      'hero_ability_id.exists' => __('validation.exists', ['attribute' => __('entities.hero_abilities.singular')]),
      'hands.integer' => __('validation.integer', ['attribute' => __('entities.cards.hands')]),
      'hands.in' => __('entities.cards.validation.hands_must_be_1_or_2'),
      'cost.string' => __('validation.string', ['attribute' => __('entities.cards.cost')]),
      'cost.max' => __('validation.max.string', ['attribute' => __('entities.cards.cost'), 'max' => 5]),
      'cost.regex' => __('entities.cards.validation.cost_format'),
      'area.boolean' => __('validation.boolean', ['attribute' => __('entities.cards.area')]),
      'image.image' => __('validation.image', ['attribute' => __('entities.cards.image')]),
      'image.mimes' => __('validation.mimes', ['attribute' => __('entities.cards.image'), 'values' => 'jpeg, png, jpg, gif, svg']),
      'image.max' => __('validation.max.file', ['attribute' => __('entities.cards.image'), 'max' => 2048]),
    ];

    foreach (array_keys(config('laravellocalization.supportedLocales', ['es' => []])) as $locale) {
      $localeName = locale_name($locale);
      $messages["name.{$locale}.unique"] = __('entities.cards.validation.name_unique', ['locale' => $localeName]);
    }

    return $messages;
  }
}