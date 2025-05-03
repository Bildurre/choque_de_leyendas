<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentPage;
use App\Services\ContentPageService;
use App\Http\Requests\Admin\ContentPageRequest;
use Illuminate\Http\Request;

class ContentPageController extends Controller
{
  protected ContentPageService $contentPageService;
  
  public function __construct(ContentPageService $contentPageService)
  {
    $this->contentPageService = $contentPageService;
  }
  
  public function index()
  {
    $pages = ContentPage::orderBy('order')->get();
    return view('admin.content.pages.index', compact('pages'));
  }
  
  public function create()
  {
    return view('admin.content.pages.create');
  }
  
  public function store(ContentPageRequest $request)
  {
    $page = $this->contentPageService->createPage($request->validated());
    
    return redirect()
      ->route('admin.content.pages.edit', $page)
      ->with('success', 'Página creada con éxito');
  }
  
  public function edit(ContentPage $page)
  {
    $page->load(['sections.blocks']);
    return view('admin.content.pages.edit', compact('page'));
  }
  
  // ... otros métodos
}