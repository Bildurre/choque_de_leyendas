<?php

namespace App\Services\PdfExport\Generators;

class TokensPdfGenerator extends BasePdfGenerator
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
    return 'pdfs.tokens';
  }
  
  /**
   * Generate filename
   */
  protected function generateFilename(array $data): string
  {
    $locale = app()->getLocale();
    $date = now()->format('Y-m-d');
    
    return "alanda-tokens-{$locale}-{$date}.pdf";
  }
  
  /**
   * Prepare view data
   */
  protected function prepareViewData(array $data): array
  {
    // TODO: Implement actual tokens data preparation
    return [
      'title' => __('pdfs.tokens_title'),
      'tokens' => $data['tokens'] ?? [],
      'generatedAt' => now(),
    ];
  }
}