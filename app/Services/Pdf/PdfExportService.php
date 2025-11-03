<?php

namespace App\Services\Pdf;

use App\Models\Faction;
use App\Models\FactionDeck;
use App\Models\Page;
use App\Models\GeneratedPdf;
use App\Services\Pdf\ExportServices\FactionExportService;
use App\Services\Pdf\ExportServices\DeckExportService;
// use App\Services\Pdf\ExportServices\CountersListExportService;
use App\Services\Pdf\ExportServices\CutOutCountersExportService;
use App\Services\Pdf\ExportServices\PagesExportService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class PdfExportService
{
  protected FactionExportService $factionExportService;
  protected DeckExportService $deckExportService;
  // protected CountersListExportService $countersListExportService;
  protected CutOutCountersExportService $cutOutCountersExportService;
  protected PagesExportService $pagesExportService;
  
  /**
   * Create a new service instance
   */
  public function __construct(
    FactionExportService $factionExportService,
    DeckExportService $deckExportService,
    // CountersListExportService $countersListExportService,
    CutOutCountersExportService $cutOutCountersExportService,
    PagesExportService $pagesExportService
  ) {
    $this->factionExportService = $factionExportService;
    $this->deckExportService = $deckExportService;
    // $this->countersListExportService = $countersListExportService;
    $this->cutOutCountersExportService = $cutOutCountersExportService;
    $this->pagesExportService = $pagesExportService;
  }
  
  /**
   * Get data for the index page based on active tab
   */
  public function getIndexData(string $activeTab): array
  {
    $data = [
      'factions' => collect(),
      'decks' => collect(),
      'pages' => collect(),
      'existingPdfs' => [
        'faction' => [],
        'deck' => [],
        'page' => [],
        'others' => [],
      ],
    ];
    
    switch ($activeTab) {
      case 'factions':
        $data['factions'] = $this->getFactions();
        $data['existingPdfs']['faction'] = $this->factionExportService->getExistingPdfs();
        break;
        
      case 'decks':
        $data['decks'] = $this->getDecks();
        $data['existingPdfs']['deck'] = $this->deckExportService->getExistingPdfs();
        break;
        
      case 'pages':
        $data['pages'] = $this->getPrintablePages();
        $data['existingPdfs']['page'] = $this->pagesExportService->getExistingPdfs();
        break;
        
      case 'others':
        $data['existingPdfs']['others'] = array_merge(
          // $this->countersListExportService->getExistingPdfs(),
          $this->cutOutCountersExportService->getExistingPdfs()
        );
        break;
    }
    
    return $data;
  }
  
  /**
   * Generate PDFs for a faction (delegates to FactionExportService)
   */
  public function generateFactionPdfs(Faction $faction): void
  {
    $this->factionExportService->generatePdfs($faction);
  }
  
  /**
   * Generate PDFs for a deck (delegates to DeckExportService)
   */
  public function generateDeckPdfs(FactionDeck $deck): void
  {
    $this->deckExportService->generatePdfs($deck);
  }
  
  /**
   * Generate counters list PDF (delegates to CountersListExportService)
   */
  // public function generateCountersListPdf(): void
  // {
  //   $this->countersListExportService->generatePdfs();
  // }
  
  /**
   * Generate cut-out counters PDF (delegates to CutOutCountersExportService)
   */
  public function generateCutOutCountersPdf(): void
  {
    $this->cutOutCountersExportService->generatePdfs();
  }
  
  /**
   * Generate page PDFs (delegates to PagesExportService)
   */
  public function generatePagePdfs(Page $page): void
  {
    $this->pagesExportService->generatePdfs($page);
  }
  
  /**
   * Delete all PDFs for a faction (delegates to FactionExportService)
   */
  public function deleteFactionPdfs(int $factionId): void
  {
    $this->factionExportService->deletePdfs($factionId);
  }
  
  /**
   * Delete all PDFs for a deck (delegates to DeckExportService)
   */
  public function deleteDeckPdfs(int $deckId): void
  {
    $this->deckExportService->deletePdfs($deckId);
  }
  
  /**
   * Delete all counters list PDFs (delegates to CountersListExportService)
   */
  // public function deleteCountersListPdfs(): void
  // {
  //   $this->countersListExportService->deletePdfs();
  // }
  
  /**
   * Delete all cut-out counters PDFs (delegates to CutOutCountersExportService)
   */
  public function deleteCutOutCountersPdfs(): void
  {
    $this->cutOutCountersExportService->deletePdfs();
  }
  
  /**
   * Delete page PDFs (delegates to PagesExportService)
   */
  public function deletePagePdfs(int $pageId): void
  {
    $this->pagesExportService->deletePdfs($pageId);
  }
  
  /**
   * Clean up all temporary PDFs
   */
  public function cleanupTemporaryPdfs(): int
  {
    $temporaryPdfs = GeneratedPdf::where('is_permanent', false)->get();
    $deletedCount = 0;
    
    foreach ($temporaryPdfs as $pdf) {
      try {
        // Delete file from storage
        if (Storage::disk('public')->exists($pdf->path)) {
          Storage::disk('public')->delete($pdf->path);
        }
        
        // Delete database record
        $pdf->delete();
        $deletedCount++;
        
      } catch (\Exception $e) {
        \Log::error('Failed to delete temporary PDF', [
          'pdf_id' => $pdf->id,
          'path' => $pdf->path,
          'error' => $e->getMessage(),
        ]);
      }
    }
    
    return $deletedCount;
  }
  
  /**
   * Get all factions with necessary relationships
   */
  private function getFactions(): Collection
  {
    return Faction::with(['heroes', 'cards'])
      ->orderBy('name')
      ->get();
  }
  
  /**
   * Get all faction decks with necessary relationships
   */
  private function getDecks(): Collection
  {
    return FactionDeck::with(['factions', 'heroes', 'cards'])
      ->orderBy('name')
      ->get();
  }
  
  /**
   * Get all printable pages
   */
  private function getPrintablePages(): Collection
  {
    return Page::printable()
      ->published()
      ->orderBy('title')
      ->get();
  }
  
  /**
   * Get the appropriate PDF for viewing based on locale
   */
  public function getPdfForViewing(GeneratedPdf $pdf, string $currentLocale): ?GeneratedPdf
  {
    // Check if file exists
    if (!$pdf->exists()) {
      return null;
    }
    
    // If PDF has no specific locale or matches current locale, return it
    if (!$pdf->locale || $pdf->locale === $currentLocale) {
      return $pdf;
    }
    
    // Try to find a localized version
    return $this->findLocalizedPdf($pdf, $currentLocale);
  }
  
  /**
   * Find a localized version of a PDF
   */
  private function findLocalizedPdf(GeneratedPdf $pdf, string $locale): ?GeneratedPdf
  {
    if ($pdf->type === 'faction' && $pdf->faction_id) {
      $localizedPdf = GeneratedPdf::where('type', 'faction')
        ->where('faction_id', $pdf->faction_id)
        ->where('locale', $locale)
        ->first();
        
      return ($localizedPdf && $localizedPdf->exists()) ? $localizedPdf : null;
      
    } elseif ($pdf->type === 'deck' && $pdf->deck_id) {
      $localizedPdf = GeneratedPdf::where('type', 'deck')
        ->where('deck_id', $pdf->deck_id)
        ->where('locale', $locale)
        ->first();
        
      return ($localizedPdf && $localizedPdf->exists()) ? $localizedPdf : null;
      
    } elseif ($pdf->type === 'page' && $pdf->page_id) {
      $localizedPdf = GeneratedPdf::where('type', 'page')
        ->where('page_id', $pdf->page_id)
        ->where('locale', $locale)
        ->first();
        
      return ($localizedPdf && $localizedPdf->exists()) ? $localizedPdf : null;
    }
    
    // For other types (counters, etc.)
    $localizedPdf = GeneratedPdf::where('type', $pdf->type)
      ->where('locale', $locale)
      ->where('is_permanent', true)
      ->first();
      
    return ($localizedPdf && $localizedPdf->exists()) ? $localizedPdf : null;
  }
}