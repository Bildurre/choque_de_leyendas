<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $blockTypes = $this->blockService->getBlockTypes();
        
        if (!isset($blockTypes[$type])) {
            abort(404, 'Block type not found');
        }
        
        $blockConfig = $blockTypes[$type];
        $allowsImage = $blockConfig['allows_image'] ?? true;
        
        return view('admin.blocks.create', compact('page', 'type', 'blockConfig', 'allowsImage'));
    }

    /**
     * Store a newly created block.
     */
    public function store(Request $request, Page $page): RedirectResponse
    {
        $type = $request->input('type');
        $blockTypes = $this->blockService->getBlockTypes();
        
        if (!isset($blockTypes[$type])) {
            abort(404, 'Block type not found');
        }
        
        $data = $request->all();
        $data['page_id'] = $page->id;
        
        // Get the highest order value for this page and increment it
        $maxOrder = Block::where('page_id', $page->id)->max('order');
        $data['order'] = is_null($maxOrder) ? 0 : $maxOrder + 1;
        
        try {
            $this->blockService->create($data);
            
            return redirect()->route('admin.pages.edit', $page)
                ->with('success', __('blocks.created_successfully'));
        } catch (\Exception $e) {
            return back()->with('error', __('blocks.create_error', ['message' => $e->getMessage()]))
                ->withInput();
        }
    }

    /**
     * Show the form for editing a block.
     */
    public function edit(Page $page, Block $block): View
    {
        $blockTypes = $this->blockService->getBlockTypes();
        
        if (!isset($blockTypes[$block->type])) {
            abort(404, 'Block type not found');
        }
        
        $blockConfig = $blockTypes[$block->type];
        $allowsImage = $blockConfig['allows_image'] ?? true;
        
        return view('admin.blocks.edit', compact('page', 'block', 'blockConfig', 'allowsImage'));
    }

    /**
     * Update the specified block.
     */
    public function update(Request $request, Page $page, Block $block): RedirectResponse
    {
        try {
            $this->blockService->update($block, $request->all());
            
            return redirect()->route('admin.pages.edit', $page)
                ->with('success', __('blocks.updated_successfully'));
        } catch (\Exception $e) {
            return back()->with('error', __('blocks.update_error', ['message' => $e->getMessage()]))
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
            return back()->with('error', __('blocks.delete_error', ['message' => $e->getMessage()]));
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
            return back()->with('error', __('blocks.reorder_error', ['message' => $e->getMessage()]));
        }
    }
}