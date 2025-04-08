<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\HasColorAttribute;

class AttackSubtype extends Model
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
    'attack_type_id',
    'color',
    'text_is_dark'
  ];

  /**
   * Get the type that owns this subtype.
   */
  public function type(): BelongsTo
  {
    return $this->belongsTo(AttackType::class, 'attack_type_id');
  }

  /**
   * Get the abilities with this subtype.
   */
  public function abilities(): HasMany
  {
    return $this->hasMany(HeroAbility::class);
  }
  
  /**
   * Get default color if none is specified
   */
  public function getColorAttribute($value)
  {
    return $value ?: '#666666';
  }
}