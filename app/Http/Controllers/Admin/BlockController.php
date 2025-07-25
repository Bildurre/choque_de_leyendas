<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlockRequest;
use App\Models\Block;
use App\Models\Page;
use App\Services\Content\BlockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlockController extends Controller
{
  protected $blockService;

  /**
   * Create a new controller instance.
   */
  public function __construct(BlockService $blockService)
  {
    $this->blockService = $blockService;
  }

  /**
   * Show the form for creating a new block.
   */
  public function create(Page $page, string $type): View
  {
    $blocks = Block::forSelectOptions($page->id);
    $blockTypes = $this->blockService->getBlockTypes();
    
    if (!isset($blockTypes[$type])) {
      abort(404, 'Block type not found');
    }
    
    $blockConfig = $blockTypes[$type];
    $allowsImage = $blockConfig['allows_image'] ?? true;
    
    return view('admin.blocks.create', compact('page', 'blocks', 'type', 'blockConfig', 'allowsImage'));
  }

  /**
   * Store a newly created block.
   */
  public function store(BlockRequest $request, Page $page): RedirectResponse
  {
    $type = $request->input('type');
    $blockTypes = $this->blockService->getBlockTypes();
    
    if (!isset($blockTypes[$type])) {
      abort(404, 'Block type not found');
    }
    
    // Los datos ya están validados por BlockRequest
    $data = $request->validated();
    $data['page_id'] = $page->id;
    
    // Get the highest order value for this page and increment it
    $maxOrder = Block::where('page_id', $page->id)->max('order');
    $data['order'] = is_null($maxOrder) ? 0 : $maxOrder + 1;
    
    try {
      $this->blockService->create($data);
      
      return redirect()->route('admin.pages.edit', $page)
        ->with('success', __('pages.blocks.created_successfully'));
    } catch (\Exception $e) {
      return back()->with('error', __('pages.blocks.create_error', ['message' => $e->getMessage()]))
        ->withInput();
    }
  }

  /**
   * Show the form for editing a block.
   */
  public function edit(Page $page, Block $block): View
  {
    $blocks = Block::forSelectOptions($page->id, $block->id);
    $blockTypes = $this->blockService->getBlockTypes();
    
    if (!isset($blockTypes[$block->type])) {
      abort(404, 'Block type not found');
    }
    
    $blockConfig = $blockTypes[$block->type];
    $allowsImage = $blockConfig['allows_image'] ?? true;
    
    return view('admin.blocks.edit', compact('page', 'block', 'blocks', 'blockConfig', 'allowsImage'));
  }

  /**
   * Update the specified block.
   */
  public function update(BlockRequest $request, Page $page, Block $block): RedirectResponse
  {
    try {
      // Use validated data from BlockRequest
      $data = $request->validated();

      $this->blockService->update($block, $data);
      
      return redirect()->route('admin.pages.blocks.edit', [$page, $block])
        ->with('success', __('blocks.updated_successfully'));
    } catch (\Exception $e) {
      return back()->with('error', __('blocks.update_error'))
        ->withInput();
    }
  }

  /**
   * Remove the specified block.
   */
  public function destroy(Page $page, Block $block): RedirectResponse
  {
    try {
      $this->blockService->delete($block);
      
      return redirect()->route('admin.pages.edit', $page)
        ->with('success', __('blocks.deleted_successfully'));
    } catch (\Exception $e) {
      return back()->with('error', __('blocks.delete_error'));
    }
  }

  /**
   * Reorder blocks for a page.
   */
  public function reorder(Request $request, Page $page): RedirectResponse
  {
    try {
      $blockIds = $request->input('item_ids', []);
      
      // Si es una cadena JSON, decodificarla
      if (is_string($blockIds)) {
        $blockIds = json_decode($blockIds, true);
      }
      
      if (empty($blockIds)) {
        return redirect()->route('admin.pages.edit', $page)
          ->with('warning', __('blocks.no_blocks_to_reorder'));
      }
      
      $this->blockService->reorderBlocks($page->id, $blockIds);
      
      return redirect()->route('admin.pages.edit', $page)
        ->with('success', __('blocks.reordered_successfully'));
    } catch (\Exception $e) {
      return back()->with('error', __('blocks.reorder_error'));
    }
  }
}