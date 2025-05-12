<?php

namespace App\Http\Requests\Game;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeckAttributesConfigurationRequest extends FormRequest
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
    $configId = $this->route('deck_attributes_configuration')?->id;
    
    return [
      'game_mode_id' => [
        'required',
        'exists:game_modes,id',
        Rule::unique('deck_attributes_configurations')->ignore($configId)
      ],
      'min_cards' => ['required', 'integer', 'min:1', 'max:100', 'lte:max_cards'],
      'max_cards' => ['required', 'integer', 'min:1', 'max:200', 'gte:min_cards'],
      'max_copies_per_card' => ['required', 'integer', 'min:1', 'max:10'],
      'max_copies_per_hero' => ['required', 'integer', 'min:1', 'max:5'],
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
      'game_mode_id.required' => __('deck_attributes.validation.game_mode_required'),
      'game_mode_id.exists' => __('game_modes.not_exists'),
      'game_mode_id.unique' => __('deck_attributes.validation.game_mode_exists'),
      
      'min_cards.required' => __('deck_attributes.validation.min_cards_required'),
      'min_cards.integer' => __('deck_attributes.validation.min_cards_integer'),
      'min_cards.min' => __('deck_attributes.validation.min_cards_range', ['min' => 1, 'max' => 100]),
      'min_cards.max' => __('deck_attributes.validation.min_cards_range', ['min' => 1, 'max' => 100]),
      'min_cards.lte' => __('deck_attributes.validation.max_cards_min_relation'),
      
      'max_cards.required' => __('deck_attributes.validation.max_cards_required'),
      'max_cards.integer' => __('deck_attributes.validation.max_cards_integer'),
      'max_cards.min' => __('deck_attributes.validation.max_cards_range', ['min' => 1, 'max' => 200]),
      'max_cards.max' => __('deck_attributes.validation.max_cards_range', ['min' => 1, 'max' => 200]),
      'max_cards.gte' => __('deck_attributes.validation.max_cards_min_relation'),
      
      'max_copies_per_card.required' => __('deck_attributes.validation.max_copies_per_card_required'),
      'max_copies_per_card.integer' => __('deck_attributes.validation.max_copies_per_card_integer'),
      'max_copies_per_card.min' => __('deck_attributes.validation.max_copies_per_card_range', ['min' => 1, 'max' => 10]),
      'max_copies_per_card.max' => __('deck_attributes.validation.max_copies_per_card_range', ['min' => 1, 'max' => 10]),
      
      'max_copies_per_hero.required' => __('deck_attributes.validation.max_copies_per_hero_required'),
      'max_copies_per_hero.integer' => __('deck_attributes.validation.max_copies_per_hero_integer'),
      'max_copies_per_hero.min' => __('deck_attributes.validation.max_copies_per_hero_range', ['min' => 1, 'max' => 5]),
      'max_copies_per_hero.max' => __('deck_attributes.validation.max_copies_per_hero_range', ['min' => 1, 'max' => 5]),
    ];
  }
}