<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContentBlockRequest;
use App\Models\ContentBlock;
use App\Services\ContentBlockService;

class ContentBlockController extends Controller
{
  public function __construct(
    protected ContentBlockService $service
  ) {}

  public function store(ContentBlockRequest $request)
  {
    $block = $this->service->create($request->validated());
    return redirect()
      ->route('admin.content-pages.edit', $block->section->page)
      ->with('success', 'Bloque creado exitosamente.');
  }

  public function update(ContentBlockRequest $request, ContentBlock $contentBlock)
  {
    $block = $this->service->update($contentBlock, $request->validated());
    return redirect()
      ->route('admin.content-pages.edit', $block->section->page)
      ->with('success', 'Bloque actualizado exitosamente.');
  }

  public function destroy(ContentBlock $contentBlock)
  {
    $pageId = $contentBlock->section->page->id;
    $this->service->delete($contentBlock);
    return redirect()
      ->route('admin.content-pages.edit', $pageId)
      ->with('success', 'Bloque eliminado exitosamente.');
  }

  public function reorder(ContentBlock $contentBlock, int $order)
  {
    $this->service->reorder($contentBlock, $order);
    return response()->json(['success' => true]);
  }
}