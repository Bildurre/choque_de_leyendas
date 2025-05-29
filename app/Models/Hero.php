<?php

namespace App\Models;

use Spatie\Sluggable\SlugOptions;
use App\Models\Traits\HasFilters;
use App\Models\Traits\HasPreviewImage;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasImageAttribute;
use Spatie\Translatable\HasTranslations;
use Spatie\Sluggable\HasTranslatableSlug;
use App\Models\Traits\HasPublishedAttribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Services\Game\HeroAttributesConfigurationService;
use Mcamara\LaravelLocalization\Interfaces\LocalizedUrlRoutable;

class Hero extends Model implements LocalizedUrlRoutable
{
  use HasFactory;
  use HasTranslations;
  use HasTranslatableSlug;
  use SoftDeletes;
  use HasImageAttribute;
  use HasFilters;
  use HasPublishedAttribute;
  use HasPreviewImage;

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
    'preview_image',
    'faction_id',
    'hero_race_id',
    'hero_class_id',
    'gender',
    'agility',
    'mental',
    'will',
    'strength',
    'armor',
    'is_published',
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
    'passive_name',
    'passive_description'
  ];

  /**
   * Get fields that can be searched in admin
   *
   * @return array
   */
  public function getAdminSearchable(): array
  {
    return ['lore_text'];
  }

  /**
   * Get fields that can be searched in public
   *
   * @return array
   */
  public function getPublicSearchable(): array
  {
    return ['lore_text', 'passive_name', 'passive_description'];
  }
  
  /**
   * Get fields that can be filtered in admin
   *
   * @return array
   */
  public function getAdminFilterable(): array
  {
    return [
      // Filtro para una relación directa
      [
        'type' => 'relation',                           // Tipo de filtro: relation, enum, boolean, etc.
        'field' => 'faction_id',                        // Campo real en la tabla (para filtrar)
        'relation' => 'faction',                        // Nombre de la relación
        'label' => __('entities.factions.singular'),    // Etiqueta a mostrar
        'option_label' => 'name',                       // Campo para mostrar como etiqueta
        'option_value' => 'id'                          // Campo para usar como valor
      ],
      // Filtro para superclass a través de heroClass
      [
        'type' => 'nested_relation',
        'field' => 'heroClass.hero_superclass_id',     // Campo a filtrar (incluyendo relación)
        'through' => ['heroClass', 'heroSuperclass'],  // Ruta de relaciones
        'label' => __('entities.hero_superclasses.singular'),
        'option_model' => \App\Models\HeroSuperclass::class, // Modelo para obtener opciones directamente
        'option_label' => 'name',
        'option_value' => 'id'
      ],
      [
        'type' => 'relation',
        'field' => 'hero_class_id', 
        'relation' => 'heroClass',
        'label' => __('entities.hero_classes.singular'),
        'option_label' => 'name',
        'option_value' => 'id'
      ],
      [
        'type' => 'relation',
        'field' => 'hero_race_id',
        'relation' => 'heroRace',
        'label' => __('entities.hero_races.singular'),
        'option_label' => 'name',
        'option_value' => 'id'
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
   * Get fields that can be filtered in public
   *
   * @return array
   */
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
        'type' => 'nested_relation',
        'field' => 'heroClass.hero_superclass_id',
        'through' => ['heroClass', 'heroSuperclass'],
        'label' => __('entities.hero_superclasses.singular'),
        'option_model' => \App\Models\HeroSuperclass::class,
        'option_label' => 'name',
        'option_value' => 'id'
      ],
      [
        'type' => 'relation',
        'field' => 'hero_class_id',
        'relation' => 'heroClass',
        'label' => __('entities.hero_classes.singular'),
        'option_label' => 'name',
        'option_value' => 'id'
      ],
      [
        'type' => 'relation',
        'field' => 'hero_race_id',
        'relation' => 'heroRace',
        'label' => __('entities.hero_races.singular'),
        'option_label' => 'name',
        'option_value' => 'id'
      ],
      [
        'type' => 'enum',
        'field' => 'gender',
        'label' => __('entities.heroes.gender'),
        'options' => [
          'male' => __('entities.heroes.genders.male'),
          'female' => __('entities.heroes.genders.female')
        ]
      ],
      [
        'type' => 'attribute_range',
        'field' => 'agility',
        'label' => __('entities.heroes.attributes.agility'),
        'options' => [
          '1' => '1',
          '2' => '2',
          '3' => '3',
          '4' => '4',
          '5' => '5+'
        ]
      ],
      [
        'type' => 'attribute_range',
        'field' => 'mental',
        'label' => __('entities.heroes.attributes.mental'),
        'options' => [
          '1' => '1',
          '2' => '2',
          '3' => '3',
          '4' => '4',
          '5' => '5+'
        ]
      ],
      [
        'type' => 'attribute_range',
        'field' => 'will',
        'label' => __('entities.heroes.attributes.will'),
        'options' => [
          '1' => '1',
          '2' => '2',
          '3' => '3',
          '4' => '4',
          '5' => '5+'
        ]
      ],
      [
        'type' => 'attribute_range',
        'field' => 'strength',
        'label' => __('entities.heroes.attributes.strength'),
        'options' => [
          '1' => '1',
          '2' => '2',
          '3' => '3',
          '4' => '4',
          '5' => '5+'
        ]
      ],
      [
        'type' => 'attribute_range',
        'field' => 'armor',
        'label' => __('entities.heroes.attributes.armor'),
        'options' => [
          '1' => '1',
          '2' => '2',
          '3' => '3',
          '4' => '4',
          '5' => '5+'
        ]
      ],
    ];
  }
  
  /**
   * Get fields that can be sorted in admin
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
        'field' => 'is_published',
        'label' => __('admin.publication_status')
      ],
    ];
  }

  /**
   * Get fields that can be sorted in public
   *
   * @return array
   */
  public function getPublicSortable(): array
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
        'field' => 'heroRace.name',
        'label' => __('entities.hero_races.singular')
      ],
      [
        'field' => 'heroClass.name',
        'label' => __('entities.hero_classes.singular')
      ],
      [
        'field' => 'health',
        'label' => __('entities.heroes.attributes.health'),
        'custom_sort' => 'health'
      ],
      [
        'field' => 'total_attributes',
        'label' => __('entities.heroes.total_attributes'),
        'custom_sort' => 'total_attributes'
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

  /**
   * Get the hero class name with proper gender
   * 
   * @return string
   */
  public function getGenderizedClassName(): mixed
  {
    if (!$this->heroClass) {
      return '';
    }

    if (app()->getLocale() !== 'es') {
      return $this->heroClass->name;
    }

    if ($this->gender !== 'female') {
      return $this->heroClass->name;
    }
    
    // Intentamos obtener la traducción con género
    $name = strtolower($this->heroClass->name);
    $translation = __("genderized.hero_classes.{$name}");
    
    // Si no existe traducción con género, devolvemos el nombre original
    if ($translation === "genderized.hero_classes.{$name}") {
      return $this->heroClass->name;
    }
    
    return ucfirst($translation);
  }

  /**
   * Get the hero race name with proper gender
   * 
   * @return string
   */
  public function getGenderizedRaceName(): mixed
  {
    if (!$this->heroRace) {
      return '';
    }

    if (app()->getLocale() !== 'es') {
      return $this->heroRace->name;
    }

    if ($this->gender !== 'female') {
      return $this->heroRace->name;
    }
    
    // Intentamos obtener la traducción con género
    $name = strtolower($this->heroRace->name);
    $translation = __("genderized.hero_races.{$name}");
    
    // Si no existe traducción con género, devolvemos el nombre original
    if ($translation === "genderized.hero_races.{$name}") {
      return $this->heroRace->name;
    }
    
    return ucfirst($translation);
  }

  /**
   * Get the hero superclass name with proper gender
   * 
   * @return string
   */
  public function getGenderizedSuperclassName(): mixed
  {
    if (!$this->heroClass) {
      return '';
    }

    if (app()->getLocale() !== 'es') {
      return $this->heroClass->heroSuperclass->name;
    }

    if ($this->gender !== 'female') {
      return $this->heroClass->heroSuperclass->name;
    }
    
    // Intentamos obtener la traducción con género
    $name = strtolower($this->heroClass->heroSuperclass->name);
    $translation = __("genderized.hero_superclasses.{$name}");
    
    // Si no existe traducción con género, devolvemos el nombre original
    if ($translation === "genderized.hero_superclasses.{$name}") {
      return $this->heroClass->heroSuperclass->name;
    }
    
    return ucfirst($translation);
  }

    /**
   * Get the directory for storing preview images
   * 
   * @return string
   */
  public function getPreviewImageDirectory(): string
  {
    return 'images/previews/heroes';
  }
}