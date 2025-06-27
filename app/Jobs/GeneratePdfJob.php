<?php

namespace App\Jobs;

use App\Models\GeneratedPdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GeneratePdfJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * The PDF type
   *
   * @var string
   */
  protected string $type;

  /**
   * The template to use
   *
   * @var string
   */
  protected string $template;

  /**
   * The data for PDF generation
   *
   * @var array
   */
  protected array $data;

  /**
   * Optional session ID for temporary PDFs
   *
   * @var string|null
   */
  protected ?string $sessionId;

  /**
   * Whether the PDF is permanent
   *
   * @var bool
   */
  protected bool $isPermanent;

  /**
   * Optional callback class and method
   *
   * @var array|null
   */
  protected ?array $callback;

  /**
   * Create a new job instance.
   */
  public function __construct(
    string $type,
    string $template,
    array $data,
    ?string $sessionId = null,
    bool $isPermanent = false,
    ?array $callback = null
  ) {
    $this->type = $type;
    $this->template = $template;
    $this->data = $data;
    $this->sessionId = $sessionId;
    $this->isPermanent = $isPermanent;
    $this->callback = $callback;
  }

  /**
   * Execute the job.
   */
  public function handle(): void
  {
    try {
      Log::info('Starting PDF generation', [
        'type' => $this->type,
        'template' => $this->template,
        'is_permanent' => $this->isPermanent,
      ]);

      // Get the appropriate generator service
      $generator = app("App\\Services\\PdfExport\\Generators\\" . ucfirst($this->template) . "PdfGenerator");
      
      // Generate the PDF
      $result = $generator->generate($this->data);
      
      // Store the PDF record
      $pdf = GeneratedPdf::create([
        'type' => $this->type,
        'template' => $this->template,
        'filename' => $result['filename'],
        'path' => $result['path'],
        'session_id' => $this->sessionId,
        'metadata' => $this->data['metadata'] ?? null,
        'is_permanent' => $this->isPermanent,
        'expires_at' => $this->isPermanent ? null : now()->addHours(24),
      ]);

      Log::info('PDF generated successfully', [
        'pdf_id' => $pdf->id,
        'filename' => $pdf->filename,
      ]);

      // Execute callback if provided
      if ($this->callback) {
        if (count($this->callback) === 3) {
          [$class, $method, $jobId] = $this->callback;
          app($class)->$method($pdf, $jobId);
        } else {
          [$class, $method] = $this->callback;
          app($class)->$method($pdf);
        }
      }

    } catch (\Exception $e) {
      Log::error('Failed to generate PDF', [
        'type' => $this->type,
        'template' => $this->template,
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
      ]);

      throw $e;
    }
  }

  /**
   * The job failed to process.
   */
  public function failed(\Throwable $exception): void
  {
    Log::error('PDF generation job failed', [
      'type' => $this->type,
      'template' => $this->template,
      'error' => $exception->getMessage(),
    ]);
  }
}