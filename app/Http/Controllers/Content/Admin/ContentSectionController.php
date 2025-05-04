<?php

namespace App\Http\Controllers\Content\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\ContentSectionRequest;
use App\Models\ContentPage;
use App\Models\ContentSection;
use App\Services\Content\ContentSectionService;
use Illuminate\Http\Request;

class ContentSectionController extends Controller
{
  protected $contentSectionService;

  /**
   * Create a new controller instance.
   */
  public function __construct(ContentSectionService $contentSectionService)
  {
    $this->contentSectionService = $contentSectionService;
  }

  /**
   * Show the form for creating a new section.
   */
  public function create(ContentPage $page)
  {
    return view('admin.content.sections.create', compact('page'));
  }

  /**
   * Store a newly created section in storage.
   */
  public function store(ContentSectionRequest $request, ContentPage $page)
  {
    $validated = $request->validated();

    try {
      $section = $this->contentSectionService->create($page, $validated);
      return redirect()->route('admin.content.pages.edit', $page)
        ->with('success', 'Sección creada correctamente.');
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al crear la sección: ' . $e->getMessage())->withInput();
    }
  }

  /**
   * Show the form for editing the specified section.
   */
  public function edit(ContentPage $page, ContentSection $section)
  {
    if ($section->content_page_id !== $page->id) {
      abort(404);
    }
    
    return view('admin.content.sections.edit', compact('page', 'section'));
  }

  /**
   * Update the specified section in storage.
   */
  public function update(ContentSectionRequest $request, ContentPage $page, ContentSection $section)
  {
    if ($section->content_page_id !== $page->id) {
      abort(404);
    }
    
    $validated = $request->validated();

    try {
      $this->contentSectionService->update($section, $validated);
      return redirect()->route('admin.content.pages.edit', $page)
        ->with('success', 'Sección actualizada correctamente.');
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al actualizar la sección: ' . $e->getMessage())->withInput();
    }
  }

  /**
   * Remove the specified section from storage.
   */
  public function destroy(ContentPage $page, ContentSection $section)
  {
    if ($section->content_page_id !== $page->id) {
      abort(404);
    }
    
    try {
      $this->contentSectionService->delete($section);
      return redirect()->route('admin.content.pages.edit', $page)
        ->with('success', 'Sección eliminada correctamente.');
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al eliminar la sección: ' . $e->getMessage());
    }
  }

  /**
   * Reorder sections.
   */
  public function reorder(Request $request, ContentPage $page)
  {
    $validated = $request->validate([
      'ids' => 'required|array',
      'ids.*' => 'integer|exists:content_sections,id'
    ]);
    
    try {
      $this->contentSectionService->reorder($page, $validated['ids']);
      return response()->json(['success' => true]);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
  }
}