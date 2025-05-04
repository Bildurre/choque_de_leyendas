<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class ContentBlock extends Model
{
  use HasFactory;
  use HasTranslations;

  protected $fillable = [
    'content_section_id',
    'type',
    'content',
    'image',
    'image_position',
    'order',
    'include_in_index',
    'model_type',
    'model_filters',
    'style_settings'
  ];

  public $translatable = [
    'content'
  ];

  protected $casts = [
    'model_filters' => 'array',
    'style_settings' => 'array'
  ];

  /**
   * Get the section that owns the block
   */
  public function section(): BelongsTo
  {
    return $this->belongsTo(ContentSection::class, 'content_section_id');
  }

  /**
   * Get block types 
   */
  public static function getTypes(): array
  {
    return [
      'text' => 'Text Block',
      'title' => 'Title Block',
      'subtitle' => 'Subtitle Block',
      'header' => 'Header Block',
      'image' => 'Image Block',
      'gallery' => 'Gallery Block',
      'model_list' => 'Model List Block',
      'cta' => 'Call to Action Block',
      'index' => 'Index Block'
    ];
  }

  /**
   * Get available model types for model_list blocks
   */
  public static function getModelTypes(): array
  {
    return [
      'heroes' => 'Heroes',
      'cards' => 'Cards',
      'factions' => 'Factions',
      'hero_classes' => 'Hero Classes',
      'hero_races' => 'Hero Races'
    ];
  }

  /**
   * Get title for indexing
   */
  public function getIndexTitle(): ?string
  {
    if ($this->type === 'title' || $this->type === 'subtitle' || $this->type === 'header') {
      return $this->getTranslation('content', app()->getLocale());
    }
    
    return null;
  }
}