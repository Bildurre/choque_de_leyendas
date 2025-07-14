<?php

namespace App\Services\Pdf\ExportServices;

use App\Jobs\GeneratePdfJob;
use App\Models\Page;
use App\Models\GeneratedPdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PagesExportService
{
  /**
   * Generate PDFs for a page (all locales)
   */
  public function generatePdfs(Page $page): void
  {
    // Delete existing PDFs first
    $this->deletePdfs($page->id);
    
    // Get page blocks ordered (only printable blocks)
    $blocks = $page->printableBlocks()->orderBy('order')->get();
    
    // Generate PDF for each locale
    $locales = config('laravellocalization.supportedLocales', ['es' => []]);
    
    foreach (array_keys($locales) as $locale) {
      // Get translated slug from the translatable field
      $translatedSlug = $page->getTranslation('slug', $locale);
      $filename = $translatedSlug . '-' . $locale . '.pdf';
      
      // Create GeneratedPdf record
      $generatedPdf = GeneratedPdf::create([
        'type' => 'page',
        'filename' => $filename,
        'path' => 'pdfs/pages/' . $filename,
        'locale' => $locale,
        'page_id' => $page->id,
        'is_permanent' => true,
        'expires_at' => null,
      ]);
      
      // Dispatch job to generate PDF
      GeneratePdfJob::dispatch(
        $generatedPdf,
        'pdfs.page',
        [
          'locale' => $locale,
          'title' => $page->getTranslation('title', $locale),
          'page' => $page,
          'blocks' => $blocks,
        ]
      );
    }
  }
  
  /**
   * Delete all PDFs for a page
   */
  public function deletePdfs(int $pageId): void
  {
    $pdfs = GeneratedPdf::where('type', 'page')
      ->where('page_id', $pageId)
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
    
    $pdfs = GeneratedPdf::where('type', 'page')
      ->where('is_permanent', true)
      ->get();
    
    $organized = [];
    $byEntity = [];
    
    // First, group all PDFs by entity
    foreach ($pdfs as $pdf) {
      $entityId = $pdf->page_id;
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