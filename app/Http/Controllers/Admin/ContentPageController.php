<?php
// app/Http/Controllers/Admin/ContentPageController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContentPageRequest;
use App\Models\ContentPage;
use App\Services\Admin\ContentPageService;

class ContentPageController extends Controller
{
  public function __construct(
    protected ContentPageService $service
  ) {}

  public function index()
  {
    $pages = ContentPage::with('sections')->orderBy('created_at', 'desc')->paginate(10);
    return view('admin.content-pages.index', compact('pages'));
  }

  public function create()
  {
    return view('admin.content-pages.create');
  }

  public function store(ContentPageRequest $request)
  {
    $page = $this->service->create($request->validated());
    return redirect()
      ->route('admin.content-pages.edit', $page)
      ->with('success', 'Página creada exitosamente.');
  }

  public function edit(ContentPage $contentPage)
  {
    $page = $contentPage->load(['sections.blocks']);
    return view('admin.content-pages.edit', compact('page'));
  }

  public function update(ContentPageRequest $request, ContentPage $contentPage)
  {
    $page = $this->service->update($contentPage, $request->validated());
    return redirect()
      ->route('admin.content-pages.edit', $page)
      ->with('success', 'Página actualizada exitosamente.');
  }

  public function destroy(ContentPage $contentPage)
  {
    $this->service->delete($contentPage);
    return redirect()
      ->route('admin.content-pages.index')
      ->with('success', 'Página eliminada exitosamente.');
  }
}