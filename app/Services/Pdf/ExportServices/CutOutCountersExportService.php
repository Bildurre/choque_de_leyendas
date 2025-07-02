<?php

namespace App\Services\Pdf\ExportServices;

use App\Jobs\GeneratePdfJob;
use App\Models\Counter;
use App\Models\GeneratedPdf;
use Illuminate\Support\Facades\Storage;

class CutOutCountersExportService
{
  /**
   * Generate cut-out counters PDF (one per locale)
   */
  public function generatePdfs(): void
  {
    // Delete existing cut-out counters PDFs first
    $this->deletePdfs();
    
    // Get published counters organized by type
    $boonCounters = Counter::published()
      ->where('type', 'boon')
      ->orderBy('name')
      ->get();
      
    $baneCounters = Counter::published()
      ->where('type', 'bane')
      ->orderBy('name')
      ->get();
    
    // Get supported locales
    $locales = config('laravellocalization.supportedLocales', ['es' => []]);
    
    foreach (array_keys($locales) as $locale) {
      // Generate cut-out counters PDF
      $filename = ($locale === 'es' ? 'contadores_recortables' : 'cut_out_counters') . '_' . $locale . '.pdf';
      $title = $locale === 'es' ? 'Contadores Recortables' : 'Cut-out Counters';
      
      $pdf = GeneratedPdf::create([
        'type' => 'cut-out-counters',
        'filename' => $filename,
        'path' => 'pdfs/others/' . $filename,
        'locale' => $locale,
        'is_permanent' => true,
        'expires_at' => null,
      ]);
      
      GeneratePdfJob::dispatch(
        $pdf,
        'pdfs.cut-out-counters',
        [
          'locale' => $locale,
          'title' => $title,
          'boonCounters' => $boonCounters,
          'baneCounters' => $baneCounters,
        ]
      );
    }
  }
  
  /**
   * Delete all cut-out counters PDFs
   */
  public function deletePdfs(): void
  {
    $pdfs = GeneratedPdf::where('type', 'cut-out-counters')->get();
    
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
   * Get existing PDFs for the others tab
   */
  public function getExistingPdfs(): array
  {
    $currentLocale = app()->getLocale();
    
    $pdfs = GeneratedPdf::where('type', 'cut-out-counters')
      ->where('is_permanent', true)
      ->orderBy('locale')
      ->get();
    
    // Group by type and find the current locale PDF
    $grouped = [];
    $key = 'cut-out-counters';
    
    if ($pdfs->isNotEmpty()) {
      $grouped[$key] = [
        'pdfs' => [],
        'display_name' => __('pdf.cut_out_counters'),
        'current_locale_pdf' => null,
      ];
      
      foreach ($pdfs as $pdf) {
        $grouped[$key]['pdfs'][] = $pdf;
        
        // Set the current locale PDF for the view button
        if ($pdf->locale === $currentLocale) {
          $grouped[$key]['current_locale_pdf'] = $pdf;
        }
      }
      
      // If no current locale PDF exists, use the first one as fallback
      if (!$grouped[$key]['current_locale_pdf'] && !empty($grouped[$key]['pdfs'])) {
        $grouped[$key]['current_locale_pdf'] = $grouped[$key]['pdfs'][0];
      }
    }
    
    return $grouped;
  }
}