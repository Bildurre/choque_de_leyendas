<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
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
   * Show a specific section of a content page.
   */
  public function show(string $pageSlug, string $sectionAnchor)
  {
    $page = ContentPage::where('slug', $pageSlug)
      ->where('is_published', true)
      ->first();
    
    if (!$page) {
      abort(404);
    }
    
    $section = ContentSection::where('content_page_id', $page->id)
      ->where('anchor_id', $sectionAnchor)
      ->first();
    
    if (!$section) {
      abort(404);
    }
    
    // Load blocks for this section
    $section->load(['blocks' => function ($query) {
      $query->orderBy('order');
    }]);
    
    return view('content.section', compact('page', 'section'));
  }

  /**
   * Get all sections for a page as JSON (for AJAX requests).
   */
  public function getSectionsByPage(Request $request, string $pageSlug)
  {
    $page = ContentPage::where('slug', $pageSlug)
      ->where('is_published', true)
      ->first();
    
    if (!$page) {
      return response()->json(['error' => 'Page not found'], 404);
    }
    
    $sections = ContentSection::where('content_page_id', $page->id)
      ->where('include_in_index', true)
      ->orderBy('order')
      ->get(['id', 'title', 'anchor_id']);
    
    return response()->json($sections);
  }
}