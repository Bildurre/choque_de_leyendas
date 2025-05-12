<?php

namespace App\Http\Requests\Game;

use Illuminate\Foundation\Http\FormRequest;

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
    return [
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
      'min_cards.required' => 'El número mínimo de cartas es obligatorio.',
      'min_cards.integer' => 'El número mínimo de cartas debe ser un número entero.',
      'min_cards.min' => 'El número mínimo de cartas debe ser al menos 1.',
      'min_cards.max' => 'El número mínimo de cartas no puede ser mayor que 100.',
      'min_cards.lte' => 'El número mínimo de cartas debe ser menor o igual que el número máximo.',
      
      'max_cards.required' => 'El número máximo de cartas es obligatorio.',
      'max_cards.integer' => 'El número máximo de cartas debe ser un número entero.',
      'max_cards.min' => 'El número máximo de cartas debe ser al menos 1.',
      'max_cards.max' => 'El número máximo de cartas no puede ser mayor que 200.',
      'max_cards.gte' => 'El número máximo de cartas debe ser mayor o igual que el número mínimo.',
      
      'max_copies_per_card.required' => 'El número máximo de copias por carta es obligatorio.',
      'max_copies_per_card.integer' => 'El número máximo de copias por carta debe ser un número entero.',
      'max_copies_per_card.min' => 'El número máximo de copias por carta debe ser al menos 1.',
      'max_copies_per_card.max' => 'El número máximo de copias por carta no puede ser mayor que 10.',
      
      'max_copies_per_hero.required' => 'El número máximo de copias por héroe es obligatorio.',
      'max_copies_per_hero.integer' => 'El número máximo de copias por héroe debe ser un número entero.',
      'max_copies_per_hero.min' => 'El número máximo de copias por héroe debe ser al menos 1.',
      'max_copies_per_hero.max' => 'El número máximo de copias por héroe no puede ser mayor que 5.',
    ];
  }
}