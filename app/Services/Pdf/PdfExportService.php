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
      // Get translated slug from the translatable field
      $translatedSlug = $faction->getTranslation('slug', $locale);
      $filename = $translatedSlug . '-' . $locale . '.pdf';
      
      // Create GeneratedPdf record
      $generatedPdf = GeneratedPdf::create([
        'type' => 'faction',
        'filename' => $filename,
        'path' => 'pdfs/factions/' . $filename,
        'locale' => $locale,
        'faction_id' => $faction->id,
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
      // Get translated slug from the translatable field
      $translatedSlug = $deck->getTranslation('slug', $locale);
      $filename = $translatedSlug . '-' . $locale . '.pdf';
      
      // Create GeneratedPdf record
      $generatedPdf = GeneratedPdf::create([
        'type' => 'deck',
        'filename' => $filename,
        'path' => 'pdfs/decks/' . $filename,
        'locale' => $locale,
        'deck_id' => $deck->id,
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
      ->where('faction_id', $factionId)
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
      ->where('deck_id', $deckId)
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
        'name' => __('pdf.types.rules'),
        'description' => __('pdf.types.rules_description'),
        'template' => 'rules',
      ],
      [
        'key' => 'tokens',
        'name' => __('pdf.types.tokens'),
        'description' => __('pdf.types.tokens_description'),
        'template' => 'tokens',
      ],
    ]);
  }
  
  /**
   * Get existing PDFs by type and organize by entity ID
   */
  private function getExistingPdfs(string $type, string $entityIdField): array
  {
    $currentLocale = app()->getLocale();
    
    $pdfs = GeneratedPdf::where('type', $type)
      ->where('is_permanent', true)
      ->get();
    
    $organized = [];
    $byEntity = [];
    
    // First, group all PDFs by entity
    foreach ($pdfs as $pdf) {
      $entityId = $pdf->$entityIdField;
      if ($entityId) {
        $byEntity[$entityId][] = $pdf;
      }
    }
    
    // Then, for each entity, pick the best PDF (current locale preferred)
    foreach ($byEntity as $entityId => $entityPdfs) {
      $currentLocalePdf = null;
      $fallbackPdf = null;
      
      foreach ($entityPdfs as $pdf) {
        if ($pdf->locale === $currentLocale) {
          $currentLocalePdf = $pdf;
          break; // Found the current locale, stop looking
        } elseif (!$fallbackPdf) {
          $fallbackPdf = $pdf; // Keep first PDF as fallback
        }
      }
      
      // Use current locale PDF if available, otherwise use fallback
      $organized[$entityId] = $currentLocalePdf ?: $fallbackPdf;
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
    }
    
    return null;
  }
}