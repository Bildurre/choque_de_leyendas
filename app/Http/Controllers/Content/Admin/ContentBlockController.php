<?php

namespace App\Http\Controllers\Content\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\ContentBlockRequest;
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
   * Show the form for creating a new block.
   */
  public function create(ContentPage $page, ContentSection $section)
  {
    if ($section->content_page_id !== $page->id) {
      abort(404);
    }
    
    $blockTypes = ContentBlock::getTypes();
    $modelTypes = ContentBlock::getModelTypes();
    
    return view('admin.content.blocks.create', compact('page', 'section', 'blockTypes', 'modelTypes'));
  }

  /**
   * Store a newly created block in storage.
   */
  public function store(ContentBlockRequest $request, ContentPage $page, ContentSection $section)
  {
    if ($section->content_page_id !== $page->id) {
      abort(404);
    }
    
    $validated = $request->validated();

    try {
      $block = $this->contentBlockService->create($section, $validated);
      return redirect()->route('admin.content.pages.edit', $page)
        ->with('success', 'Bloque creado correctamente.');
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al crear el bloque: ' . $e->getMessage())->withInput();
    }
  }

  /**
   * Show the form for editing the specified block.
   */
  public function edit(ContentPage $page, ContentSection $section, ContentBlock $block)
  {
    if ($section->content_page_id !== $page->id || $block->content_section_id !== $section->id) {
      abort(404);
    }
    
    $blockTypes = ContentBlock::getTypes();
    $modelTypes = ContentBlock::getModelTypes();
    
    return view('admin.content.blocks.edit', compact('page', 'section', 'block', 'blockTypes', 'modelTypes'));
  }

  /**
   * Update the specified block in storage.
   */
  public function update(ContentBlockRequest $request, ContentPage $page, ContentSection $section, ContentBlock $block)
  {
    if ($section->content_page_id !== $page->id || $block->content_section_id !== $section->id) {
      abort(404);
    }
    
    $validated = $request->validated();

    try {
      $this->contentBlockService->update($block, $validated);
      return redirect()->route('admin.content.pages.edit', $page)
        ->with('success', 'Bloque actualizado correctamente.');
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al actualizar el bloque: ' . $e->getMessage())->withInput();
    }
  }

  /**
   * Remove the specified block from storage.
   */
  public function destroy(ContentPage $page, ContentSection $section, ContentBlock $block)
  {
    if ($section->content_page_id !== $page->id || $block->content_section_id !== $section->id) {
      abort(404);
    }
    
    try {
      $this->contentBlockService->delete($block);
      return redirect()->route('admin.content.pages.edit', $page)
        ->with('success', 'Bloque eliminado correctamente.');
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al eliminar el bloque: ' . $e->getMessage());
    }
  }

  /**
   * Reorder blocks.
   */
  public function reorder(Request $request, ContentPage $page, ContentSection $section)
  {
    if ($section->content_page_id !== $page->id) {
      abort(404);
    }
    
    $validated = $request->validate([
      'ids' => 'required|array',
      'ids.*' => 'integer|exists:content_blocks,id'
    ]);
    
    try {
      $this->contentBlockService->reorder($section, $validated['ids']);
      return response()->json(['success' => true]);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
  }
}