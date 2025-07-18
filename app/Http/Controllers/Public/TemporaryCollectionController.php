<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Jobs\GeneratePdfJob;
use App\Models\GeneratedPdf;
use App\Services\Pdf\TemporaryCollectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TemporaryCollectionController extends Controller
{
  public function __construct(
    private TemporaryCollectionService $collectionService
  ) {}
  
  /**
   * Add an entity to the collection (AJAX)
   */
  public function add(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'type' => 'required|in:card,hero',
      'entity_id' => 'required|integer',
      'copies' => 'integer|min:1|max:10',
    ]);
    
    $copies = $validated['copies'] ?? 1;
    
    $success = $this->collectionService->addEntity(
      $validated['type'],
      $validated['entity_id'],
      $copies
    );
    
    if (!$success) {
      return response()->json([
        'success' => false,
        'message' => __('pdf.collection.add_failed'),
      ], 400);
    }
    
    return response()->json([
      'success' => true,
      'message' => __('pdf.collection.added_successfully'),
      'count' => $this->collectionService->getItemsCount(),
      'totalCards' => $this->collectionService->getTotalCardsCount(),
    ]);
  }
  
  /**
   * Remove an entity from the collection (AJAX)
   */
  public function remove(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'type' => 'required|in:card,hero',
      'entity_id' => 'required|integer',
    ]);
    
    $success = $this->collectionService->removeEntity(
      $validated['type'],
      $validated['entity_id']
    );
    
    if (!$success) {
      return response()->json([
        'success' => false,
        'message' => __('pdf.collection.remove_failed'),
      ], 400);
    }
    
    return response()->json([
      'success' => true,
      'message' => __('pdf.collection.removed_successfully'),
      'count' => $this->collectionService->getItemsCount(),
      'totalCards' => $this->collectionService->getTotalCardsCount(),
    ]);
  }
  
  /**
   * Update the number of copies for an entity (AJAX)
   */
  public function updateCopies(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'type' => 'required|in:card,hero',
      'entity_id' => 'required|integer',
      'copies' => 'required|integer|min:1|max:10',
    ]);
    
    $success = $this->collectionService->updateCopies(
      $validated['type'],
      $validated['entity_id'],
      $validated['copies']
    );
    
    if (!$success) {
      return response()->json([
        'success' => false,
        'message' => __('pdf.collection.update_failed'),
      ], 400);
    }
    
    return response()->json([
      'success' => true,
      'message' => __('pdf.collection.updated_successfully'),
      'totalCards' => $this->collectionService->getTotalCardsCount(),
    ]);
  }
  
  /**
   * Clear the entire collection
   */
  public function clear(): RedirectResponse
  {
    $this->collectionService->clearCollection();
    
    return redirect()->route('public.pdf-collection.index')
      ->with('success', __('pdf.collection.cleared_successfully'));
  }
  
  /**
   * Generate PDF from the collection
   */
  public function generate(): RedirectResponse
  {
    // Check if collection has items
    if (!$this->collectionService->hasItems()) {
      return redirect()->route('public.pdf-collection.index')
        ->with('error', __('pdf.collection.no_items'));
    }
    
    // Get items from collection
    $items = $this->collectionService->getItems();
    
    // Prepare filename
    $locale = app()->getLocale();
    $timestamp = now()->format('YmdHis');
    $filename = "custom_collection_{$timestamp}_{$locale}.pdf";
    
    // Create GeneratedPdf record
    $generatedPdf = GeneratedPdf::create([
      'type' => 'custom-collection',
      'filename' => $filename,
      'path' => 'pdfs/temporary/' . $filename,
      'session_id' => session()->getId(),
      'locale' => $locale,
      'is_permanent' => false,
      'expires_at' => now()->addHours(24),
    ]);
    
    // Dispatch job to generate PDF
    GeneratePdfJob::dispatch(
      $generatedPdf,
      'pdfs.collection',
      [
        'items' => $items,
        'locale' => $locale,
        'title' => __('pdf.collection.custom_collection'),
      ]
    );
    
    // Clear the collection after generating
    $this->collectionService->clearCollection();
    
    return redirect()->route('public.pdf-collection.index')
      ->with('success', __('pdf.collection.generation_started'));
  }
  
  /**
   * Get collection status (AJAX)
   */
  public function status(): JsonResponse
  {
    return response()->json([
      'count' => $this->collectionService->getItemsCount(),
      'totalCards' => $this->collectionService->getTotalCardsCount(),
      'hasItems' => $this->collectionService->hasItems(),
    ]);
  }
  
  /**
   * Get collection items (AJAX)
   */
  public function items(): JsonResponse
  {
    $items = $this->collectionService->getItems()->map(function ($item) {
      return [
        'type' => $item['type'],
        'entity_id' => $item['entity']->id,
        'name' => $item['entity']->name,
        'faction' => $item['entity']->faction->name ?? null,
        'copies' => $item['copies'],
        'image_url' => $item['entity']->getPreviewImageUrl() ?: $item['entity']->getImageUrl(),
      ];
    });
    
    return response()->json([
      'items' => $items->values()->toArray(), // Asegurar que sea un array indexado
      'count' => $items->count(),
      'totalCards' => $this->collectionService->getTotalCardsCount(),
    ]);
  }
}