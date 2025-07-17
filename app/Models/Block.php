<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Services\Content\BlockDataService;
use App\Models\Traits\HasMultilingualImageAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Block extends Model
{
  use HasFactory, HasTranslations, HasMultilingualImageAttribute;

  /**
   * The attributes that are mass assignable.
   */
  protected $fillable = [
    'page_id',
    'parent_id',
    'type',
    'title',
    'subtitle',
    'content',
    'order',
    'is_printable',
    'is_indexable',
    'background_color',
    'image',
    'settings',
  ];

  /**
   * The attributes that should be cast.
   */
  protected $casts = [
    'settings' => 'array',
    'image' => 'array',
    'order' => 'integer',
    'is_printable' => 'boolean',
    'content' => 'array'
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
   * Get the parent block of the block.
   */
  public function parent()
  {
      return $this->belongsTo(Block::class, 'parent_id');
  }

  /**
   * Get the children blocks of the block.
   */
  public function children()
  {
      return $this->hasMany(Block::class, 'parent_id')->orderBy('order');
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
   * Get the field name for storing multilingual images for this model
   * 
   * @return string
   */
  public function getMultilingualImageField(): string
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
    
    // Get block-specific data
    $blockDataService = app(BlockDataService::class);
    $data = $blockDataService->getBlockData($this);
    
    // Merge block and additional data
    $viewData = array_merge(['block' => $this], $data);
    
    return view($view, $viewData)->render();
  }
  
  /**
   * Scope a query to only include printable blocks.
   */
  public function scopePrintable($query)
  {
    return $query->where('is_printable', true);
  }

  /**
   * Scope a query to only include printable blocks.
   */
  public function scopeIndexable($query)
  {
    return $query->where('is_indexable', true);
  }

  /**
   * Get the display label for the block
   *
   * @return string
   */
  public function getDisplayLabelAttribute(): string
  {
    // Priority: title > subtitle > content
    if (!empty($this->title)) {
      return $this->cleanHtml($this->title);
    }
      
    if (!empty($this->subtitle)) {
      return $this->cleanHtml($this->subtitle);
    }
      
    // Handle content
    if (!empty($this->content)) {
      // If content is an array with 'text' key
      if (is_array($this->content) && isset($this->content['text'])) {
        return $this->extractFirstTagContent($this->content['text']);
      }
      
      // If content is a string
      if (is_string($this->content)) {
        return $this->extractFirstTagContent($this->content);
      }
      
      // If content is an array with other structure, check for first string value
      if (is_array($this->content)) {
        foreach ($this->content as $key => $value) {
          if (is_string($value) && !empty($value)) {
            return $this->extractFirstTagContent($value);
          }
        }
      }
    }
      
    // Fallback
    return __('pages.blocks.types.' . $this->type);
  }

  /**
   * Extract content from the first HTML tag found
   *
   * @param string $html
   * @return string
   */
  private function extractFirstTagContent(string $html): string
  {
    // First, try to extract content from the first HTML tag
    // Match opening tag and its content until closing tag
    $pattern = '/<(\w+)(?:\s[^>]*)?>(.+?)<\/\1>/s';
    
    if (preg_match($pattern, $html, $matches)) {
      // Extract the content inside the first tag
      $content = $matches[2];
      
      // Clean any nested HTML tags
      $content = strip_tags($content);
      
      // Decode HTML entities
      $content = html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
      
      // Remove extra whitespace
      $content = trim(preg_replace('/\s+/', ' ', $content));
      
      // Truncate if needed
      return $this->truncateText($content);
    }
    
    // If no tags found, fall back to cleaning the whole content
    return $this->truncateText($html);
  }
  
  /**
   * Clean HTML tags and entities from text
   *
   * @param string $text
   * @return string
   */
  private function cleanHtml(string $text): string
  {
    // Remove HTML tags
    $text = strip_tags($text);
    
    // Decode HTML entities
    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    
    // Remove extra whitespace
    $text = trim(preg_replace('/\s+/', ' ', $text));
    
    return $text;
  }

  /**
   * Truncate text for display in select
   *
   * @param string $text
   * @param int $length
   * @return string
   */
  private function truncateText(string $text, int $length = 60): string
  {
    // Clean HTML first
    $text = $this->cleanHtml($text);
    
    // If text is shorter than or equal to length, return as is
    if (strlen($text) <= $length) {
      return $text;
    }
    
    // Find the last space before the length limit
    $truncated = substr($text, 0, $length);
    $lastSpace = strrpos($truncated, ' ');
    
    // If no space found, extend to find the next word boundary
    if ($lastSpace === false) {
      $position = $length;
      while ($position < strlen($text) && $text[$position] !== ' ') {
        $position++;
      }
      $truncated = substr($text, 0, $position);
    } else {
      // Cut at the last space found
      $truncated = substr($text, 0, $lastSpace);
    }
    
    return $truncated . '...';
  }
  
  /**
   * Scope to get blocks with their display labels
   *
   * @param \Illuminate\Database\Eloquent\Builder $query
   * @param int $pageId
   * @return \Illuminate\Support\Collection
   */
  public function scopeForSelectOptions($query, int $pageId, ?int $excludeId = null)
  {
    $query->where('page_id', $pageId);
    
    if ($excludeId) {
      $query->where('id', '!=', $excludeId);
    }
    
    return $query->orderBy('order')->get()->pluck('display_label', 'id');
  }
}