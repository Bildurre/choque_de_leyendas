<?php

namespace App\Models;

use App\Models\Traits\HasAdminFilters;
use App\Models\Traits\HasCostAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class HeroAbility extends Model
{
  use HasFactory;
  use HasTranslations;
  use SoftDeletes;
  use HasCostAttribute;
  use HasAdminFilters;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'description',
    'attack_range_id',
    'attack_subtype_id',
    'area',
    'cost',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'area' => 'boolean',
    'deleted_at' => 'datetime',
  ];

  /**
   * The attributes that are translatable.
   *
   * @var array
   */
  public $translatable = [
    'name',
    'description'
  ];

  /**
  * Get fields that can be searched
  *
  * @return array
  */
  public function getAdminSearchable(): array
  {
    return ['description'];
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
        'field' => 'attack_range_id',
        'relation' => 'attackRange',
        'label' => __('entities.attack_ranges.singular'),
        'option_label' => 'name',
        'option_value' => 'id'
      ],
      [
        'type' => 'relation',
        'field' => 'attack_subtype_id',
        'relation' => 'attackSubtype',
        'label' => __('entities.attack_subtypes.singular'),
        'option_label' => 'name',
        'option_value' => 'id'
      ],
      [
        'type' => 'enum',
        'field' => 'area',
        'label' => __('entities.hero_abilities.area'),
        'options' => [
          '1' => __('common.yes'),
          '0' => __('common.no')
        ]
      ],
      [
        'type' => 'cost_range',
        'field' => 'cost_total',
        'label' => __('common.total_cost'),
        'options' => [
          '0' => '0',
          '1' => '1',
          '2' => '2',
          '3' => '3',
          '4' => '4',
          '5' => '5+'
        ]
      ],
      [
        'type' => 'cost_colors',
        'field' => 'cost_colors',
        'label' => __('common.cost_contains'),
        'options' => [
          'R' => __('common.cost_colors.red'),
          'G' => __('common.cost_colors.green'),
          'B' => __('common.cost_colors.blue')
        ]
      ],
      [
        'type' => 'cost_exact',
        'field' => 'cost_exact',
        'label' => __('common.cost_exact'),
        'options' => $this->getPredefinedCostOptions()
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
        'label' => __('entities.hero_abilities.name')
      ],
      [
        'field' => 'created_at',
        'label' => __('common.created_at')
      ],
      [
        'field' => 'cost_total',
        'label' => __('common.total_cost'),
        'custom_sort' => 'cost_total'
      ],
      [
        'field' => 'cost_order',
        'label' => __('common.cost_order'),
        'custom_sort' => 'cost_order'
      ]
    ];
  }

  /**
   * Get the attack range that owns the hero ability.
   */
  public function attackRange()
  {
    return $this->belongsTo(AttackRange::class);
  }

  /**
   * Get the attack subtype that owns the hero ability.
   */
  public function attackSubtype()
  {
    return $this->belongsTo(AttackSubtype::class);
  }

  /**
   * Get the heroes that have this ability.
   */
  public function heroes()
  {
    return $this->belongsToMany(Hero::class, 'hero_hero_ability');
  }

  /**
   * Get the cards that are based on this hero ability.
   */
  public function cards()
  {
    return $this->hasMany(Card::class);
  }
}