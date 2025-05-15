<?php

namespace App\Models;

use App\Models\Traits\HasAdminFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class HeroClass extends Model
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
  protected $table = 'hero_classes';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'passive',
    'hero_superclass_id',
  ];

  /**
   * The attributes that are translatable.
   *
   * @var array
   */
  public $translatable = [
    'name',
    'passive',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'deleted_at' => 'datetime', // Añadimos este cast
  ];

  /**
  * Get fields that can be searched
  *
  * @return array
  */
  public function getAdminSearchable(): array
  {
    return ['passive'];
  }
  
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
        'label' => __('entities.hero_classes.name')
      ],
      [
        'field' => 'heroSuperclass.name',
        'label' => __('entities.hero_superclasses.singular')
      ],
      [
        'field' => 'created_at',
        'label' => __('common.created_at')
      ]
    ];
  }

  /**
   * Get the superclass that owns the hero class.
   */
  public function heroSuperclass(): BelongsTo
  {
    return $this->belongsTo(HeroSuperclass::class, 'hero_superclass_id');
  }

  /**
   * Get the heros that belong to this hero class.
   */
  public function heroes()
  {
    return $this->hasMany(Hero::class);
  }
}