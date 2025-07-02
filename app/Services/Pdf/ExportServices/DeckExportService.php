<?php

namespace App\Services\Pdf\ExportServices;

use App\Jobs\GeneratePdfJob;
use App\Models\FactionDeck;
use App\Models\GeneratedPdf;
use Illuminate\Support\Facades\Storage;

class DeckExportService
{
  /**
   * Generate PDFs for a deck (all locales)
   */
  public function generatePdfs(FactionDeck $deck): void
  {
    // Delete existing PDFs first
    $this->deletePdfs($deck->id);
    
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
   * Delete all PDFs for a deck
   */
  public function deletePdfs(int $deckId): void
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
   * Get existing PDFs organized by entity ID
   */
  public function getExistingPdfs(): array
  {
    $currentLocale = app()->getLocale();
    
    $pdfs = GeneratedPdf::where('type', 'deck')
      ->where('is_permanent', true)
      ->get();
    
    $organized = [];
    $byEntity = [];
    
    // First, group all PDFs by entity
    foreach ($pdfs as $pdf) {
      $entityId = $pdf->deck_id;
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