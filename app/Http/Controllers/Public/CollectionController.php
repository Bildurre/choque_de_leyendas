<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\Collection\CollectionService;
use App\Services\PdfExport\PdfExportService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class CollectionController extends Controller
{
  public function __construct(
    private CollectionService $collectionService,
    private PdfExportService $pdfExportService
  ) {}
  
  /**
   * Add an item to the collection
   */
  public function add(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'type' => 'required|in:hero,card,faction,deck',
      'id' => 'required|integer'
    ]);
    
    $collection = session('print_collection', $this->collectionService->initializeCollection());
    
    switch ($validated['type']) {
      case 'hero':
        $collection = $this->collectionService->addHero($validated['id'], $collection);
        break;
      case 'card':
        $collection = $this->collectionService->addCard($validated['id'], $collection);
        break;
      case 'faction':
        $collection = $this->collectionService->addFaction($validated['id'], $collection);
        break;
      case 'deck':
        $collection = $this->collectionService->addDeck($validated['id'], $collection);
        break;
    }
    
    $collection['updated_at'] = now()->toDateTimeString();
    session(['print_collection' => $collection]);
    
    return response()->json([
      'success' => true,
      'message' => $this->collectionService->getSuccessMessage($validated['type']),
      'count' => $this->collectionService->getTotalCount($collection),
      'copies' => $this->collectionService->getTotalCopies($collection)
    ]);
  }
  
  /**
   * Update the quantity of an item
   */
  public function update(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'type' => 'required|in:hero,card',
      'id' => 'required|integer',
      'copies' => 'required|integer|min:1|max:99'
    ]);
    
    $collection = session('print_collection', $this->collectionService->initializeCollection());
    
    $collection = $this->collectionService->updateQuantity(
      $validated['type'],
      $validated['id'],
      $validated['copies'],
      $collection
    );
    
    $collection['updated_at'] = now()->toDateTimeString();
    session(['print_collection' => $collection]);
    
    return response()->json([
      'success' => true,
      'count' => $this->collectionService->getTotalCount($collection),
      'copies' => $this->collectionService->getTotalCopies($collection)
    ]);
  }
  
  /**
   * Remove an item from the collection
   */
  public function remove(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'type' => 'required|in:hero,card',
      'id' => 'required|integer'
    ]);
    
    $collection = session('print_collection', $this->collectionService->initializeCollection());
    
    $collection = $this->collectionService->removeItem(
      $validated['type'],
      $validated['id'],
      $collection
    );
    
    $collection['updated_at'] = now()->toDateTimeString();
    session(['print_collection' => $collection]);
    
    return response()->json([
      'success' => true,
      'message' => __('public.item_removed_from_collection'),
      'count' => $this->collectionService->getTotalCount($collection),
      'copies' => $this->collectionService->getTotalCopies($collection)
    ]);
  }
  
  /**
   * Clear the entire collection
   */
  public function clear(): JsonResponse
  {
    session()->forget('print_collection');
    
    return response()->json([
      'success' => true,
      'message' => __('public.collection_cleared')
    ]);
  }
  
  /**
   * Generate PDF from the current collection
   */
  public function generatePdf(Request $request): JsonResponse
  {
    $collection = session('print_collection', $this->collectionService->initializeCollection());
    
    if ($this->collectionService->getTotalCount($collection) === 0) {
      return response()->json([
        'success' => false,
        'message' => __('public.print_collection_empty')
      ], 400);
    }
    
    try {
      // Generate PDF asynchronously
      $jobId = $this->pdfExportService->generateAsync(
        'collection',
        'collection',
        [
          'collection' => $collection,
          'reduce_heroes' => $request->boolean('reduce_heroes', false),
          'with_gap' => $request->boolean('with_gap', true),
          'is_permanent' => false,
          'metadata' => [
            'total_items' => $this->collectionService->getTotalCount($collection),
            'total_copies' => $this->collectionService->getTotalCopies($collection),
          ],
        ],
        session()->getId()
      );
      
      // Clear the collection after generating
      session()->forget('print_collection');
      
      return response()->json([
        'success' => true,
        'message' => __('public.pdf_generation_started'),
        'jobId' => $jobId,
      ]);
      
    } catch (\Exception $e) {
      \Log::error('Failed to start PDF generation', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
      ]);
      
      return response()->json([
        'success' => false,
        'message' => __('public.pdf_generation_failed')
      ], 500);
    }
  }
  
  /**
   * Check PDF generation status
   */
  public function checkStatus(string $jobId): JsonResponse
  {
    $status = $this->pdfExportService->getJobStatus($jobId);
    
    if ($status['status'] === 'completed' && $status['pdf_id']) {
      $pdf = \App\Models\GeneratedPdf::find($status['pdf_id']);
      
      if ($pdf) {
        $status['pdf'] = [
          'id' => $pdf->id,
          'url' => $pdf->url,
          'filename' => $pdf->filename,
          'size' => $pdf->formatted_size,
        ];
      }
    }
    
    return response()->json($status);
  }
}