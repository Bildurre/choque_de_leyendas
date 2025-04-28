<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class CardType extends Model
{
  use HasFactory;
  use HasTranslations;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'hero_superclass_id'
  ];

  /**
   * The attributes that are translatable.
   *
   * @var array
   */
  public $translatable = [
    'name'
  ];

  /**
   * Get the hero superclass associated with the card type.
   */
  public function heroSuperclass(): BelongsTo
  {
    return $this->belongsTo(HeroSuperclass::class);
  }
}