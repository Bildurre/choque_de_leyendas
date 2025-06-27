<?php

namespace App\Services\PdfExport\Storage;

use App\Models\GeneratedPdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class PdfStorageService
{
  /**
   * Storage disk
   */
  protected string $disk = 'public';
  
  /**
   * Store a PDF file
   */
  public function store(UploadedFile|string $file, string $directory, string $filename): string
  {
    $path = "{$directory}/{$filename}";
    
    if ($file instanceof UploadedFile) {
      return Storage::disk($this->disk)->putFileAs($directory, $file, $filename);
    } else {
      Storage::disk($this->disk)->put($path, $file);
      return $path;
    }
  }
  
  /**
   * Delete a PDF file
   */
  public function delete(string $path): bool
  {
    return Storage::disk($this->disk)->delete($path);
  }
  
  /**
   * Check if file exists
   */
  public function exists(string $path): bool
  {
    return Storage::disk($this->disk)->exists($path);
  }
  
  /**
   * Get file URL
   */
  public function url(string $path): string
  {
    return Storage::disk($this->disk)->url($path);
  }
  
  /**
   * Get file size
   */
  public function size(string $path): int
  {
    return Storage::disk($this->disk)->size($path);
  }
  
  /**
   * Move PDF between directories
   */
  public function move(string $from, string $to): bool
  {
    return Storage::disk($this->disk)->move($from, $to);
  }
  
  /**
   * Clean up expired temporary PDFs
   */
  public function cleanupExpired(): int
  {
    $deleted = 0;
    $expiredPdfs = GeneratedPdf::expired()->get();
    
    foreach ($expiredPdfs as $pdf) {
      try {
        $pdf->delete(); // Model will handle file deletion
        $deleted++;
      } catch (\Exception $e) {
        \Log::error('Failed to delete expired PDF', [
          'pdf_id' => $pdf->id,
          'error' => $e->getMessage(),
        ]);
      }
    }
    
    return $deleted;
  }
  
  /**
   * Get storage statistics
   */
  public function getStatistics(): array
  {
    $permanentCount = GeneratedPdf::permanent()->count();
    $temporaryCount = GeneratedPdf::temporary()->count();
    
    $permanentSize = 0;
    $temporarySize = 0;
    
    // Calculate sizes
    GeneratedPdf::permanent()->each(function ($pdf) use (&$permanentSize) {
      if ($pdf->exists()) {
        $permanentSize += $pdf->size;
      }
    });
    
    GeneratedPdf::temporary()->each(function ($pdf) use (&$temporarySize) {
      if ($pdf->exists()) {
        $temporarySize += $pdf->size;
      }
    });
    
    return [
      'permanent' => [
        'count' => $permanentCount,
        'size' => $permanentSize,
        'formatted_size' => $this->formatBytes($permanentSize),
      ],
      'temporary' => [
        'count' => $temporaryCount,
        'size' => $temporarySize,
        'formatted_size' => $this->formatBytes($temporarySize),
      ],
      'total' => [
        'count' => $permanentCount + $temporaryCount,
        'size' => $permanentSize + $temporarySize,
        'formatted_size' => $this->formatBytes($permanentSize + $temporarySize),
      ],
    ];
  }
  
  /**
   * Format bytes to human readable
   */
  protected function formatBytes($bytes, $precision = 2): string
  {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    
    return round($bytes / pow(1024, $pow), $precision) . ' ' . $units[$pow];
  }
}