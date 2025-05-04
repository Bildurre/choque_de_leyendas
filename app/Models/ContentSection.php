<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class ContentSection extends Model
{
  use HasFactory;
  use HasTranslations;

  protected $fillable = [
    'content_page_id',
    'title',
    'anchor_id',
    'order',
    'include_in_index',
    'background_color'
  ];

  public $translatable = [
    'title'
  ];

  /**
   * Get the page that owns the section
   */
  public function page(): BelongsTo
  {
    return $this->belongsTo(ContentPage::class, 'content_page_id');
  }

  /**
   * Get the blocks for this section
   */
  public function blocks(): HasMany
  {
    return $this->hasMany(ContentBlock::class)->orderBy('order');
  }
  
  /**
   * Get a cleaned version of the anchor ID
   */
  public function getCleanAnchorIdAttribute(): string
  {
    if ($this->anchor_id) {
      return $this->anchor_id;
    }
    
    // Generate an anchor ID from the title
    return str_slug($this->getTranslation('title', app()->getLocale()));
  }
}