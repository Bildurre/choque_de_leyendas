<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Card extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'description',
    'faction_id',
    'type',
    'cost',
    'effect'
  ];

  /**
   * Get the faction that owns the card.
   */
  public function faction(): BelongsTo
  {
    return $this->belongsTo(Faction::class);
  }
}