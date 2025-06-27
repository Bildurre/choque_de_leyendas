<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\GeneratedPdf;
use App\Services\Collection\CollectionService;
use App\Services\PdfExport\PdfExportService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadsController extends Controller
{
  public function __construct(
    private PdfExportService $pdfExportService,
    private CollectionService $collectionService
  ) {}
  
  /**
   * Display the downloads page with collection management
   */
  public function index(Request $request): View
  {
    // Get current session ID
    $sessionId = session()->getId();
    
    // Get permanent PDFs (available to all)
    $permanentPdfs = GeneratedPdf::permanent()
      ->orderBy('created_at', 'desc')
      ->get();
    
    // Get session PDFs (only for current user)
    $sessionPdfs = GeneratedPdf::forSession($sessionId)
      ->where('expires_at', '>', now())
      ->orderBy('created_at', 'desc')
      ->get();
    
    // Get current collection
    $collection = session('print_collection', $this->collectionService->initializeCollection());
    
    // Load collection models if not empty
    if ($this->collectionService->getTotalCount($collection) > 0) {
      $models = $this->collectionService->loadCollectionModels($collection);
    } else {
      $models = ['heroes' => collect(), 'cards' => collect()];
    }
    
    return view('public.downloads.index', [
      'permanentPdfs' => $permanentPdfs,
      'sessionPdfs' => $sessionPdfs,
      'collection' => $collection,
      'heroes' => $models['heroes'],
      'cards' => $models['cards'],
      'totalCount' => $this->collectionService->getTotalCount($collection),
      'totalCopies' => $this->collectionService->getTotalCopies($collection),
    ]);
  }
  
  /**
   * Download a PDF file
   */
  public function download(GeneratedPdf $pdf): StreamedResponse
  {
    // Check if user has access to this PDF
    if (!$pdf->is_permanent && $pdf->session_id !== session()->getId()) {
      abort(403, 'Unauthorized access to this file.');
    }
    
    // Check if file exists
    if (!$pdf->exists()) {
      abort(404, 'File not found.');
    }
    
    // Stream the file
    return response()->streamDownload(function () use ($pdf) {
      echo \Storage::disk('public')->get($pdf->path);
    }, $pdf->filename, [
      'Content-Type' => 'application/pdf',
      'Content-Disposition' => 'attachment; filename="' . $pdf->filename . '"',
    ]);
  }
  
  /**
   * Delete a PDF file
   */
  public function destroy(GeneratedPdf $pdf): \Illuminate\Http\JsonResponse
  {
    // Check if user has access to delete this PDF
    if (!$pdf->is_permanent && $pdf->session_id !== session()->getId()) {
      return response()->json([
        'success' => false,
        'message' => __('public.unauthorized_pdf_delete')
      ], 403);
    }
    
    // Only allow deletion of temporary PDFs
    if ($pdf->is_permanent) {
      return response()->json([
        'success' => false,
        'message' => __('public.cannot_delete_permanent_pdf')
      ], 403);
    }
    
    try {
      $pdf->delete();
      
      return response()->json([
        'success' => true,
        'message' => __('public.pdf_deleted_successfully')
      ]);
    } catch (\Exception $e) {
      \Log::error('Failed to delete PDF', [
        'pdf_id' => $pdf->id,
        'error' => $e->getMessage()
      ]);
      
      return response()->json([
        'success' => false,
        'message' => __('public.pdf_deletion_failed')
      ], 500);
    }
  }
}