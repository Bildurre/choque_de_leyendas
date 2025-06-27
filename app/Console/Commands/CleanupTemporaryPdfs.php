<?php

namespace App\Console\Commands;

use App\Models\GeneratedPdf;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanupTemporaryPdfs extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'pdfs:cleanup 
                          {--dry-run : Show what would be deleted without actually deleting}
                          {--force : Force deletion without confirmation}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Clean up expired temporary PDFs';

  /**
   * Execute the console command.
   */
  public function handle(): int
  {
    $this->info('Starting temporary PDFs cleanup...');

    // Get expired PDFs
    $expiredPdfs = GeneratedPdf::expired()->get();
    
    if ($expiredPdfs->isEmpty()) {
      $this->info('No expired PDFs found.');
      return Command::SUCCESS;
    }

    $this->info("Found {$expiredPdfs->count()} expired PDFs.");

    if ($this->option('dry-run')) {
      $this->table(
        ['ID', 'Type', 'Filename', 'Created At', 'Expired At'],
        $expiredPdfs->map(function ($pdf) {
          return [
            $pdf->id,
            $pdf->type,
            $pdf->filename,
            $pdf->created_at->format('Y-m-d H:i:s'),
            $pdf->expires_at->format('Y-m-d H:i:s'),
          ];
        })
      );
      
      $totalSize = $expiredPdfs->sum(function ($pdf) {
        return $pdf->exists() ? $pdf->size : 0;
      });
      
      $this->info("Total size to be freed: " . $this->formatBytes($totalSize));
      
      return Command::SUCCESS;
    }

    if (!$this->option('force')) {
      if (!$this->confirm('Do you want to delete these PDFs?')) {
        $this->info('Operation cancelled.');
        return Command::SUCCESS;
      }
    }

    $deletedCount = 0;
    $freedSpace = 0;

    $this->withProgressBar($expiredPdfs, function ($pdf) use (&$deletedCount, &$freedSpace) {
      try {
        if ($pdf->exists()) {
          $freedSpace += $pdf->size;
        }
        
        $pdf->delete(); // This will also delete the file (see model's deleting event)
        $deletedCount++;
        
      } catch (\Exception $e) {
        Log::error('Failed to delete PDF', [
          'pdf_id' => $pdf->id,
          'error' => $e->getMessage(),
        ]);
      }
    });

    $this->newLine(2);
    $this->info("Deleted {$deletedCount} PDFs.");
    $this->info("Freed disk space: " . $this->formatBytes($freedSpace));

    // Also clean up orphaned session PDFs (older than 48 hours)
    $orphanedPdfs = GeneratedPdf::temporary()
      ->whereNotNull('session_id')
      ->where('created_at', '<', now()->subHours(48))
      ->get();

    if ($orphanedPdfs->isNotEmpty()) {
      $this->newLine();
      $this->info("Found {$orphanedPdfs->count()} orphaned session PDFs.");
      
      if ($this->option('force') || $this->confirm('Delete orphaned session PDFs?')) {
        $orphanedCount = 0;
        
        foreach ($orphanedPdfs as $pdf) {
          try {
            $pdf->delete();
            $orphanedCount++;
          } catch (\Exception $e) {
            Log::error('Failed to delete orphaned PDF', [
              'pdf_id' => $pdf->id,
              'error' => $e->getMessage(),
            ]);
          }
        }
        
        $this->info("Deleted {$orphanedCount} orphaned PDFs.");
      }
    }

    Log::info('PDF cleanup completed', [
      'deleted_count' => $deletedCount,
      'freed_space' => $freedSpace,
    ]);

    return Command::SUCCESS;
  }

  /**
   * Format bytes to human readable format
   */
  private function formatBytes($bytes, $precision = 2): string
  {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    
    return round($bytes / pow(1024, $pow), $precision) . ' ' . $units[$pow];
  }
}