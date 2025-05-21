<?php

namespace App\Models;

use App\Models\Traits\HasAdminFilters;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasImageAttribute;
use Spatie\Translatable\HasTranslations;
use App\Models\Traits\HasPublishedAttribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Counter extends Model
{
  use HasFactory;
  use SoftDeletes;
  use HasTranslations;
  use HasImageAttribute;
  use HasAdminFilters;
  use HasPublishedAttribute;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'effect',
    'type',
    'icon',
    'is_published',
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
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'is_published' => 'boolean',
    'deleted_at' => 'datetime',
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
      ],
      [
        'type' => 'enum',
        'field' => 'is_published',
        'label' => __('admin.publication_status'),
        'options' => [
          '1' => __('admin.published'),
          '0' => __('admin.draft')
        ]
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
        'label' => __('entities.counters.singular')
      ],
      [
        'field' => 'type',
        'label' => __('entities.counters.type')
      ],
      [
        'field' => 'created_at',
        'label' => __('common.created_at')
      ],
      [
        'field' => 'is_published',
        'label' => __('admin.publication_status')
      ],
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
    return __('entities.counters.types.' . $this->type);
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