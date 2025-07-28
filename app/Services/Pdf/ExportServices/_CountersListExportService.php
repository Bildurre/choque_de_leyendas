<?php

namespace App\Services\Pdf\ExportServices;

use App\Jobs\GeneratePdfJob;
use App\Models\Counter;
use App\Models\GeneratedPdf;
use Illuminate\Support\Facades\Storage;

class CountersListExportService
{
  /**
   * Generate counters list PDF (one per locale)
   */
  public function generatePdfs(): void
  {
    // Delete existing counters list PDFs first
    $this->deletePdfs();
    
    // Get published counters for the list
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
      // Generate counters list PDF
      $listFilename = ($locale === 'es' ? 'lista_de_contadores' : 'counters_list') . '_' . $locale . '.pdf';
      $listTitle = $locale === 'es' ? 'Lista de Contadores' : 'Counters List';
      
      $listPdf = GeneratedPdf::create([
        'type' => 'counters-list',
        'filename' => $listFilename,
        'path' => 'pdfs/others/' . $listFilename,
        'locale' => $locale,
        'is_permanent' => true,
        'expires_at' => null,
      ]);
      
      GeneratePdfJob::dispatch(
        $listPdf,
        'pdfs.counters-list',
        [
          'locale' => $locale,
          'title' => $listTitle,
          'boonCounters' => $boonCounters,
          'baneCounters' => $baneCounters,
        ]
      );
    }
  }
  
  /**
   * Delete all counters list PDFs
   */
  public function deletePdfs(): void
  {
    $pdfs = GeneratedPdf::where('type', 'counters-list')->get();
    
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
    
    $pdfs = GeneratedPdf::where('type', 'counters-list')
      ->where('is_permanent', true)
      ->orderBy('locale')
      ->get();
    
    // Group by type and find the current locale PDF
    $grouped = [];
    $key = 'counters-list';
    
    if ($pdfs->isNotEmpty()) {
      $grouped[$key] = [
        'pdfs' => [],
        'display_name' => __('pdf.counters_list'),
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