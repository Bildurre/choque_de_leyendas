<?php

namespace App\Http\Controllers\Content\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\ContentPageRequest;
use App\Models\ContentPage;
use App\Services\Content\ContentPageService;

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
  public function store(ContentPageRequest $request)
  {
    $validated = $request->validated();

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
  public function update(ContentPageRequest $request, ContentPage $page)
  {
    $validated = $request->validated();

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