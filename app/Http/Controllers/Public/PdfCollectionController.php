<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\GeneratedPdf;
use App\Services\Pdf\PdfCollectionService;
use App\Services\Pdf\PdfExportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PdfCollectionController extends Controller
{
  public function __construct(
    private PdfCollectionService $pdfCollectionService,
    private PdfExportService $pdfExportService
  ) {}
  
  /**
   * Display the public PDF collection page
   */
  public function index(): View
  {
    $currentLocale = app()->getLocale();
    
    // Get PDFs organized by type for current locale
    $pdfs = $this->pdfCollectionService->getPublicPdfs($currentLocale);
    
    return view('public.pdf-collection.index', [
      'factionPdfs' => $pdfs['factions'],
      'deckPdfs' => $pdfs['decks'],
      'otherPdfs' => $pdfs['others'],
      'temporaryPdfs' => $pdfs['temporary'],
    ]);
  }
  
  /**
   * View a PDF file
   */
  public function view(GeneratedPdf $pdf)
  {
    // For temporary PDFs, always serve the actual file regardless of locale
    if (!$pdf->is_permanent && $pdf->exists()) {
      return response()->file(storage_path('app/public/' . $pdf->path), [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="' . $pdf->filename . '"',
      ]);
    }
    
    // For permanent PDFs, use the existing logic
    $currentLocale = app()->getLocale();
    $pdfToView = $this->pdfExportService->getPdfForViewing($pdf, $currentLocale);
    
    if (!$pdfToView) {
      abort(404, 'PDF file not found');
    }
    
    // Serve the PDF
    return response()->file(storage_path('app/public/' . $pdfToView->path), [
      'Content-Type' => 'application/pdf',
      'Content-Disposition' => 'inline; filename="' . $pdfToView->filename . '"',
    ]);
  }
  
  /**
   * Download a PDF file
   */
  public function download(GeneratedPdf $pdf): StreamedResponse
  {
    // Check if file exists
    if (!$pdf->exists()) {
      abort(404, 'PDF file not found');
    }
    
    // Stream the file for download
    return response()->streamDownload(function () use ($pdf) {
      echo \Storage::disk('public')->get($pdf->path);
    }, $pdf->filename, [
      'Content-Type' => 'application/pdf',
      'Content-Disposition' => 'attachment; filename="' . $pdf->filename . '"',
    ]);
  }
  
  /**
   * Delete a temporary PDF
   */
  public function destroy(GeneratedPdf $pdf): RedirectResponse
  {
    $sessionId = session()->getId();
    
    // Check if user can delete this PDF
    if (!$this->pdfCollectionService->canDelete($pdf, $sessionId)) {
      abort(403, 'You cannot delete this PDF');
    }
    
    // Delete the PDF
    if ($this->pdfCollectionService->deletePdf($pdf)) {
      return redirect()->route('public.pdf-collection.index')
        ->with('success', __('pdf.deleted_successfully'));
    }
    
    return redirect()->route('public.pdf-collection.index')
      ->with('error', __('pdf.deletion_failed'));
  }
}