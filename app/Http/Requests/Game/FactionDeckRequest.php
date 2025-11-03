<?php

namespace App\Http\Requests\Game;

use Illuminate\Foundation\Http\FormRequest;

class FactionDeckRequest extends FormRequest
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
    $rules = [
      'name.en' => 'required|string|max:255',
      'name.es' => 'required|string|max:255',
      'description.en' => 'nullable|string',
      'description.es' => 'nullable|string',
      'epic_quote.en' => 'nullable|string',
      'epic_quote.es' => 'nullable|string',
      'game_mode_id' => 'required|exists:game_modes,id',
      'faction_ids' => 'required|array|min:1',
      'faction_ids.*' => 'required|exists:factions,id|distinct',
      'heroes' => 'nullable|array',
      'heroes.*.id' => 'required|exists:heroes,id|distinct',
      'cards' => 'nullable|array',
      'cards.*.id' => 'required|exists:cards,id',
      'cards.*.copies' => 'required|integer|min:1|max:3',
      'icon' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
      'remove_icon' => 'nullable|boolean',
      'is_published' => 'boolean',
    ];

    return $rules;
  }

  /**
   * Get custom attributes for validator errors.
   *
   * @return array<string, string>
   */
  public function attributes(): array
  {
    return [
      'name.en' => __('entities.faction_decks.name') . ' (EN)',
      'name.es' => __('entities.faction_decks.name') . ' (ES)',
      'description.en' => __('entities.faction_decks.description') . ' (EN)',
      'description.es' => __('entities.faction_decks.description') . ' (ES)',
      'epic_quote.en' => __('entities.faction_decks.epic_quote') . ' (EN)',
      'epic_quote.es' => __('entities.faction_decks.epic_quote') . ' (ES)',
      'game_mode_id' => __('entities.game_modes.singular'),
      'faction_ids' => __('entities.factions.plural'),
      'faction_ids.*' => __('entities.factions.singular'),
      'hero_ids' => __('entities.heroes.plural'),
      'hero_ids.*' => __('entities.heroes.singular'),
      'card_ids' => __('entities.cards.plural'),
      'card_ids.*' => __('entities.cards.singular'),
      'icon' => __('entities.faction_decks.icon'),
      'is_published' => __('admin.publication_status'),
    ];
  }

  /**
   * Get custom messages for validator errors.
   *
   * @return array<string, string>
   */
  public function messages(): array
  {
    return [
      'faction_ids.required' => __('validation.required', ['attribute' => __('entities.factions.plural')]),
      'faction_ids.min' => __('validation.min.array', ['attribute' => __('entities.factions.plural'), 'min' => 1]),
      'faction_ids.*.exists' => __('validation.exists', ['attribute' => __('entities.factions.singular')]),
      'faction_ids.*.distinct' => __('validation.distinct', ['attribute' => __('entities.factions.singular')]),
      'icon.image' => __('validation.image', ['attribute' => __('entities.faction_decks.icon')]),
      'icon.mimes' => __('validation.mimes', ['attribute' => __('entities.faction_decks.icon'), 'values' => 'jpeg, jpg, png, gif, webp']),
      'icon.max' => __('validation.max.file', ['attribute' => __('entities.faction_decks.icon'), 'max' => '2MB']),
    ];
  }
}