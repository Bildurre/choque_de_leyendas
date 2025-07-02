<?php

namespace App\Services\Pdf\ExportServices;

use App\Jobs\GeneratePdfJob;
use App\Models\Faction;
use App\Models\GeneratedPdf;
use Illuminate\Support\Facades\Storage;

class FactionExportService
{
  /**
   * Generate PDFs for a faction (all locales)
   */
  public function generatePdfs(Faction $faction): void
  {
    // Delete existing PDFs first
    $this->deletePdfs($faction->id);
    
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
   * Delete all PDFs for a faction
   */
  public function deletePdfs(int $factionId): void
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
   * Get existing PDFs organized by entity ID
   */
  public function getExistingPdfs(): array
  {
    $currentLocale = app()->getLocale();
    
    $pdfs = GeneratedPdf::where('type', 'faction')
      ->where('is_permanent', true)
      ->get();
    
    $organized = [];
    $byEntity = [];
    
    // First, group all PDFs by entity
    foreach ($pdfs as $pdf) {
      $entityId = $pdf->faction_id;
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
}