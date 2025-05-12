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
    'min_cards',
    'max_cards',
    'max_copies_per_card',
    'max_copies_per_hero',
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
    'max_copies_per_hero' => 'integer',
  ];

  /**
   * Get the default configuration
   * 
   * @return \App\Models\DeckAttributesConfiguration
   */
  public static function getDefault(): self
  {
    return self::firstOrCreate([], [
      'min_cards' => 30,
      'max_cards' => 40,
      'max_copies_per_card' => 2,
      'max_copies_per_hero' => 1,
    ]);
  }

  /**
   * Validate if a deck meets the configuration requirements
   * 
   * @param int $totalCards
   * @param bool $hasExceededCardCopies
   * @param bool $hasExceededHeroCopies
   * @return array Array with validation results
   */
  public function validateDeck(int $totalCards, bool $hasExceededCardCopies, bool $hasExceededHeroCopies): array
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

    // Check hero copies
    if ($hasExceededHeroCopies) {
      $isValid = false;
      $errors[] = "El mazo no puede tener más de {$this->max_copies_per_hero} copias de un mismo héroe.";
    }

    return [
      'isValid' => $isValid,
      'errors' => $errors,
    ];
  }
}