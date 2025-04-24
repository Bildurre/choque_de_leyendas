<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EquipmentType extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'category'
  ];

  /**
   * Get the equipment cards that belong to this type.
   */
  public function equipment(): HasMany
  {
    return $this->hasMany(Card::class, 'equipment_type_id');
  }

  /**
   * Check if this type is a weapon
   *
   * @return bool
   */
  public function isWeapon(): bool
  {
    return $this->category === 'weapon';
  }

  /**
   * Check if this type is armor
   *
   * @return bool
   */
  public function isArmor(): bool
  {
    return $this->category === 'armor';
  }
}