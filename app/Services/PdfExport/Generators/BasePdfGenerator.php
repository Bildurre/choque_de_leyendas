<?php

namespace App\Services\PdfExport\Generators;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

abstract class BasePdfGenerator
{
  /**
   * Default PDF options
   */
  protected array $defaultOptions = [
    'isHtml5ParserEnabled' => true,
    'isRemoteEnabled' => true,
    'isPhpEnabled' => false,
    'defaultFont' => 'sans-serif',
    'dpi' => 150,
    'enable_font_subsetting' => false,
  ];
  
  /**
   * Generate the PDF
   */
  abstract public function generate(array $data): array;
  
  /**
   * Get the view name for this generator
   */
  abstract protected function getViewName(): string;
  
  /**
   * Prepare data for the view
   */
  abstract protected function prepareViewData(array $data): array;
  
  /**
   * Generate filename for the PDF
   */
  protected function generateFilename(array $data): string
  {
    $type = $data['type'] ?? 'document';
    $date = now()->format('Y-m-d');
    $random = Str::random(6);
    
    return "alanda-{$type}-{$date}-{$random}.pdf";
  }
  
  /**
   * Create PDF from view
   */
  protected function createPdf(string $view, array $viewData, array $options = []): \Barryvdh\DomPDF\PDF
  {
    $pdf = PDF::loadView($view, $viewData);
    
    // Set paper size
    $pdf->setPaper($options['paper'] ?? 'a4', $options['orientation'] ?? 'portrait');
    
    // Merge options
    $pdfOptions = array_merge($this->defaultOptions, $options['pdf_options'] ?? []);
    $pdf->setOptions($pdfOptions);
    
    return $pdf;
  }
  
  /**
   * Save PDF to storage
   */
  protected function savePdf(\Barryvdh\DomPDF\PDF $pdf, string $filename, bool $isPermanent = false): string
  {
    $directory = $isPermanent ? 'pdfs/permanent' : 'pdfs/temp';
    $path = "{$directory}/{$filename}";
    
    // Ensure directory exists
    Storage::disk('public')->makeDirectory($directory);
    
    // Save the PDF
    Storage::disk('public')->put($path, $pdf->output());
    
    return $path;
  }
  
  /**
   * Common generate implementation
   */
  protected function generatePdf(array $data, array $options = []): array
  {
    // Prepare view data
    $viewData = $this->prepareViewData($data);
    
    // Generate filename
    $filename = $this->generateFilename($data);
    
    // Create PDF
    $pdf = $this->createPdf($this->getViewName(), $viewData, $options);
    
    // Save PDF
    $isPermanent = $data['is_permanent'] ?? false;
    $path = $this->savePdf($pdf, $filename, $isPermanent);
    
    return [
      'filename' => $filename,
      'path' => $path,
    ];
  }
}