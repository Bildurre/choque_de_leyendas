<?php

namespace App\Services\PdfExport\Generators;

class RulesPdfGenerator extends BasePdfGenerator
{
  /**
   * Generate the PDF
   */
  public function generate(array $data): array
  {
    return $this->generatePdf($data, [
      'paper' => 'a4',
      'orientation' => 'portrait',
    ]);
  }
  
  /**
   * Get the view name
   */
  protected function getViewName(): string
  {
    return 'pdfs.rules';
  }
  
  /**
   * Generate filename
   */
  protected function generateFilename(array $data): string
  {
    $locale = app()->getLocale();
    $date = now()->format('Y-m-d');
    
    return "alanda-rules-{$locale}-{$date}.pdf";
  }
  
  /**
   * Prepare view data
   */
  protected function prepareViewData(array $data): array
  {
    // TODO: Implement actual rules data preparation
    return [
      'title' => __('pdfs.rules_title'),
      'content' => $data['content'] ?? '',
      'generatedAt' => now(),
    ];
  }
}