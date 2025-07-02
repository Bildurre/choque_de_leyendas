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
   * Generate page PDF for all locales
   */
  public function generatePdfs(string $slug): void
  {
    // Delete existing PDFs for this page type first
    $this->deletePdfs($slug);
    
    // Get supported locales
    $locales = config('laravellocalization.supportedLocales', ['es' => []]);
    
    foreach (array_keys($locales) as $locale) {
      // Find the page by slug in the current locale
      $page = $this->findPageBySlug($slug, $locale);
      
      if (!$page) {
        \Log::warning("Page not found for slug '{$slug}' in locale: {$locale}");
        continue;
      }
      
      // Get page blocks ordered (only printable blocks)
      $blocks = $page->printableBlocks()->orderBy('order')->get();
      
      // Get the localized slug for filename
      $localizedSlug = $page->getTranslation('slug', $locale);
      
      // Generate PDF filename based on localized slug and locale
      $filename = Str::slug($localizedSlug) . '_' . $locale . '.pdf';
      $title = $page->getTranslation('title', $locale);
      
      $pdf = GeneratedPdf::create([
        'type' => $slug, // Use the original slug as type identifier
        'filename' => $filename,
        'path' => 'pdfs/pages/' . $filename,
        'locale' => $locale,
        'is_permanent' => true,
        'expires_at' => null,
      ]);
      
      GeneratePdfJob::dispatch(
        $pdf,
        'pdfs.page', // Generic page template
        [
          'locale' => $locale,
          'title' => $title,
          'page' => $page,
          'blocks' => $blocks,
        ]
      );
    }
  }
  
  /**
   * Find page by slug and locale
   */
  private function findPageBySlug(string $slug, string $locale): ?Page
  {
    // Since pages have translatable slugs, we need to search in all locales
    $supportedLocales = array_keys(config('laravellocalization.supportedLocales', ['es' => []]));
    
    // First, try to find by the given slug in any locale
    $pages = Page::where('is_published', true)
      ->where('is_printable', true)
      ->get();
    
    foreach ($pages as $page) {
      // Check if this page has the given slug in any locale
      foreach ($supportedLocales as $checkLocale) {
        $pageSlug = $page->getTranslation('slug', $checkLocale, false);
        if ($pageSlug === $slug) {
          return $page;
        }
      }
    }
    
    return null;
  }
  
  /**
   * Delete all PDFs for a specific page type
   */
  public function deletePdfs(string $slug): void
  {
    $pdfs = GeneratedPdf::where('type', $slug)->get();
    
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
   * Get existing PDFs for a specific page type
   */
  public function getExistingPdfs(string $slug): array
  {
    $currentLocale = app()->getLocale();
    
    $pdfs = GeneratedPdf::where('type', $slug)
      ->where('is_permanent', true)
      ->orderBy('locale')
      ->get();
    
    // Group by type and find the current locale PDF
    $grouped = [];
    
    if ($pdfs->isNotEmpty()) {
      // Try to find the actual page to get the display name
      $page = $this->findPageBySlug($slug, $currentLocale);
      $displayName = $page ? $page->getTranslation('title', $currentLocale) : $this->getDisplayName($slug);
      
      $grouped[$slug] = [
        'pdfs' => [],
        'display_name' => $displayName,
        'current_locale_pdf' => null,
      ];
      
      foreach ($pdfs as $pdf) {
        $grouped[$slug]['pdfs'][] = $pdf;
        
        // Set the current locale PDF for the view button
        if ($pdf->locale === $currentLocale) {
          $grouped[$slug]['current_locale_pdf'] = $pdf;
        }
      }
      
      // If no current locale PDF exists, use the first one as fallback
      if (!$grouped[$slug]['current_locale_pdf'] && !empty($grouped[$slug]['pdfs'])) {
        $grouped[$slug]['current_locale_pdf'] = $grouped[$slug]['pdfs'][0];
      }
    }
    
    return $grouped;
  }
  
  /**
   * Get all existing PDFs for all printable pages
   */
  public function getAllPagesExistingPdfs(): array
  {
    $currentLocale = app()->getLocale();
    $pages = Page::printable()->published()->get();
    $existingPdfs = [];
    
    foreach ($pages as $page) {
      $slug = $page->slug; // This will get the slug in current locale
      
      $pdfs = GeneratedPdf::where('type', $slug)
        ->where('is_permanent', true)
        ->orderBy('locale')
        ->get();
      
      if ($pdfs->isNotEmpty()) {
        $existingPdfs[$page->id] = [
          'pdfs' => [],
          'page' => $page,
          'current_locale_pdf' => null,
        ];
        
        foreach ($pdfs as $pdf) {
          $existingPdfs[$page->id]['pdfs'][] = $pdf;
          
          // Set the current locale PDF for the view button
          if ($pdf->locale === $currentLocale) {
            $existingPdfs[$page->id]['current_locale_pdf'] = $pdf;
          }
        }
        
        // If no current locale PDF exists, use the first one as fallback
        if (!$existingPdfs[$page->id]['current_locale_pdf'] && !empty($existingPdfs[$page->id]['pdfs'])) {
          $existingPdfs[$page->id]['current_locale_pdf'] = $existingPdfs[$page->id]['pdfs'][0];
        }
      }
    }
    
    return $existingPdfs;
  }
  
  /**
   * Get display name for a page type (fallback if page not found)
   */
  private function getDisplayName(string $slug): string
  {
    $displayNames = [
      'reglas' => __('pdf.types.rules'),
      'anexos' => __('pdf.types.appendix'),
      'glosario' => __('pdf.types.glossary'),
      // Add more as needed
    ];
    
    return $displayNames[$slug] ?? ucfirst($slug);
  }
}