<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;
use App\Models\Traits\HasImageAttribute;

class ContentBlock extends Model
{
  use HasTranslations;
  use HasImageAttribute;
  
  protected $fillable = [
    'content_section_id',
    'type',
    'content',
    'image',
    'model_type',
    'filters',
    'settings',
    'order'
  ];
  
  public $translatable = [
    'content'
  ];
  
  protected $casts = [
    'filters' => 'array',
    'settings' => 'array'
  ];
  
  public function section(): BelongsTo
  {
    return $this->belongsTo(ContentSection::class, 'content_section_id');
  }
  
  /**
   * Get the directory for storing images for this model
   */
  public function getImageDirectory(): string
  {
    return 'content/blocks';
  }

  /**
   * Render this block based on its type
   */
  public function render()
  {
    switch ($this->type) {
      case 'text':
        return view('components.content.text-block', ['block' => $this]);
      case 'image':
        return view('components.content.image-block', ['block' => $this]);
      case 'text_image':
        return view('components.content.text-image-block', ['block' => $this]);
      case 'list':
        return view('components.content.list-block', ['block' => $this]);
      case 'table':
        return view('components.content.table-block', ['block' => $this]);
      case 'model_list':
        return $this->renderModelList();
      default:
        return view('components.content.text-block', ['block' => $this]);
    }
  }

  /**
   * Render a model list block with the appropriate model data
   */
  protected function renderModelList()
  {
    $modelType = $this->model_type;
    $filters = $this->filters ?? [];
    
    // Get the appropriate model class and query based on model_type
    switch ($modelType) {
      case 'heroes':
        $models = Hero::query();
        break;
      case 'cards':
        $models = Card::query();
        break;
      case 'factions':
        $models = Faction::query();
        break;
      default:
        $models = collect([]);
    }
    
    // Apply filters if models is a query
    if (method_exists($models, 'where')) {
      foreach ($filters as $key => $value) {
        if (!empty($value)) {
          $models->where($key, $value);
        }
      }
      $models = $models->get();
    }
    
    return view('components.content.model-list-block', [
      'block' => $this,
      'models' => $models,
      'modelType' => $modelType
    ]);
  }
}