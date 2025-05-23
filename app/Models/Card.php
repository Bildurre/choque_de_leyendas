<?php

namespace App\Models;

use Spatie\Sluggable\SlugOptions;
use App\Models\Traits\HasAdminFilters;
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
  use HasAdminFilters;
  use HasPublishedAttribute;
  use HasPreviewImage;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'slug',
    'image',
    'preview_image',
    'lore_text',
    'faction_id',
    'card_type_id',
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
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'hands' => 'integer',
    'area' => 'boolean',
    'deleted_at' => 'datetime',
    'is_published' => 'boolean',
    'preview_image' => 'array'
  ];

  /**
   * The attributes that are translatable.
   *
   * @var array
   */
  public $translatable = [
    'name',
    'slug',
    'lore_text',
    'effect',
    'restriction'
  ];

  /**
  * Get fields that can be searched
  *
  * @return array
  */
  public function getAdminSearchable(): array
  {
    return ['lore_text', 'effect', 'restriction'];
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

  /**
   * Get the options for generating the slug.
   */
  public function getSlugOptions(): SlugOptions
  {
    return SlugOptions::create()
      ->generateSlugsFrom('name')
      ->saveSlugsTo('slug');
  }

  /**
   * Get the route key for the model.
   */
  public function getRouteKeyName(): string
  {
    return 'slug';
  }

  /**
   * Get the localized route key for a specific locale.
   *
   * @param string $locale
   * @return string|null
   */
  public function getLocalizedRouteKey($locale)
  {
    return $this->getTranslation('slug', $locale, false);
  }

  /**
   * Get the directory for storing images for this model
   * 
   * @return string
   */
  public function getImageDirectory(): string
  {
    return 'images/cards';
  }

  /**
   * Get the field name for storing images for this model
   * 
   * @return string
   */
  public function getImageField(): string
  {
    return 'image';
  }

  /**
   * Get the faction that owns the card.
   */
  public function faction()
  {
    return $this->belongsTo(Faction::class);
  }

  /**
   * Get the card type that owns the card.
   */
  public function cardType()
  {
    return $this->belongsTo(CardType::class);
  }

  /**
   * Get the equipment type that owns the card.
   */
  public function equipmentType()
  {
    return $this->belongsTo(EquipmentType::class);
  }

  /**
   * Get the attack range that owns the card.
   */
  public function attackRange()
  {
    return $this->belongsTo(AttackRange::class);
  }

  /**
   * Get the attack subtype that owns the card.
   */
  public function attackSubtype()
  {
    return $this->belongsTo(AttackSubtype::class);
  }

  /**
   * Get the hero ability that this card is linked to (if any).
   */
  public function heroAbility()
  {
    return $this->belongsTo(HeroAbility::class);
  }

    /**
   * Get the directory for storing preview images
   * 
   * @return string
   */
  public function getPreviewImageDirectory(): string
  {
    return 'images/previews/cards';
  }
}