<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\HasColorAttribute;

class AttackType extends Model
{
  use HasFactory;
  use HasColorAttribute;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'description',
    'color',
    'text_is_dark'
  ];

  /**
   * Get the subtypes for this attack type.
   */
  public function subtypes(): HasMany
  {
    return $this->hasMany(AttackSubtype::class);
  }
}