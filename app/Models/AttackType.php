<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttackType extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name'
  ];

  /**
   * Get the subtypes for this attack type.
   */
  public function subtypes(): HasMany
  {
    return $this->hasMany(AttackSubtype::class);
  }
}