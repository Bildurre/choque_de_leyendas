<?php

namespace App\Services\Pdf;

use App\Jobs\GeneratePdfJob;
use App\Models\Faction;
use App\Models\FactionDeck;
use App\Models\GeneratedPdf;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PdfExportService
{
  /**
   * Get data for the index page based on active tab
   */
  public function getIndexData(string $activeTab): array
  {
    $data = [
      'factions' => collect(),
      'decks' => collect(),
      'customExports' => collect(),
      'existingPdfs' => [
        'faction' => [],
        'deck' => [],
        'custom' => [],
      ],
    ];
    
    switch ($activeTab) {
      case 'factions':
        $data['factions'] = $this->getFactions();
        $data['existingPdfs']['faction'] = $this->getExistingPdfs('faction', 'faction_id');
        break;
        
      case 'decks':
        $data['decks'] = $this->getDecks();
        $data['existingPdfs']['deck'] = $this->getExistingPdfs('deck', 'deck_id');
        break;
        
      case 'others':
        $data['customExports'] = $this->getCustomExports();
        $data['existingPdfs']['custom'] = $this->getExistingCustomPdfs();
        break;
    }
    
    return $data;
  }
  
  /**
   * Generate PDFs for a faction (all locales)
   */
  public function generateFactionPdfs(Faction $faction): void
  {
    // Delete existing PDFs first
    $this->deleteFactionPdfs($faction->id);
    
    // Get published heroes and cards
    $heroes = $faction->heroes()->published()->get();
    $cards = $faction->cards()->published()->get();
    
    // Prepare items for PDF (1 copy of each hero, 2 copies of each card)
    $items = collect();
    
    foreach ($heroes as $hero) {
      $items->push([
        'type' => 'hero',
        'entity' => $hero,
        'copies' => 1,
      ]);
    }
    
    foreach ($cards as $card) {
      $items->push([
        'type' => 'card',
        'entity' => $card,
        'copies' => 2,
      ]);
    }
    
    // Generate PDF for each locale
    $locales = config('laravellocalization.supportedLocales', ['es' => []]);
    
    foreach (array_keys($locales) as $locale) {
      $filename = Str::slug($faction->name) . '-' . $locale . '.pdf';
      
      // Create GeneratedPdf record
      $generatedPdf = GeneratedPdf::create([
        'type' => 'faction',
        'filename' => $filename,
        'path' => 'pdfs/factions/' . $filename,
        'metadata' => [
          'faction_id' => $faction->id,
          'locale' => $locale,
        ],
        'is_permanent' => true,
        'expires_at' => null,
      ]);
      
      // Dispatch job to generate PDF
      GeneratePdfJob::dispatch(
        $generatedPdf,
        'pdfs.collection',
        [
          'items' => $items,
          'locale' => $locale,
          'title' => $faction->getTranslation('name', $locale),
        ]
      );
    }
  }
  
  /**
   * Generate PDFs for a deck (all locales)
   */
  public function generateDeckPdfs(FactionDeck $deck): void
  {
    // Delete existing PDFs first
    $this->deleteDeckPdfs($deck->id);
    
    // Get heroes and cards with their copies from pivot
    $heroes = $deck->heroes()->published()->get();
    $cards = $deck->cards()->published()->get();
    
    // Prepare items for PDF with copies from deck
    $items = collect();
    
    foreach ($heroes as $hero) {
      $copies = $hero->pivot->copies ?? 1;
      $items->push([
        'type' => 'hero',
        'entity' => $hero,
        'copies' => $copies,
      ]);
    }
    
    foreach ($cards as $card) {
      $copies = $card->pivot->copies ?? 1;
      $items->push([
        'type' => 'card',
        'entity' => $card,
        'copies' => $copies,
      ]);
    }
    
    // Generate PDF for each locale
    $locales = config('laravellocalization.supportedLocales', ['es' => []]);
    
    foreach (array_keys($locales) as $locale) {
      $filename = Str::slug($deck->name) . '-' . $locale . '.pdf';
      
      // Create GeneratedPdf record
      $generatedPdf = GeneratedPdf::create([
        'type' => 'deck',
        'filename' => $filename,
        'path' => 'pdfs/decks/' . $filename,
        'metadata' => [
          'deck_id' => $deck->id,
          'locale' => $locale,
        ],
        'is_permanent' => true,
        'expires_at' => null,
      ]);
      
      // Dispatch job to generate PDF
      GeneratePdfJob::dispatch(
        $generatedPdf,
        'pdfs.collection',
        [
          'items' => $items,
          'locale' => $locale,
          'title' => $deck->getTranslation('name', $locale),
        ]
      );
    }
  }
  
  /**
   * Delete all PDFs for a faction
   */
  public function deleteFactionPdfs(int $factionId): void
  {
    $pdfs = GeneratedPdf::where('type', 'faction')
      ->where('metadata->faction_id', $factionId)
      ->get();
    
    foreach ($pdfs as $pdf) {
      // Delete file from storage
      if (Storage::disk('public')->exists($pdf->path)) {
        Storage::disk('public')->delete($pdf->path);
      }
      
      // Delete database record
      $pdf->delete();
    }
  }
  
  /**
   * Delete all PDFs for a deck
   */
  public function deleteDeckPdfs(int $deckId): void
  {
    $pdfs = GeneratedPdf::where('type', 'deck')
      ->where('metadata->deck_id', $deckId)
      ->get();
    
    foreach ($pdfs as $pdf) {
      // Delete file from storage
      if (Storage::disk('public')->exists($pdf->path)) {
        Storage::disk('public')->delete($pdf->path);
      }
      
      // Delete database record
      $pdf->delete();
    }
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
    return FactionDeck::with(['faction', 'heroes', 'cards'])
      ->orderBy('name')
      ->get();
  }
  
  /**
   * Get custom export configurations
   */
  private function getCustomExports(): Collection
  {
    return collect([
      [
        'key' => 'rules',
        'name' => __('admin.pdf_export.rules'),
        'description' => __('admin.pdf_export.rules_description'),
        'template' => 'rules',
      ],
      [
        'key' => 'tokens',
        'name' => __('admin.pdf_export.tokens'),
        'description' => __('admin.pdf_export.tokens_description'),
        'template' => 'tokens',
      ],
    ]);
  }
  
  /**
   * Get existing PDFs by type and organize by entity ID
   */
  private function getExistingPdfs(string $type, string $metadataKey): array
  {
    $currentLocale = app()->getLocale();
    
    $pdfs = GeneratedPdf::where('type', $type)
      ->where('is_permanent', true)
      ->get();
    
    $organized = [];
    foreach ($pdfs as $pdf) {
      if (isset($pdf->metadata[$metadataKey])) {
        $entityId = $pdf->metadata[$metadataKey];
        $pdfLocale = $pdf->metadata['locale'] ?? null;
        
        // Only add if it's the current locale or if we haven't found one yet
        if (!isset($organized[$entityId]) || $pdfLocale === $currentLocale) {
          $organized[$entityId] = $pdf;
        }
      }
    }
    
    return $organized;
  }
  
  /**
   * Get existing custom PDFs organized by type
   */
  private function getExistingCustomPdfs(): array
  {
    $pdfs = GeneratedPdf::whereIn('type', ['rules', 'tokens'])
      ->where('is_permanent', true)
      ->get();
    
    $organized = [];
    foreach ($pdfs as $pdf) {
      $organized[$pdf->type] = $pdf;
    }
    
    return $organized;
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
    
    // Check if this PDF has a locale in metadata
    $pdfLocale = $pdf->metadata['locale'] ?? null;
    
    // If PDF has no specific locale or matches current locale, return it
    if (!$pdfLocale || $pdfLocale === $currentLocale) {
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
    if ($pdf->type === 'faction' && isset($pdf->metadata['faction_id'])) {
      $localizedPdf = GeneratedPdf::where('type', 'faction')
        ->where('metadata->faction_id', $pdf->metadata['faction_id'])
        ->where('metadata->locale', $locale)
        ->first();
        
      return ($localizedPdf && $localizedPdf->exists()) ? $localizedPdf : null;
      
    } elseif ($pdf->type === 'deck' && isset($pdf->metadata['deck_id'])) {
      $localizedPdf = GeneratedPdf::where('type', 'deck')
        ->where('metadata->deck_id', $pdf->metadata['deck_id'])
        ->where('metadata->locale', $locale)
        ->first();
        
      return ($localizedPdf && $localizedPdf->exists()) ? $localizedPdf : null;
    }
    
    return null;
  }
}