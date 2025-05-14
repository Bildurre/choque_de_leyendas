<?php

namespace App\Models;

use Spatie\Sluggable\SlugOptions;
use App\Models\Traits\HasAdminFilters;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasImageAttribute;
use Spatie\Translatable\HasTranslations;
use Spatie\Sluggable\HasTranslatableSlug;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Services\Game\HeroAttributesConfigurationService;

class Hero extends Model
{
  use HasFactory;
  use HasTranslations;
  use HasTranslatableSlug;
  use SoftDeletes;
  use HasImageAttribute;
  use HasAdminFilters;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'heroes';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'slug',
    'lore_text',
    'passive_name',
    'passive_description',
    'image',
    'faction_id',
    'hero_race_id',
    'hero_class_id',
    'gender',
    'agility',
    'mental',
    'will',
    'strength',
    'armor',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'agility' => 'integer',
    'mental' => 'integer',
    'will' => 'integer',
    'strength' => 'integer',
    'armor' => 'integer',
    'deleted_at' => 'datetime',
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
    'passive_name',
    'passive_description'
  ];

  /**
  * Get fields that can be searched
  *
  * @return array
  */
  public function getAdminSearchable(): array
  {
    return ['name', 'lore_text'];
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
        'field' => 'faction.id',
        'label' => __('entities.factions.singular'),
        'label_field' => 'name', // Campo a mostrar como etiqueta
        'value_field' => 'id'    // Campo a usar como valor
      ],
      [
        'field' => 'heroRace.id',
        'label' => __('entities.hero_races.singular'),
        'label_field' => 'name',
        'value_field' => 'id'
      ],
      [
        'field' => 'heroClass.id',
        'label' => __('entities.hero_classes.singular'),
        'label_field' => 'name',
        'value_field' => 'id'
      ],
      [
        'field' => 'heroClass.hero_superclass_id',
        'label' => __('entities.hero_superclasses.singular'),
        'label_field' => 'name',
        'value_field' => 'id',
        'relation_path' => 'heroClass.heroSuperclass' // Camino para llegar a la relación
      ],
      [
        'field' => 'gender',
        'label' => __('entities.heroes.gender'),
        'options' => [
          'male' => __('entities.heroes.genders.male'),
          'female' => __('entities.heroes.genders.female')
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
          'label' => __('entities.heroes.name')
        ],
        [
          'field' => 'faction.name',
          'label' => __('entities.factions.singular')
        ],
        [
          'field' => 'created_at',
          'label' => __('common.created_at')
        ],
        [
          'field' => 'updated_at',
          'label' => __('common.updated_at')
        ]
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
   * Get the directory for storing images for this model
   * 
   * @return string
   */
  public function getImageDirectory(): string
  {
    return 'images/heroes';
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
   * Get the faction that owns the hero.
   */
  public function faction()
  {
    return $this->belongsTo(Faction::class);
  }
  
  /**
   * Get the race of the hero.
   */
  public function heroRace()
  {
    return $this->belongsTo(HeroRace::class);
  }
  
  /**
   * Get the class of the hero.
   */
  public function heroClass()
  {
    return $this->belongsTo(HeroClass::class);
  }

  /**
   * Get the abilities of the hero.
   */
  public function heroAbilities()
  {
    return $this->belongsToMany(HeroAbility::class, 'hero_hero_ability');
  }

  /**
   * Calculate total health based on attributes and configuration
   * 
   * @return int
   */
  public function getHealthAttribute(): int
  {
    $configService = app(HeroAttributesConfigurationService::class);
    return $configService->calculateHealth(
      $this->agility,
      $this->mental,
      $this->will,
      $this->strength,
      $this->armor
    );
  }

  /**
   * Get the total attribute points used
   * 
   * @return int
   */
  public function getTotalAttributesAttribute(): int
  {
    return $this->agility + $this->mental + $this->will + $this->strength + $this->armor;
  }
}