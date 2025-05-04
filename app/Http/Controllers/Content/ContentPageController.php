<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\ContentPage;
use App\Services\Content\ContentPageService;
use Illuminate\Support\Facades\App;

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
   * Show a content page by its slug.
   */
  public function show($slug)
  {
    $page = $this->contentPageService->getPageBySlug($slug);
    
    if (!$page || !$page->is_published) {
      abort(404);
    }
    
    // Load sections with their blocks
    $page->load(['sections.blocks' => function ($query) {
      $query->orderBy('order');
    }]);
    
    return view('content.show', compact('page'));
  }

  /**
   * Redirect to the localized version of the page.
   */
  public function localizedRedirect($slug)
  {
    $page = $this->contentPageService->getPageBySlug($slug);
    
    if (!$page || !$page->is_published) {
      abort(404);
    }
    
    $locale = App::getLocale();
    $localizedSlug = $page->getLocalizedSlug($locale);
    
    if ($localizedSlug !== $slug) {
      return redirect()->route('content.show', ['slug' => $localizedSlug]);
    }
    
    return $this->show($slug);
  }

  /**
   * List all published pages.
   */
  public function index()
  {
    $pages = $this->contentPageService->getPublishedPages();
    return view('content.index', compact('pages'));
  }
}