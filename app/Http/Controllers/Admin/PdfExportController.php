<?php

namespace App\Http\Controllers\Admin;

use App\Models\Faction;
use Illuminate\View\View;
use App\Models\FactionDeck;
use App\Models\GeneratedPdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Services\Pdf\PdfExportService;

class PdfExportController extends Controller
{
  public function __construct(
    private PdfExportService $pdfExportService
  ) {}
  
  /**
   * Display the PDF export dashboard with tabs
   */
  public function index(Request $request): View
  {
    $activeTab = $request->get('tab', 'factions');
    
    // Get data from service
    $data = $this->pdfExportService->getIndexData($activeTab);
    
    return view('admin.pdf-export.index', [
      'activeTab' => $activeTab,
      'factions' => $data['factions'],
      'decks' => $data['decks'],
      'customExports' => $data['customExports'],
      'existingPdfs' => $data['existingPdfs'],
    ]);
  }

  /**
   * View a PDF file
   */
  public function view(GeneratedPdf $pdf)
  {
    // Get current locale
    $currentLocale = app()->getLocale();
    
    // Get the appropriate PDF for viewing
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
   * Generate PDF for a faction
   */
  public function generateFaction(Faction $faction): RedirectResponse
  {
    try {
      $this->pdfExportService->generateFactionPdfs($faction);
      
      return redirect()->route('admin.pdf-export.index', ['tab' => 'factions'])
        ->with('success', __('admin.pdf_generation_started', ['name' => $faction->name]));
    } catch (\Exception $e) {
      \Log::error('Failed to generate faction PDF', [
        'faction_id' => $faction->id,
        'error' => $e->getMessage(),
      ]);
      
      return redirect()->route('admin.pdf-export.index', ['tab' => 'factions'])
        ->with('error', __('admin.pdf_generation_failed'));
    }
  }
  
  /**
   * Generate PDF for a deck
   */
  public function generateDeck(FactionDeck $deck): RedirectResponse
  {
    try {
      $this->pdfExportService->generateDeckPdfs($deck);
      
      return redirect()->route('admin.pdf-export.index', ['tab' => 'decks'])
        ->with('success', __('admin.pdf_generation_started', ['name' => $deck->name]));
    } catch (\Exception $e) {
      \Log::error('Failed to generate deck PDF', [
        'deck_id' => $deck->id,
        'error' => $e->getMessage(),
      ]);
      
      return redirect()->route('admin.pdf-export.index', ['tab' => 'decks'])
        ->with('error', __('admin.pdf_generation_failed'));
    }
  }
  
  /**
   * Delete a PDF and all its locale variations
   */
  public function destroy(GeneratedPdf $pdf): RedirectResponse
  {
    $tab = 'factions'; // Default tab
    
    // Determine which tab to redirect to
    if ($pdf->type === 'deck') {
      $tab = 'decks';
    } elseif (in_array($pdf->type, ['rules', 'tokens'])) {
      $tab = 'others';
    }
    
    try {
      // Delete all PDFs for this entity (all locales)
      if ($pdf->type === 'faction' && isset($pdf->metadata['faction_id'])) {
        $this->pdfExportService->deleteFactionPdfs($pdf->metadata['faction_id']);
      } elseif ($pdf->type === 'deck' && isset($pdf->metadata['deck_id'])) {
        $this->pdfExportService->deleteDeckPdfs($pdf->metadata['deck_id']);
      } else {
        // For custom PDFs, just delete this one
        $pdf->delete();
      }
      
      return redirect()->route('admin.pdf-export.index', ['tab' => $tab])
        ->with('success', __('admin.pdf_deleted_successfully'));
    } catch (\Exception $e) {
      \Log::error('Failed to delete PDF', [
        'pdf_id' => $pdf->id,
        'error' => $e->getMessage(),
      ]);
      
      return redirect()->route('admin.pdf-export.index', ['tab' => $tab])
        ->with('error', __('admin.pdf_deletion_failed'));
    }
  }
  
  /**
   * Clean up all temporary PDFs
   */
  public function cleanup(): RedirectResponse
  {
    try {
      $deletedCount = $this->pdfExportService->cleanupTemporaryPdfs();
      
      return redirect()->route('admin.pdf-export.index', ['tab' => request()->get('tab', 'factions')])
        ->with('success', __('admin.pdf_cleanup_completed', ['count' => $deletedCount]));
    } catch (\Exception $e) {
      \Log::error('Failed to cleanup temporary PDFs', [
        'error' => $e->getMessage(),
      ]);
      
      return redirect()->route('admin.pdf-export.index', ['tab' => request()->get('tab', 'factions')])
        ->with('error', __('admin.pdf_cleanup_failed'));
    }
  }
}