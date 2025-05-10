<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class AttackRange extends Model
{
  use HasFactory;
  use HasTranslations;
  use SoftDeletes;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'attack_ranges';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'icon',
  ];

  /**
   * The attributes that are translatable.
   *
   * @var array
   */
  public $translatable = [
    'name',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'deleted_at' => 'datetime',
  ];

  /**
   * Get the hero abilities associated with this attack range.
   */
  public function heroAbilities()
  {
    return $this->hasMany(HeroAbility::class);
  }

  /**
   * Get the cards associated with this attack range.
   */
  public function cards()
  {
    return $this->hasMany(Card::class);
  }

  /**
   * Get the directory for storing icons for this model
   * 
   * @return string
   */
  public function getImageDirectory(): string
  {
    return 'images/attack-ranges';
  }

  /**
   * Get the icon URL
   */
  public function getIconUrl(): ?string
  {
    if (!$this->icon) {
      return null;
    }
    
    return asset('storage/' . $this->icon);
  }
}