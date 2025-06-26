<?php

namespace App\Http\Controllers\PdfGenerator\PrintCollection;

use App\Http\Controllers\Controller;
use App\Services\PdfGenerator\PrintCollection\PrintCollectionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class PrintCollectionController extends Controller
{
  /**
   * The print collection service instance
   */
  public function __construct(
    private PrintCollectionService $printCollectionService
  ) {}

  /**
   * Display the print collection page
   */
  public function index(): View
  {
    $collection = session('print_collection', [
      'heroes' => [],
      'cards' => [],
      'updated_at' => now()->toDateTimeString()
    ]);

    if (empty($collection['heroes']) && empty($collection['cards'])) {
      return view('pdf-generator.print-collection.index', [
        'collection' => $collection,
        'heroes' => collect(),
        'cards' => collect()
      ]);
    }

    $models = $this->printCollectionService->loadCollectionModels($collection);

    return view('pdf-generator.print-collection.index', [
      'collection' => $collection,
      'heroes' => $models['heroes'],
      'cards' => $models['cards']
    ]);
  }

  /**
   * Add an item to the print collection
   */
  public function add(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'type' => 'required|in:hero,card,faction,deck',
      'id' => 'required|integer'
    ]);

    $collection = session('print_collection', [
      'heroes' => [],
      'cards' => [],
      'updated_at' => now()->toDateTimeString()
    ]);

    switch ($validated['type']) {
      case 'hero':
        $collection = $this->printCollectionService->addHero($validated['id'], $collection);
        break;
      case 'card':
        $collection = $this->printCollectionService->addCard($validated['id'], $collection);
        break;
      case 'faction':
        $collection = $this->printCollectionService->addFaction($validated['id'], $collection);
        break;
      case 'deck':
        $collection = $this->printCollectionService->addDeck($validated['id'], $collection);
        break;
    }

    $collection['updated_at'] = now()->toDateTimeString();
    session(['print_collection' => $collection]);

    return response()->json([
      'success' => true,
      'message' => $this->printCollectionService->getSuccessMessage($validated['type'])
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

    $collection = session('print_collection', [
      'heroes' => [],
      'cards' => [],
      'updated_at' => now()->toDateTimeString()
    ]);

    $collection = $this->printCollectionService->updateItemQuantity(
      $validated['type'],
      $validated['id'],
      $validated['copies'],
      $collection
    );

    $collection['updated_at'] = now()->toDateTimeString();
    session(['print_collection' => $collection]);

    return response()->json([
      'success' => true,
      'message' => __('public.quantity_updated')
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

    $collection = session('print_collection', [
      'heroes' => [],
      'cards' => [],
      'updated_at' => now()->toDateTimeString()
    ]);

    $collection = $this->printCollectionService->removeItem(
      $validated['type'],
      $validated['id'],
      $collection
    );

    $collection['updated_at'] = now()->toDateTimeString();
    session(['print_collection' => $collection]);

    return response()->json([
      'success' => true,
      'message' => __('public.item_removed_from_collection')
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
   * Generate the PDF for the current collection
   */
  public function generatePdf(Request $request)
  {
    $collection = session('print_collection', [
      'heroes' => [],
      'cards' => [],
      'updated_at' => now()->toDateTimeString()
    ]);

    if (empty($collection['heroes']) && empty($collection['cards'])) {
      return redirect()->route('public.print-collection.index')
        ->with('error', __('public.print_collection_empty'));
    }

    // Load models with all relationships
    $models = $this->printCollectionService->loadCollectionModels($collection);
    
    // Prepare items for PDF
    $items = $this->printCollectionService->prepareItemsForPdf(
      $collection,
      $models['heroes'],
      $models['cards']
    );

    // Get PDF options from request
    $reduceHeroes = $request->boolean('reduce_heroes', false);
    $withGap = $request->boolean('with_gap', true);

    // Generate PDF
    $pdf = PDF::loadView('pdf-generator.print-collection.pdf.collection', [
      'items' => $items,
      'reduceHeroes' => $reduceHeroes,
      'withGap' => $withGap,
      'totalCopies' => $this->printCollectionService->getTotalCopies($collection)
    ]);

    // Configure PDF options
    $pdf->setPaper('a4', 'portrait');
    $pdf->setOptions([
      'isHtml5ParserEnabled' => true,
      'isRemoteEnabled' => true,
      'isPhpEnabled' => false,
      'defaultFont' => 'sans-serif',
      'dpi' => 150,
      'enable_font_subsetting' => false,
    ]);

    return $pdf->stream('collection-' . now()->format('Y-m-d') . '.pdf');
  }
}