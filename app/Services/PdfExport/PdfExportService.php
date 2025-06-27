<?php

namespace App\Services\PdfExport;

use App\Jobs\GeneratePdfJob;
use App\Models\GeneratedPdf;
use Illuminate\Support\Str;

class PdfExportService
{
  /**
   * Generate a PDF asynchronously
   */
  public function generateAsync(
    string $type,
    string $template,
    array $data,
    ?string $sessionId = null,
    bool $isPermanent = false,
    ?array $callback = null
  ): string {
    // Create a job ID for tracking
    $jobId = Str::uuid()->toString();
    
    // Store job ID in cache for status checking
    cache()->put("pdf_job_{$jobId}", [
      'status' => 'pending',
      'progress' => 0,
      'pdf_id' => null,
    ], now()->addHours(2));
    
    // Dispatch the job
    GeneratePdfJob::dispatch($type, $template, $data, $sessionId, $isPermanent, [
      self::class,
      'handleJobComplete',
      $jobId
    ]);
    
    return $jobId;
  }
  
  /**
   * Generate a PDF synchronously (for admin immediate downloads)
   */
  public function generateSync(
    string $type,
    string $template,
    array $data,
    bool $isPermanent = true
  ): GeneratedPdf {
    // Get the appropriate generator
    $generator = $this->getGenerator($template);
    
    // Generate the PDF
    $result = $generator->generate($data);
    
    // Store the PDF record
    return GeneratedPdf::create([
      'type' => $type,
      'template' => $template,
      'filename' => $result['filename'],
      'path' => $result['path'],
      'session_id' => null,
      'metadata' => $data['metadata'] ?? null,
      'is_permanent' => $isPermanent,
      'expires_at' => $isPermanent ? null : now()->addHours(24),
    ]);
  }
  
  /**
   * Get job status
   */
  public function getJobStatus(string $jobId): array
  {
    return cache()->get("pdf_job_{$jobId}", [
      'status' => 'not_found',
      'progress' => 0,
      'pdf_id' => null,
    ]);
  }
  
  /**
   * Handle job completion callback
   */
  public static function handleJobComplete(GeneratedPdf $pdf, string $jobId): void
  {
    cache()->put("pdf_job_{$jobId}", [
      'status' => 'completed',
      'progress' => 100,
      'pdf_id' => $pdf->id,
    ], now()->addHours(2));
  }
  
  /**
   * Get the appropriate generator instance
   */
  protected function getGenerator(string $template)
  {
    $generatorClass = "App\\Services\\PdfExport\\Generators\\" . ucfirst($template) . "PdfGenerator";
    
    if (!class_exists($generatorClass)) {
      throw new \InvalidArgumentException("Generator for template '{$template}' not found");
    }
    
    return app($generatorClass);
  }
  
  /**
   * Get available PDFs for a session
   */
  public function getSessionPdfs(string $sessionId): \Illuminate\Database\Eloquent\Collection
  {
    return GeneratedPdf::forSession($sessionId)
      ->where('expires_at', '>', now())
      ->orderBy('created_at', 'desc')
      ->get();
  }
  
  /**
   * Get all permanent PDFs
   */
  public function getPermanentPdfs(): \Illuminate\Database\Eloquent\Collection
  {
    return GeneratedPdf::permanent()
      ->orderBy('created_at', 'desc')
      ->get();
  }
  
  /**
   * Delete a PDF
   */
  public function deletePdf(GeneratedPdf $pdf): bool
  {
    try {
      return $pdf->delete();
    } catch (\Exception $e) {
      \Log::error('Failed to delete PDF', [
        'pdf_id' => $pdf->id,
        'error' => $e->getMessage(),
      ]);
      return false;
    }
  }
}