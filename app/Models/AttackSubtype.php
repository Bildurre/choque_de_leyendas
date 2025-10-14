<?php

namespace App\Models;

use App\Models\Traits\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttackSubtype extends Model
{
  use HasFactory;
  use HasTranslations;
  use SoftDeletes;
  use HasFilters;

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
  * Get fields that can be filtered
  *
  * @return array
  */
  public function getAdminFilterable(): array
  {
    return [
    ];
  }

  /**
  * Get fields that can be sorted
  *
  * @return array
  */
  public function getAdminSortable(): array
  {
    return [
      [
        'field' => 'name',
        'label' => __('entities.attack_subtypes.name')
      ],
      [
        'field' => 'created_at',
        'label' => __('common.created_at')
      ]
    ];
  }

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
}