<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;
use App\Models\Traits\HasImageAttribute;

class ContentBlock extends Model
{
  use HasTranslations, HasImageAttribute;
  
  protected $fillable = [
    'content_section_id',
    'type',
    'content',
    'image',
    'metadata',
    'model_type',
    'filters',
    'settings',
    'order',
    'include_in_index'
  ];
  
  public $translatable = [
    'content',
    'metadata' // Para títulos/subtítulos traducibles
  ];
  
  protected $casts = [
    'metadata' => 'array',
    'filters' => 'array',
    'settings' => 'array'
  ];
  
  public function section(): BelongsTo
  {
    return $this->belongsTo(ContentSection::class, 'content_section_id');
  }
  
  public function getImageDirectory(): string
  {
    return 'content/blocks';
  }

  // Método para renderizar el bloque según su tipo
  public function render()
  {
    $viewPath = 'components.content.blocks.' . str_replace('_', '-', $this->type);
    
    if (view()->exists($viewPath)) {
      return view($viewPath, [
        'block' => $this,
        'models' => $this->getModels()
      ]);
    }
    
    // Fallback a bloque de texto por defecto
    return view('components.content.blocks.text', ['block' => $this]);
  }

  protected function getModels()
  {
    if ($this->type !== 'model_list' || !$this->model_type) {
      return collect();
    }
    
    $modelClass = match($this->model_type) {
      'heroes' => Hero::class,
      'cards' => Card::class,
      'factions' => Faction::class,
      'hero_abilities' => HeroAbility::class,
      'attack_ranges' => AttackRange::class,
      'attack_subtypes' => AttackSubtype::class,
      default => null
    };
    
    if (!$modelClass) {
      return collect();
    }
    
    $query = $modelClass::query();
    
    // Aplicar filtros
    foreach ($this->filters ?? [] as $key => $value) {
      if (!empty($value)) {
        $query->where($key, $value);
      }
    }
    
    return $query->get();
  }

  // Obtener configuración del bloque
  public function getSetting($key, $default = null)
  {
    return data_get($this->settings, $key, $default);
  }

  // Obtener metadata traducida
  public function getMetadata($key, $locale = null)
  {
    $metadata = $this->getTranslation('metadata', $locale ?? app()->getLocale());
    return data_get($metadata, $key);
  }
}