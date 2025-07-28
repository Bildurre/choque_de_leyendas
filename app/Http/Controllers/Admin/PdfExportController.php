<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faction;
use App\Models\FactionDeck;
use App\Models\Page;
use App\Models\GeneratedPdf;
use App\Services\Pdf\PdfExportService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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
      'pages' => $data['pages'],
      'existingPdfs' => $data['existingPdfs'],
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
   * Generate PDF for a page
   */
  public function generatePage(Page $page): RedirectResponse
  {
    try {
      $this->pdfExportService->generatePagePdfs($page);
      
      return redirect()->route('admin.pdf-export.index', ['tab' => 'pages'])
        ->with('success', __('admin.pdf_generation_started', ['name' => $page->title]));
    } catch (\Exception $e) {
      \Log::error('Failed to generate page PDF', [
        'page_id' => $page->id,
        'error' => $e->getMessage(),
      ]);
      
      return redirect()->route('admin.pdf-export.index', ['tab' => 'pages'])
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
    } elseif (in_array($pdf->type, ['counters-list', 'cut-out-counters'])) {
      $tab = 'others';
    } elseif ($pdf->type === 'page') {
      $tab = 'pages';
    }
    
    try {
      // Delete all PDFs for this entity (all locales)
      if ($pdf->type === 'faction' && $pdf->faction_id) {
        $this->pdfExportService->deleteFactionPdfs($pdf->faction_id);
      } elseif ($pdf->type === 'deck' && $pdf->deck_id) {
        $this->pdfExportService->deleteDeckPdfs($pdf->deck_id);
      // } elseif ($pdf->type === 'counters-list') {
      //   $this->pdfExportService->deleteCountersListPdfs();
      } elseif ($pdf->type === 'cut-out-counters') {
        $this->pdfExportService->deleteCutOutCountersPdfs();
      } elseif ($pdf->type === 'page' && $pdf->page_id) {
        $this->pdfExportService->deletePagePdfs($pdf->page_id);
      } else {
        // For any other PDFs, just delete this one
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
   * Generate counters list PDF
   */
  // public function generateCountersList(): RedirectResponse
  // {
  //   try {
  //     $this->pdfExportService->generateCountersListPdf();
      
  //     return redirect()->route('admin.pdf-export.index', ['tab' => 'others'])
  //       ->with('success', __('admin.pdf_generation_started', ['name' => __('pdf.counters_list')]));
  //   } catch (\Exception $e) {
  //     \Log::error('Failed to generate counters list PDF', [
  //       'error' => $e->getMessage(),
  //     ]);
      
  //     return redirect()->route('admin.pdf-export.index', ['tab' => 'others'])
  //       ->with('error', __('admin.pdf_generation_failed'));
  //   }
  // }
  
  /**
   * Generate cut-out counters PDF
   */
  public function generateCutOutCounters(): RedirectResponse
  {
    try {
      $this->pdfExportService->generateCutOutCountersPdf();
      
      return redirect()->route('admin.pdf-export.index', ['tab' => 'others'])
        ->with('success', __('admin.pdf_generation_started', ['name' => __('pdf.cut_out_counters')]));
    } catch (\Exception $e) {
      \Log::error('Failed to generate cut-out counters PDF', [
        'error' => $e->getMessage(),
      ]);
      
      return redirect()->route('admin.pdf-export.index', ['tab' => 'others'])
        ->with('error', __('admin.pdf_generation_failed'));
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
        ->with('success', __('pdf.cleanup_completed', ['count' => $deletedCount]));
    } catch (\Exception $e) {
      \Log::error('Failed to cleanup temporary PDFs', [
        'error' => $e->getMessage(),
      ]);
      
      return redirect()->route('admin.pdf-export.index', ['tab' => request()->get('tab', 'factions')])
        ->with('error', __('pdf.cleanup_failed'));
    }
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
}