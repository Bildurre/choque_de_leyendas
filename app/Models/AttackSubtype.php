<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class AttackSubtype extends Model
{
  use HasFactory;
  use HasTranslations;
  use SoftDeletes;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'attack_subtypes';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'type',
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
   * Get the hero abilities associated with this attack subtype.
   */
  public function heroAbilities()
  {
    return $this->hasMany(HeroAbility::class);
  }

  /**
   * Get the cards associated with this attack subtype.
   */
  public function cards()
  {
    return $this->hasMany(Card::class);
  }

  /**
   * Get available types.
   * 
   * @return array
   */
  public static function getTypes(): array
  {
    return [
      'physical' => __('attack_subtypes.types.physical'),
      'magical' => __('attack_subtypes.types.magical'),
    ];
  }

  /**
   * Get the type name.
   * 
   * @return string
   */
  public function getTypeNameAttribute(): string
  {
    $types = self::getTypes();
    return $types[$this->type] ?? $this->type;
  }
}