<?php

namespace App\Models;

use App\Models\Traits\HasAdminFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class AttackRange extends Model
{
  use HasFactory;
  use HasTranslations;
  use SoftDeletes;
  use HasAdminFilters;

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
}