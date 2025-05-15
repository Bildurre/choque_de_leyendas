<?php

namespace App\Models;

use App\Models\Traits\HasAdminFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class EquipmentType extends Model
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
  protected $table = 'equipment_types';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'category',
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
  * Get fields that can be filtered
  *
  * @return array
  */
  public function getAdminFilterable(): array
  {
    return [
      [
        'type' => 'enum',
        'field' => 'category',
        'label' => __('entities.equipment_types.category'),
        'options' => [
          'weapon' => __('entities.equipment_types.categories.weapon'),
          'armor' => __('entities.equipment_types.categories.armor')
        ]
      ]
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
        'label' => __('entities.equipment_types.name')
      ],
      [
        'field' => 'category',
        'label' => __('entities.equipment_types.category')
      ],
      [
        'field' => 'created_at',
        'label' => __('common.created_at')
      ]
    ];
  }

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'deleted_at' => 'datetime',
  ];

  /**
   * Get the cards associated with this equipment type.
   */
  public function cards()
  {
    return $this->hasMany(Card::class);
  }

  /**
   * Get available categories.
   * 
   * @return array
   */
  public static function getCategories(): array
  {
    return [
      'weapon' => __('entities.equipment_types.categories.weapon'),
      'armor' => __('entities.equipment_types.categories.armor'),
    ];
  }

  /**
   * Get the category name.
   * 
   * @return string
   */
  public function getCategoryNameAttribute(): string
  {
    $categories = self::getCategories();
    return $categories[$this->category] ?? $this->category;
  }
}