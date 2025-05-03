<?php
namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContentSectionRequest;
use App\Models\ContentSection;
use App\Services\ContentSectionService;

class ContentSectionController extends Controller
{
  public function __construct(
    protected ContentSectionService $service
  ) {}

  public function store(ContentSectionRequest $request)
  {
    $section = $this->service->create($request->validated());
    return redirect()
      ->route('admin.content-pages.edit', $section->page)
      ->with('success', 'Sección creada exitosamente.');
  }

  public function update(ContentSectionRequest $request, ContentSection $contentSection)
  {
    $section = $this->service->update($contentSection, $request->validated());
    return redirect()
      ->route('admin.content-pages.edit', $section->page)
      ->with('success', 'Sección actualizada exitosamente.');
  }

  public function destroy(ContentSection $contentSection)
  {
    $pageId = $contentSection->page_id;
    $this->service->delete($contentSection);
    return redirect()
      ->route('admin.content-pages.edit', $pageId)
      ->with('success', 'Sección eliminada exitosamente.');
  }

  public function reorder(ContentSection $contentSection, int $order)
  {
    $this->service->reorder($contentSection, $order);
    return response()->json(['success' => true]);
  }
}