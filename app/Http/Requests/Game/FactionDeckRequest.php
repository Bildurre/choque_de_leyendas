<?php

namespace App\Http\Requests\Game;

use App\Http\Requests\Traits\ValidatesTranslatableUniqueness;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FactionDeckRequest extends FormRequest
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
    $factionDeckId = $this->route('faction_deck');
    $locales = config('app.available_locales', ['es']);
    
    $rules = [
      'name' => ['required', 'array'],
      'name.es' => ['required', 'string', 'max:255'],
      'faction_id' => ['required', 'exists:factions,id'],
      'icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
      'remove_icon' => ['nullable', 'boolean'],
      'cards' => ['nullable', 'array'],
      'cards.*.id' => ['exists:cards,id'],
      'cards.*.copies' => ['required', 'integer', 'min:1'],
      'heroes' => ['nullable', 'array'],
      'heroes.*.id' => ['exists:heroes,id'],
      'heroes.*.copies' => ['required', 'integer', 'min:1'],
    ];

    // Solo añadir la regla de game_mode_id si es una nueva creación
    if (!$factionDeckId) {
      $rules['game_mode_id'] = ['required', 'exists:game_modes,id'];
    }

    // Add uniqueness rules for each locale within the same faction
    foreach ($locales as $locale) {
      $rules["name.{$locale}"] = [
        'required',
        function ($attribute, $value, $fail) use ($locale, $factionDeckId) {
          $query = \App\Models\FactionDeck::whereRaw("JSON_EXTRACT(name, '$.\"{$locale}\"') = ?", [$value])
            ->where('faction_id', $this->faction_id);
          
          if ($factionDeckId) {
            $query->where('id', '!=', $factionDeckId);
          }
          
          if ($query->exists()) {
            $fail("Ya existe un mazo de facción con este nombre en " . locale_name($locale) . " para esta facción.");
          }
        }
      ];
    }

    return $rules;
  }

  /**
   * Prepare the data for validation.
   */
  protected function prepareForValidation()
  {
    // Si estamos editando, obtener el game_mode_id existente
    if ($this->route('faction_deck')) {
      $this->merge([
        'game_mode_id' => $this->route('faction_deck')->game_mode_id,
      ]);
    }
  }

  /**
   * Get custom validation messages.
   */
  public function messages(): array
  {
    return [
      'name.required' => 'El nombre del mazo es obligatorio.',
      'name.array' => 'El nombre debe ser un array con traducciones.',
      'name.es.required' => 'El nombre en español es obligatorio.',
      'faction_id.required' => 'La facción es obligatoria.',
      'faction_id.exists' => 'La facción seleccionada no existe.',
      'game_mode_id.required' => 'El modo de juego es obligatorio.',
      'game_mode_id.exists' => 'El modo de juego seleccionado no existe.',
      'icon.image' => 'El archivo debe ser una imagen válida.',
      'icon.mimes' => 'El archivo debe ser de tipo: jpeg, png, jpg, gif, svg.',
      'icon.max' => 'La imagen no debe ser mayor de 2MB.',
      'cards.array' => 'Las cartas deben ser un array.',
      'cards.*.id.exists' => 'La carta seleccionada no existe.',
      'cards.*.copies.required' => 'El número de copias es obligatorio.',
      'cards.*.copies.integer' => 'El número de copias debe ser un número entero.',
      'cards.*.copies.min' => 'El número de copias debe ser al menos 1.',
      'heroes.array' => 'Los héroes deben ser un array.',
      'heroes.*.id.exists' => 'El héroe seleccionado no existe.',
      'heroes.*.copies.required' => 'El número de copias es obligatorio.',
      'heroes.*.copies.integer' => 'El número de copias debe ser un número entero.',
      'heroes.*.copies.min' => 'El número de copias debe ser al menos 1.',
    ];
  }
}