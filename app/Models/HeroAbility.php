<?php

namespace App\Models;

use App\Models\Traits\HasFilters;
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
  use HasFilters;

  protected $fillable = [
    'name',
    'description',
    'attack_type',
    'attack_range_id',
    'attack_subtype_id',
    'area',
    'cost',
  ];

  protected $casts = [
    'area' => 'boolean',
    'deleted_at' => 'datetime',
  ];

  public $translatable = [
    'name',
    'description'
  ];

  /**
   * Get available attack types
   *
   * @return array
   */
  public static function getAttackTypes(): array
  {
    return [
      'physical' => __('entities.hero_abilities.attack_types.physical'),
      'magical' => __('entities.hero_abilities.attack_types.magical'),
    ];
  }

  public function getAdminSearchable(): array
  {
    return ['description'];
  }

  public function getAdminFilterable(): array
  {
    return [
      [
        'type' => 'enum',
        'field' => 'attack_type',
        'label' => __('entities.hero_abilities.attack_type'),
        'options' => self::getAttackTypes()
      ],
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
          'R' => 'R',
          'G' => 'G',
          'B' => 'B'
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
  
  public function getAdminSortable(): array
  {
    return [
      [
        'field' => 'name',
        'label' => __('entities.hero_abilities.name')
      ],
      [
        'field' => 'attack_type',
        'label' => __('entities.hero_abilities.attack_type')
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

  public function attackRange()
  {
    return $this->belongsTo(AttackRange::class);
  }

  public function attackSubtype()
  {
    return $this->belongsTo(AttackSubtype::class);
  }

  public function heroes()
  {
    return $this->belongsToMany(Hero::class, 'hero_hero_ability');
  }

  public function cards()
  {
    return $this->hasMany(Card::class);
  }
}