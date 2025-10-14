<?php

namespace App\Models;

use Spatie\Sluggable\SlugOptions;
use App\Models\Traits\HasFilters;
use App\Models\Traits\HasPreviewImage;
use App\Models\Traits\HasCostAttribute;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasImageAttribute;
use Spatie\Translatable\HasTranslations;
use Spatie\Sluggable\HasTranslatableSlug;
use App\Models\Traits\HasPublishedAttribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Mcamara\LaravelLocalization\Interfaces\LocalizedUrlRoutable;

class Card extends Model implements LocalizedUrlRoutable
{
  use HasFactory;
  use HasTranslations;
  use HasTranslatableSlug;
  use SoftDeletes;
  use HasImageAttribute;
  use HasCostAttribute;
  use HasFilters;
  use HasPublishedAttribute;
  use HasPreviewImage;

  protected $fillable = [
    'name',
    'slug',
    'image',
    'preview_image',
    'lore_text',
    'epic_quote',
    'faction_id',
    'card_type_id',
    'card_subtype_id',
    'equipment_type_id',
    'attack_range_id',
    'attack_subtype_id',
    'hero_ability_id',
    'hands',
    'cost',
    'effect',
    'restriction',
    'area',
    'is_published',
    'is_unique',
    'attack_type'
  ];

  protected $casts = [
    'hands' => 'integer',
    'area' => 'boolean',
    'deleted_at' => 'datetime',
    'is_published' => 'boolean',
    'is_unique' => 'boolean',
    'preview_image' => 'array'
  ];

  public $translatable = [
    'name',
    'slug',
    'lore_text',
    'epic_quote',
    'effect',
    'restriction'
  ];

  /**
   * Card type IDs that support subtypes (Spell and Technique)
   */
  const SPELL_TYPE_ID = 5;
  const TECHNIQUE_TYPE_ID = 4;

  /**
   * Check if this card's type supports subtypes
   *
   * @return bool
   */
  public function supportsSubtype(): bool
  {
    return in_array($this->card_type_id, [self::SPELL_TYPE_ID, self::TECHNIQUE_TYPE_ID]);
  }

  public function getAdminSearchable(): array
  {
    return ['lore_text', 'effect', 'restriction'];
  }

  public function getPublicSearchable(): array
  {
    return ['lore_text', 'effect', 'restriction'];
  }
  
  public function getAdminFilterable(): array
  {
    return [
      [
        'type' => 'relation',
        'field' => 'faction_id',
        'relation' => 'faction',
        'label' => __('entities.factions.singular'),
        'option_label' => 'name',
        'option_value' => 'id'
      ],
      [
        'type' => 'relation',
        'field' => 'card_type_id',
        'relation' => 'cardType',
        'label' => __('entities.card_types.singular'),
        'option_label' => 'name',
        'option_value' => 'id'
      ],
      [
        'type' => 'relation',
        'field' => 'card_subtype_id',
        'relation' => 'cardSubtype',
        'label' => __('entities.card_subtypes.singular'),
        'option_label' => 'name',
        'option_value' => 'id'
      ],
      [
        'type' => 'relation',
        'field' => 'equipment_type_id',
        'relation' => 'equipmentType',
        'label' => __('entities.equipment_types.singular'),
        'option_label' => 'name',
        'option_value' => 'id'
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

  public function getPublicFilterable(): array
  {
    return [
      [
        'type' => 'relation',
        'field' => 'faction_id',
        'relation' => 'faction',
        'label' => __('entities.factions.singular'),
        'option_label' => 'name',
        'option_value' => 'id'
      ],
      [
        'type' => 'relation',
        'field' => 'card_type_id',
        'relation' => 'cardType',
        'label' => __('entities.card_types.singular'),
        'option_label' => 'name',
        'option_value' => 'id'
      ],
      [
        'type' => 'relation',
        'field' => 'card_subtype_id',
        'relation' => 'cardSubtype',
        'label' => __('entities.card_subtypes.singular'),
        'option_label' => 'name',
        'option_value' => 'id'
      ],
      [
        'type' => 'relation',
        'field' => 'equipment_type_id',
        'relation' => 'equipmentType',
        'label' => __('entities.equipment_types.singular'),
        'option_label' => 'name',
        'option_value' => 'id'
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
        'label' => __('entities.cards.area'),
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
      ],
    ];
  }
  
  public function getAdminSortable(): array
  {
    return [
      [
        'field' => 'name',
        'label' => __('entities.cards.name')
      ],
      [
        'field' => 'cardType.name',
        'label' => __('entities.card_types.singular')
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
      ],
      [
        'field' => 'is_published',
        'label' => __('admin.publication_status')
      ],
    ];
  }

  public function getPublicSortable(): array
  {
    return [
      [
        'field' => 'name',
        'label' => __('entities.cards.name')
      ],
      [
        'field' => 'faction.name',
        'label' => __('entities.factions.singular')
      ],
      [
        'field' => 'cardType.name',
        'label' => __('entities.card_types.singular')
      ],
      [
        'field' => 'cost_total',
        'label' => __('common.total_cost'),
        'custom_sort' => 'cost_total'
      ],
    ];
  }

  public function getSlugOptions(): SlugOptions
  {
    return SlugOptions::create()
      ->generateSlugsFrom('name')
      ->saveSlugsTo('slug');
  }

  public function getRouteKeyName(): string
  {
    return 'slug';
  }

  public function getLocalizedRouteKey($locale)
  {
    return $this->getTranslation('slug', $locale, false);
  }

  public function getImageDirectory(): string
  {
    return 'images/cards';
  }

  public function getImageField(): string
  {
    return 'image';
  }

  public function faction()
  {
    return $this->belongsTo(Faction::class);
  }

  public function cardType()
  {
    return $this->belongsTo(CardType::class);
  }

  public function cardSubtype()
  {
    return $this->belongsTo(CardSubtype::class);
  }

  public function equipmentType()
  {
    return $this->belongsTo(EquipmentType::class);
  }

  public function attackRange()
  {
    return $this->belongsTo(AttackRange::class);
  }

  public function attackSubtype()
  {
    return $this->belongsTo(AttackSubtype::class);
  }

  public function heroAbility()
  {
    return $this->belongsTo(HeroAbility::class);
  }

  public function getPreviewImageDirectory(): string
  {
    return 'images/previews/cards';
  }

    /**
   * Get available attack types
   *
   * @return array
   */
  public static function getAttackTypes(): array
  {
    return [
      'physical' => __('entities.cards.attack_types.physical'),
      'magical' => __('entities.cards.attack_types.magical'),
    ];
  }
}