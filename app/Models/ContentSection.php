<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class ContentSection extends Model
{
  use HasTranslations;
  
  protected $fillable = [
    'content_page_id',
    'title',
    'anchor_id',
    'order',
    'include_in_index',
    'settings'
  ];
  
  public $translatable = [
    'title'
  ];
  
  protected $casts = [
    'settings' => 'array'
  ];
  
  public function page(): BelongsTo
  {
    return $this->belongsTo(ContentPage::class, 'content_page_id');
  }
  
  public function blocks(): HasMany
  {
    return $this->hasMany(ContentBlock::class)->orderBy('order');
  }
}