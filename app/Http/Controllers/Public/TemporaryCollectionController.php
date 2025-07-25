<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\GeneratedPdf;
use App\Services\Pdf\TemporaryCollectionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemporaryCollectionController extends Controller
{
  private const MAX_COLLECTION_ITEMS = 100;
  
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
    
    // Check if adding would exceed limit
    $currentTotal = $this->collectionService->getTotalCardsCount();
    if ($currentTotal + $copies > self::MAX_COLLECTION_ITEMS) {
      return response()->json([
        'success' => false,
        'message' => __('pdf.collection.max_items_exceeded', ['max' => self::MAX_COLLECTION_ITEMS]),
      ], 400);
    }
    
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
    
    // Check if update would exceed limit
    $currentCopies = $this->collectionService->getCopies($validated['type'], $validated['entity_id']);
    $difference = $validated['copies'] - $currentCopies;
    $currentTotal = $this->collectionService->getTotalCardsCount();
    
    if ($currentTotal + $difference > self::MAX_COLLECTION_ITEMS) {
      return response()->json([
        'success' => false,
        'message' => __('pdf.collection.max_items_exceeded', ['max' => self::MAX_COLLECTION_ITEMS]),
      ], 400);
    }
    
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
   * Generate PDF from the collection (AJAX for progress tracking)
   */
  public function generate(): JsonResponse
  {
    // Check if collection has items
    if (!$this->collectionService->hasItems()) {
      return response()->json([
        'success' => false,
        'message' => __('pdf.collection.no_items'),
      ], 400);
    }
    
    // Check total items limit
    $totalCards = $this->collectionService->getTotalCardsCount();
    if ($totalCards > self::MAX_COLLECTION_ITEMS) {
      return response()->json([
        'success' => false,
        'message' => __('pdf.collection.max_items_exceeded', ['max' => self::MAX_COLLECTION_ITEMS]),
      ], 400);
    }
    
    // Get items from collection
    $items = $this->collectionService->getItems();
    
    // Prepare filename
    $locale = app()->getLocale();
    $timestamp = now()->format('YmdHis');
    $filename = "{$timestamp}_{$locale}.pdf";
    
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
    
    try {
      // Process items to expand copies
      $expandedItems = collect();
      
      foreach ($items as $item) {
        $copies = $item['copies'] ?? 1;
        
        for ($i = 0; $i < $copies; $i++) {
          $expandedItems->push([
            'type' => $item['type'],
            'entity' => $item['entity'],
          ]);
        }
      }
      
      // Generate PDF synchronously
      $pdf = PDF::loadView('pdfs.collection', [
        'items' => $expandedItems,
        'locale' => $locale,
        'title' => __('pdf.collection.custom_collection'),
      ]);
      
      $pdf->setPaper('a4', 'portrait');
      
      // PDF options
      $pdf->setOptions([
        'isHtml5ParserEnabled' => true,
        'isRemoteEnabled' => true,
        'isPhpEnabled' => false,
        'defaultFont' => 'sans-serif',
        'dpi' => 150,
        'enable_font_subsetting' => false,
      ]);
      
      // Save PDF
      $content = $pdf->output();
      Storage::disk('public')->put($generatedPdf->path, $content);
      
      // Get file size for the response
      $sizeInBytes = strlen($content);

      // Clear the collection after generating
      $this->collectionService->clearCollection();
      
      return response()->json([
        'success' => true,
        'message' => __('pdf.collection.generated_successfully'),
        'pdf_id' => $generatedPdf->id,
        'download_url' => route('public.pdf-collection.download', $generatedPdf),
        'view_url' => route('public.pdf-collection.view', $generatedPdf),
        'size' => $this->formatBytes($sizeInBytes), // Devolver el tamaño formateado
      ]);
      
    } catch (\Exception $e) {
      \Log::error('Failed to generate temporary PDF', [
        'pdf_id' => $generatedPdf->id,
        'error' => $e->getMessage(),
      ]);
      
      // Delete the PDF record if generation failed
      $generatedPdf->delete();
      
      return response()->json([
        'success' => false,
        'message' => __('pdf.collection.generation_failed'),
      ], 500);
    }
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
      'maxItems' => self::MAX_COLLECTION_ITEMS,
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
      'items' => $items->values()->toArray(),
      'count' => $items->count(),
      'totalCards' => $this->collectionService->getTotalCardsCount(),
      'maxItems' => self::MAX_COLLECTION_ITEMS,
    ]);
  }

  /**
   * Format bytes to human readable format
   */
  private function formatBytes($bytes, $precision = 2) 
  {
    $units = ['B', 'KB', 'MB', 'GB'];
    
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    
    $bytes /= pow(1024, $pow);
    
    return round($bytes, $precision) . ' ' . $units[$pow];
  }
}