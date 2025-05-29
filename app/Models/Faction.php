<?php

namespace App\Models;

use Spatie\Sluggable\SlugOptions;
use App\Models\Traits\HasFilters;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasColorAttribute;
use App\Models\Traits\HasImageAttribute;
use Spatie\Translatable\HasTranslations;
use Spatie\Sluggable\HasTranslatableSlug;
use App\Models\Traits\HasPublishedAttribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Mcamara\LaravelLocalization\Interfaces\LocalizedUrlRoutable;

class Faction extends Model implements LocalizedUrlRoutable
{
  use HasFactory;
  use HasTranslations;
  use HasTranslatableSlug;
  use SoftDeletes;
  use HasColorAttribute;
  use HasImageAttribute;
  use HasFilters;
  use HasPublishedAttribute;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'slug',
    'lore_text',
    'color',
    'icon',
    'text_is_dark',
    'is_published',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'text_is_dark' => 'boolean',
    'deleted_at' => 'datetime',
    'is_published' => 'boolean',
  ];

  /**
   * The attributes that are translatable.
   *
   * @var array
   */
  public $translatable = [
    'name',
    'slug',
    'lore_text'
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
    return ['lore_text'];
  }

  /**
   * Get fields that can be filtered in admin
   *
   * @return array
   */
  public function getAdminFilterable(): array
  {
    return [
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
    // Por ahora las facciones no tienen filtros en la parte pública
    // pero podríamos añadir filtros por cantidad de héroes/cartas en el futuro
    return [];
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
        'label' => __('entities.factions.name')
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
        'label' => __('entities.factions.name')
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
    return 'images/factions';
  }

  /**
   * Get the heroes that belong to this faction.
   */
  public function heroes()
  {
    return $this->hasMany(Hero::class);
  }

  /**
   * Get the cards that belong to this faction.
   */
  public function cards()
  {
    return $this->hasMany(Card::class);
  }

  /**
   * Get the faction decks associated with the faction.
   */
  public function factionDecks()
  {
    return $this->hasMany(FactionDeck::class);
  }
}