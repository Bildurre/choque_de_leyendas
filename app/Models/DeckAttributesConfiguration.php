<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeckAttributesConfiguration extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'game_mode_id',
    'min_cards',
    'max_cards',
    'max_copies_per_card',
    'required_heroes',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'min_cards' => 'integer',
    'max_cards' => 'integer',
    'max_copies_per_card' => 'integer',
    'required_heroes' => 'integer',
  ];

  /**
   * Get the game mode associated with this faction deck.
   */
  public function gameMode()
  {
    return $this->belongsTo(GameMode::class);
  }

  /**
   * Validate if a deck meets the configuration requirements
   * 
   * @param int $totalCards Total number of cards (sum of copies)
   * @param bool $hasExceededCardCopies Whether any card exceeds max copies
   * @param int $totalHeroes Total number of heroes
   * @return array Array with validation results
   */
  public function validateDeck(int $totalCards, bool $hasExceededCardCopies, int $totalHeroes = 0): array
  {
    $isValid = true;
    $errors = [];

    // Check total cards
    if ($totalCards < $this->min_cards) {
      $isValid = false;
      $errors[] = "El mazo debe tener al menos {$this->min_cards} cartas.";
    }

    if ($totalCards > $this->max_cards) {
      $isValid = false;
      $errors[] = "El mazo no puede tener más de {$this->max_cards} cartas.";
    }

    // Check card copies
    if ($hasExceededCardCopies) {
      $isValid = false;
      $errors[] = "El mazo no puede tener más de {$this->max_copies_per_card} copias de una misma carta.";
    }

    // Check required heroes (exact number)
    if ($this->required_heroes > 0 && $totalHeroes != $this->required_heroes) {
      $isValid = false;
      $errors[] = "El mazo debe tener exactamente {$this->required_heroes} héroes.";
    }

    return [
      'isValid' => $isValid,
      'errors' => $errors,
    ];
  }
}