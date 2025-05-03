<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class ContentPage extends Model
{
  use HasTranslations;
  
  protected $fillable = [
    'title',
    'slug',
    'type',
    'meta_description',
    'show_index',
    'order',
    'is_published'
  ];
  
  public $translatable = [
    'title',
    'meta_description'
  ];
  
  public function sections(): HasMany
  {
    return $this->hasMany(ContentSection::class)->orderBy('order');
  }
  
  // Helper to get first-level index
  public function getIndexSections()
  {
    return $this->sections()
      ->where('include_in_index', true)
      ->get(['id', 'title', 'anchor_id']);
  }
}