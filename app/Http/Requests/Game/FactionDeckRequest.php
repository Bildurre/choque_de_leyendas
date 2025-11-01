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
      'game_mode_id' => 'required|exists:game_modes,id',
      'faction_ids' => 'required|array|min:1',
      'faction_ids.*' => 'required|exists:factions,id|distinct',
      'hero_ids' => 'nullable|array',
      'hero_ids.*' => 'nullable|exists:heroes,id|distinct',
      'card_ids' => 'nullable|array',
      'card_ids.*' => 'nullable|exists:cards,id|distinct',
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
      'game_mode_id' => __('entities.game_modes.singular'),
      'faction_ids' => __('entities.factions.plural'),
      'faction_ids.*' => __('entities.factions.singular'),
      'hero_ids' => __('entities.heroes.plural'),
      'hero_ids.*' => __('entities.heroes.singular'),
      'card_ids' => __('entities.cards.plural'),
      'card_ids.*' => __('entities.cards.singular'),
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
    ];
  }
}