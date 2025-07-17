<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageRequest;
use App\Models\Page;
use App\Services\Content\PageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    protected $pageService;

    /**
     * Create a new controller instance.
     */
    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }

    /**
     * Display a listing of pages.
     */
    public function index(Request $request): View
    {
        $trashed = $request->has('trashed');
        
        // Obtener contadores para las pestañas directamente con Eloquent
        $activeCount = Page::count();
        $trashedCount = Page::onlyTrashed()->count();
        
        $query = Page::with('parent')
            ->withCount('children');
        
        if ($trashed) {
            $query->onlyTrashed();
        }
        
        $pages = $query->orderBy('order')->paginate(20);
        
        // Obtener todas las páginas publicadas para el selector
        $availablePages = Page::where('is_published', true)
            ->orderBy('title')
            ->pluck('title', 'id');
        
        // Obtener la página actual establecida como home
        $currentHomePage = Page::where('is_home', true)->first();
        
        return view('admin.pages.index', compact(
            'pages', 
            'trashed', 
            'activeCount', 
            'trashedCount',
            'availablePages',
            'currentHomePage'
        ));
    }

    /**
     * Set a page as the home page
     */
    public function setHome(Request $request): RedirectResponse
    {
        $request->validate([
            'page_id' => 'required|exists:pages,id'
        ]);
        
        try {
            $this->pageService->setAsHome($request->page_id);
            
            $page = Page::find($request->page_id);
            
            return redirect()->route('admin.pages.index')
                ->with('success', __('pages.set_as_home_success', ['title' => $page->title]));
        } catch (\Exception $e) {
            return back()->with('error', __('pages.set_as_home_error'));
        }
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
        $page = $this->pageService->create($request->validated());
        
        return redirect()->route('admin.pages.edit', $page)
          ->with('success', __('pages.created_successfully', ['title' => $page->title]));
      } catch (\Exception $e) {
        return back()->with('error', __('pages.create_error'))
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
        $blocks = $page->blocks()->with('parent')->get();
        
        return view('admin.pages.edit', compact('page', 'pages', 'blocks', 'templates'));
    }

    /**
     * Update the specified page in storage.
     */
    public function update(PageRequest $request, Page $page): RedirectResponse
    {
        try {
            $this->pageService->update($page, $request->validated());
            
            return redirect()->route('admin.pages.index')
                ->with('success', __('pages.updated_successfully', ['title' => $page->title]));
        } catch (\Exception $e) {
            return back()->with('error', __('pages.update_error'))
                ->withInput();
        }
    }

    /**
     * Remove the specified page from storage.
     */
    public function destroy(Page $page): RedirectResponse
    {
        try {
            $title = $page->title;
            $this->pageService->delete($page);
            
            return redirect()->route('admin.pages.index')
                ->with('success', __('pages.deleted_successfully', ['title' => $title]));
        } catch (\Exception $e) {
            return back()->with('error', __('pages.delete_error'));
        }
    }
    
    /**
     * Restore the specified page from trash.
     */
    public function restore($id): RedirectResponse
    {
      try {
        $page = $this->pageService->restore($id);
        
        return redirect()->route('admin.pages.index', ['trashed' => 1])
          ->with('success', __('pages.restored_successfully', ['title' => $page->title]));
      } catch (\Exception $e) {
        return back()->with('error', __('pages.restore_error'));
      }
    }
    
    /**
     * Force delete the specified page.
     */
    public function forceDelete($id): RedirectResponse
    {
      try {
        $title = $this->pageService->forceDelete($id);
        
        return redirect()->route('admin.pages.index', ['trashed' => 1])
          ->with('success', __('pages.force_deleted_successfully', ['title' => $title]));
      } catch (\Exception $e) {
        return back()->with('error', __('pages.force_delete_error'));
      }
    }

    /**
     * Toggle the published status of the specified page.
     */
    public function togglePublished(Page $page)
    {
      $page->togglePublished();

      $statusMessage = $page->isPublished() 
        ? __('page.published_successfully', ['name' => $page->title])
        : __('page.unpublished_successfully', ['name' => $page->title]);

      return back()->with('success', $statusMessage);
    }
}