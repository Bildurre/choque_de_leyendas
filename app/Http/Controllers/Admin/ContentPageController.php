<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentPage;
use App\Services\Content\ContentPageService;
use Illuminate\Http\Request;

class ContentPageController extends Controller
{
  protected $contentPageService;

  /**
   * Create a new controller instance.
   */
  public function __construct(ContentPageService $contentPageService)
  {
    $this->contentPageService = $contentPageService;
  }

  /**
   * Display a listing of the pages.
   */
  public function index()
  {
    $pages = $this->contentPageService->getAllPages();
    return view('admin.content.pages.index', compact('pages'));
  }

  /**
   * Show the form for creating a new page.
   */
  public function create()
  {
    $pages = $this->contentPageService->getAllPages();
    return view('admin.content.pages.create', compact('pages'));
  }

  /**
   * Store a newly created page in storage.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'title' => 'required|array',
      'title.es' => 'required|string|max:255',
      'slug' => 'nullable|string|max:255|unique:content_pages,slug',
      'meta_description' => 'nullable|array',
      'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
      'is_published' => 'boolean',
      'show_in_menu' => 'boolean',
      'order' => 'integer',
      'parent_slug' => 'nullable|string|exists:content_pages,slug',
    ]);

    try {
      $page = $this->contentPageService->create($validated);
      return redirect()->route('admin.content.pages.edit', $page)
        ->with('success', 'Página creada correctamente.');
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al crear la página: ' . $e->getMessage())->withInput();
    }
  }

  /**
   * Show the form for editing the specified page.
   */
  public function edit(ContentPage $page)
  {
    $pages = $this->contentPageService->getAllPages()->reject(function ($item) use ($page) {
      return $item->id === $page->id;
    });
    
    return view('admin.content.pages.edit', compact('page', 'pages'));
  }

  /**
   * Update the specified page in storage.
   */
  public function update(Request $request, ContentPage $page)
  {
    $validated = $request->validate([
      'title' => 'required|array',
      'title.es' => 'required|string|max:255',
      'slug' => 'nullable|string|max:255|unique:content_pages,slug,' . $page->id,
      'meta_description' => 'nullable|array',
      'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
      'remove_background_image' => 'nullable|in:0,1',
      'is_published' => 'boolean',
      'show_in_menu' => 'boolean',
      'order' => 'integer',
      'parent_slug' => 'nullable|string|exists:content_pages,slug',
    ]);

    try {
      $this->contentPageService->update($page, $validated);
      return redirect()->route('admin.content.pages.edit', $page)
        ->with('success', 'Página actualizada correctamente.');
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al actualizar la página: ' . $e->getMessage())->withInput();
    }
  }

  /**
   * Remove the specified page from storage.
   */
  public function destroy(ContentPage $page)
  {
    try {
      $this->contentPageService->delete($page);
      return redirect()->route('admin.content.pages.index')
        ->with('success', 'Página eliminada correctamente.');
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al eliminar la página: ' . $e->getMessage());
    }
  }
}