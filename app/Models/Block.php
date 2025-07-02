<?php

namespace App\Models;

use App\Models\Traits\HasImageAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Block extends Model
{
  use HasFactory, HasTranslations, HasImageAttribute;

  /**
   * The attributes that are mass assignable.
   */
  protected $fillable = [
    'page_id',
    'type',
    'title',
    'subtitle',
    'content',
    'order',
    'is_printable',
    'background_color',
    'image',
    'settings',
  ];

  /**
   * The attributes that should be cast.
   */
  protected $casts = [
    'settings' => 'array',
    'order' => 'integer',
    'is_printable' => 'boolean',
  ];

  /**
   * The attributes that are translatable.
   */
  public $translatable = [
    'title',
    'subtitle',
    'content'
  ];

  /**
   * Get the page that owns the block.
   */
  public function page()
  {
    return $this->belongsTo(Page::class);
  }

  /**
   * Get the block type configuration.
   */
  public function getTypeConfig()
  {
    $types = config('blocks.types', []);
    return $types[$this->type] ?? null;
  }

  /**
   * Get the directory for storing images for this model
   */
  public function getImageDirectory(): string
  {
    return 'images/blocks';
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
   * Render the block using the appropriate view
   */
  public function render()
  {
    $config = $this->getTypeConfig();
    
    if (!$config || !isset($config['view'])) {
      return null;
    }
    
    $view = $config['view'];
    
    return view($view, ['block' => $this]);
  }
  
  /**
   * Scope a query to only include printable blocks.
   */
  public function scopePrintable($query)
  {
    return $query->where('is_printable', true);
  }
}