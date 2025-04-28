<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class HeroClass extends Model
{
  use HasFactory;
  use HasTranslations;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'hero_classes';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'passive',
    'hero_superclass_id',
  ];

  /**
   * The attributes that are translatable.
   *
   * @var array
   */
  public $translatable = [
    'name',
    'passive',
  ];

  /**
   * Get the superclass that owns the hero class.
   */
  public function heroSuperclass(): BelongsTo
  {
    return $this->belongsTo(HeroSuperclass::class, 'hero_superclass_id');
  }

  /**
   * Get the heros that belong to this hero class.
   */
  public function heroes()
  {
    return $this->hasMany(Hero::class);
  }
}