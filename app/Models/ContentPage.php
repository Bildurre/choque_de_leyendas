<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;
use App\Models\Traits\HasImageAttribute;

class ContentPage extends Model
{
  use HasTranslations, HasImageAttribute;
  
  protected $fillable = [
    'title',
    'slug',
    'type',
    'meta_description',
    'background_image',
    'header_config',
    'show_index',
    'is_published'
  ];
  
  public $translatable = [
    'title',
    'meta_description'
  ];
  
  protected $casts = [
    'header_config' => 'array'
  ];
  
  public function sections(): HasMany
  {
    return $this->hasMany(ContentSection::class)->orderBy('order');
  }
  
  public function getIndexSections()
  {
    return $this->sections()
      ->where('include_in_index', true)
      ->with(['blocks' => function($query) {
        $query->where('include_in_index', true);
      }])
      ->get(['id', 'title', 'anchor_id', 'order']);
  }
  
  public function getImageDirectory(): string
  {
    return 'content/pages/backgrounds';
  }
}