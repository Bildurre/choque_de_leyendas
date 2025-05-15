<?php

namespace App\Models;

use App\Models\Traits\HasAdminFilters;
use App\Models\Traits\HasImageAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Counter extends Model
{
  use HasFactory, SoftDeletes, HasTranslations, HasImageAttribute, HasAdminFilters;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'effect',
    'type',
    'icon'
  ];

  /**
   * The attributes that are translatable.
   *
   * @var array<int, string>
   */
  public $translatable = [
    'name',
    'effect'
  ];

  /**
  * Get fields that can be searched
  *
  * @return array
  */
  public function getAdminSearchable(): array
  {
    return ['effect'];
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
      'type' => 'enum',
      'field' => 'type',
      'label' => __('entities.counters.type'),
      'options' => [
        'boon' => __('entities.counters.types.boon'),
        'bane' => __('entities.counters.types.bane')
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
        'label' => __('entities.counters.singular')
      ],
      [
        'field' => 'type',
        'label' => __('entities.counters.type')
      ],
      [
        'field' => 'created_at',
        'label' => __('common.created_at')
      ]
    ];
  }

  /**
   * Get the directory for storing images for this model
   * 
   * @return string
   */
  public function getImageDirectory(): string
  {
    return 'images/counters';
  }

  /**
   * Get the type name attribute
   * 
   * @return string
   */
  public function getTypeNameAttribute(): string
  {
    return __('counters.types.' . $this->type);
  }

  /**
   * Get array of available counter types
   * 
   * @return array
   */
  public static function getTypes(): array
  {
    return [
      'boon' => __('entities.counters.types.boon'),
      'bane' => __('entities.counters.types.bane')
    ];
  }
}