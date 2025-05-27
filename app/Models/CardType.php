<?php

namespace App\Models;

use App\Models\Traits\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class CardType extends Model
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
  protected $table = 'card_types';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'hero_superclass_id',
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
      [
        'type' => 'relation',
        'field' => 'hero_superclass_id',
        'relation' => 'heroSuperclass',
        'label' => __('entities.hero_superclasses.singular'),
        'option_label' => 'name',
        'option_value' => 'id'
      ],
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
        'label' => __('entities.card_types.name')
      ],
      [
        'field' => 'created_at',
        'label' => __('common.created_at')
      ]
    ];
  }

  /**
   * Get the hero superclass associated with this card type.
   */
  public function heroSuperclass()
  {
    return $this->belongsTo(HeroSuperclass::class);
  }

  /**
   * Get the cards that belong to this card type.
   */
  public function cards()
  {
    return $this->hasMany(Card::class);
  }
}