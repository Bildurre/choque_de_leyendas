<?php

namespace App\Http\Controllers\Content;

use Illuminate\View\View;
use App\Models\ContentPage;
use App\Http\Controllers\Controller;
use App\Services\ContentPageService;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ContentPageRequest;

class ContentPageController extends Controller
{
  protected $service;

  /**
   * Create a new controller instance.
   *
   * @param ContentPageService $service
   */
  public function __construct(ContentPageService $service)
  {
    $this->service = $service;
    
    // Apply admin middleware only to admin routes
    $this->middleware(['auth', 'admin'])->only([
      'index', 'create', 'store', 'edit', 'update', 'destroy'
    ]);
  }

  /**
   * Display a listing of the content pages (admin).
   *
   * @return View
   */
  public function index(): View
  {
    $pages = ContentPage::with('sections')
      ->orderBy('created_at', 'desc')
      ->paginate(10);
      
    return view('admin.content-pages.index', compact('pages'));
  }

  /**
   * Show the form for creating a new content page (admin).
   *
   * @return View
   */
  public function create(): View
  {
    return view('admin.content-pages.create');
  }

  /**
   * Store a newly created content page in storage (admin).
   *
   * @param ContentPageRequest $request
   * @return RedirectResponse
   */
  public function store(ContentPageRequest $request): RedirectResponse
  {
    $page = $this->service->create($request->validated());
    
    return redirect()
      ->route('admin.content-pages.edit', $page)
      ->with('success', 'Página creada exitosamente.');
  }

  /**
   * Show the form for editing the specified content page (admin).
   *
   * @param ContentPage $contentPage
   * @return View
   */
  public function edit(ContentPage $contentPage): View
  {
    $page = $contentPage->load(['sections.blocks']);
    
    return view('admin.content-pages.edit', compact('page'));
  }

  /**
   * Update the specified content page in storage (admin).
   *
   * @param ContentPageRequest $request
   * @param ContentPage $contentPage
   * @return RedirectResponse
   */
  public function update(ContentPageRequest $request, ContentPage $contentPage): RedirectResponse
  {
    $page = $this->service->update($contentPage, $request->validated());
    
    return redirect()
      ->route('admin.content-pages.edit', $page)
      ->with('success', 'Página actualizada exitosamente.');
  }

  /**
   * Remove the specified content page from storage (admin).
   *
   * @param ContentPage $contentPage
   * @return RedirectResponse
   */
  public function destroy(ContentPage $contentPage): RedirectResponse
  {
    $this->service->delete($contentPage);
    
    return redirect()
      ->route('admin.content-pages.index')
      ->with('success', 'Página eliminada exitosamente.');
  }

  /**
   * Display the specified content page by slug (public).
   *
   * @param string $slug
   * @return View
   */
  public function show(string $slug): View
  {
    $page = $this->service->getPublishedBySlug($slug);
    
    if (!$page) {
      abort(404, 'Página no encontrada');
    }
    
    return view('public.content.page', compact('page'));
  }

  /**
   * Display the home page (public).
   *
   * @return View
   */
  public function home(): View
  {
    $page = $this->service->getPublishedByType('home');
    
    if (!$page) {
      abort(404, 'Página de inicio no encontrada');
    }
    
    return view('public.content.page', compact('page'));
  }

  /**
   * Display the rules page (public).
   *
   * @return View
   */
  public function rules(): View
  {
    $page = $this->service->getPublishedByType('rules');
    
    if (!$page) {
      abort(404, 'Página de reglas no encontrada');
    }
    
    return view('public.content.page', compact('page'));
  }

  /**
   * Display the components page (public).
   *
   * @return View
   */
  public function components(): View
  {
    $page = $this->service->getPublishedByType('components');
    
    if (!$page) {
      abort(404, 'Página de componentes no encontrada');
    }
    
    return view('public.content.page', compact('page'));
  }

  /**
   * Display the annexes page (public).
   *
   * @return View
   */
  public function annexes(): View
  {
    $page = $this->service->getPublishedByType('annexes');
    
    if (!$page) {
      abort(404, 'Página de anexos no encontrada');
    }
    
    return view('public.content.page', compact('page'));
  }
}