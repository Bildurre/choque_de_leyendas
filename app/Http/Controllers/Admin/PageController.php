<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageRequest;
use App\Models\Page;
use App\Services\Media\ImageService;
use App\Services\Content\PageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PageController extends Controller
{
  protected $pageService;
  protected $imageService;

  /**
   * Create a new controller instance.
   */
  public function __construct(PageService $pageService, ImageService $imageService)
  {
    $this->pageService = $pageService;
    $this->imageService = $imageService;
  }

  /**
   * Display a listing of pages.
   */
  public function index(): View
  {
    $pages = Page::with('parent')
      ->withCount('children')
      ->orderBy('order')
      ->paginate(20);
    
    return view('admin.pages.index', compact('pages'));
  }

  /**
   * Show the form for creating a new page.
   */
  public function create(): View
  {
    $pages = Page::all()->pluck('title', 'id');
    $templates = $this->pageService->getAvailableTemplates();
    
    return view('admin.pages.create', compact('pages', 'templates'));
  }

  /**
   * Store a newly created page in storage.
   */
  public function store(PageRequest $request): RedirectResponse
  {
    try {
      $this->pageService->create($request->validated());
      return redirect()->route('admin.pages.index')
        ->with('success', 'Page created successfully.');
    } catch (\Exception $e) {
      return back()->with('error', 'Error creating page: ' . $e->getMessage())
        ->withInput();
    }
  }

  /**
   * Show the form for editing the specified page.
   */
  public function edit(Page $page): View
  {
    $pages = Page::where('id', '!=', $page->id)
      ->pluck('title', 'id');
    $templates = $this->pageService->getAvailableTemplates();
    
    return view('admin.pages.edit', compact('page', 'pages', 'templates'));
  }

  /**
   * Update the specified page in storage.
   */
  public function update(PageRequest $request, Page $page): RedirectResponse
  {
    try {
      $this->pageService->update($page, $request->validated());
      return redirect()->route('admin.pages.index')
        ->with('success', 'Page updated successfully.');
    } catch (\Exception $e) {
      return back()->with('error', 'Error updating page: ' . $e->getMessage())
        ->withInput();
    }
  }

  /**
   * Remove the specified page from storage.
   */
  public function destroy(Page $page): RedirectResponse
  {
    try {
      $this->pageService->delete($page);
      return redirect()->route('admin.pages.index')
        ->with('success', 'Page deleted successfully.');
    } catch (\Exception $e) {
      return back()->with('error', 'Error deleting page: ' . $e->getMessage());
    }
  }
}