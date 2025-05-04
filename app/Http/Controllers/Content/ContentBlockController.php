<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\ContentBlock;
use App\Models\ContentPage;
use App\Models\ContentSection;
use App\Services\Content\ContentBlockService;
use Illuminate\Http\Request;

class ContentBlockController extends Controller
{
  protected $contentBlockService;

  /**
   * Create a new controller instance.
   */
  public function __construct(ContentBlockService $contentBlockService)
  {
    $this->contentBlockService = $contentBlockService;
  }

  /**
   * Display a specific content block.
   */
  public function show(string $pageSlug, string $sectionAnchor, int $blockId)
  {
    $page = ContentPage::where('slug', $pageSlug)
      ->where('is_published', true)
      ->first();
    
    if (!$page) {
      abort(404);
    }
    
    $section = ContentSection::where('content_page_id', $page->id)
      ->where('anchor_id', $sectionAnchor)
      ->first();
    
    if (!$section) {
      abort(404);
    }
    
    $block = ContentBlock::where('content_section_id', $section->id)
      ->where('id', $blockId)
      ->first();
    
    if (!$block) {
      abort(404);
    }
    
    return view('content.block', compact('page', 'section', 'block'));
  }

  /**
   * Get filtered model list data for model_list block type.
   */
  public function getModelListData(Request $request, int $blockId)
  {
    $block = ContentBlock::find($blockId);
    
    if (!$block || $block->type !== 'model_list') {
      return response()->json(['error' => 'Invalid block'], 404);
    }
    
    // Validate that the block's section's page is published
    $section = $block->section;
    $page = $section->page;
    
    if (!$page->is_published) {
      return response()->json(['error' => 'Page not published'], 403);
    }
    
    $modelType = $block->model_type;
    $filters = $block->model_filters ?? [];
    
    // Apply any additional filters from the request
    $requestFilters = $request->input('filters', []);
    $mergedFilters = array_merge($filters, $requestFilters);
    
    // Get the model data based on the model type and filters
    $data = $this->getModelData($modelType, $mergedFilters);
    
    return response()->json($data);
  }

  /**
   * Get model data based on model type and filters.
   */
  private function getModelData(string $modelType, array $filters)
  {
    // Default response
    $data = [
      'items' => [],
      'total' => 0
    ];
    
    // Get the model based on the model type
    switch ($modelType) {
      case 'heroes':
        $query = \App\Models\Hero::with(['faction', 'race', 'heroClass']);
        break;
      
      case 'cards':
        $query = \App\Models\Card::with(['faction', 'cardType', 'equipmentType']);
        break;
      
      case 'factions':
        $query = \App\Models\Faction::withCount(['heroes', 'cards']);
        break;
      
      case 'hero_classes':
        $query = \App\Models\HeroClass::with('heroSuperclass');
        break;
      
      case 'hero_races':
        $query = \App\Models\HeroRace::withCount('heroes');
        break;
      
      default:
        return $data;
    }
    
    // Apply filters
    foreach ($filters as $key => $value) {
      if ($value !== null && $value !== '') {
        // Handle special filters
        if ($key === 'search' && !empty($value)) {
          $query->where('name', 'like', "%{$value}%");
        } elseif (is_array($value)) {
          $query->whereIn($key, $value);
        } else {
          $query->where($key, $value);
        }
      }
    }
    
    // Get paginated results
    $perPage = $filters['per_page'] ?? 10;
    $page = $filters['page'] ?? 1;
    $results = $query->paginate($perPage, ['*'], 'page', $page);
    
    $data['items'] = $results->items();
    $data['total'] = $results->total();
    $data['per_page'] = $results->perPage();
    $data['current_page'] = $results->currentPage();
    $data['last_page'] = $results->lastPage();
    
    return $data;
  }
}