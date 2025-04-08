<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttackRange extends Model
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
    'icon'
  ];

  /**
   * Get the abilities with this range.
   */
  public function abilities(): HasMany
  {
    return $this->hasMany(HeroAbility::class);
  }
}