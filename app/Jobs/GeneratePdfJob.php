<?php

namespace App\Jobs;

use App\Models\GeneratedPdf;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class GeneratePdfJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
  
  /**
   * Create a new job instance
   */
  public function __construct(
    public GeneratedPdf $generatedPdf,
    public string $view,
    public array $data
  ) {}
  
  /**
   * Execute the job
   */
  public function handle(): void
  {
    try {
      // Set locale for this job
      if (isset($this->data['locale'])) {
        App::setLocale($this->data['locale']);
      }
      
      // Process items to expand copies
      if (isset($this->data['items'])) {
        $expandedItems = collect();
        
        foreach ($this->data['items'] as $item) {
          $copies = $item['copies'] ?? 1;
          
          for ($i = 0; $i < $copies; $i++) {
            $expandedItems->push([
              'type' => $item['type'],
              'entity' => $item['entity'],
            ]);
          }
        }
        
        $this->data['items'] = $expandedItems;
      }
      
      // Generate PDF
      $pdf = PDF::loadView($this->view, $this->data);
      $pdf->setPaper('a4', 'portrait');
      
      // PDF options
      $pdf->setOptions([
        'isHtml5ParserEnabled' => true,
        'isRemoteEnabled' => true,
        'isPhpEnabled' => false,
        'defaultFont' => 'sans-serif',
        'dpi' => 150,
        'enable_font_subsetting' => false,
      ]);
      
      // Save PDF
      $content = $pdf->output();
      Storage::disk('public')->put($this->generatedPdf->path, $content);
      
    } catch (\Exception $e) {
      \Log::error('Failed to generate PDF', [
        'pdf_id' => $this->generatedPdf->id,
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
      ]);
      
      // Delete the PDF record if generation failed
      $this->generatedPdf->delete();
      
      throw $e;
    }
  }
}