<?php

namespace App\Services\Admin;

use Ifsnop\Mysqldump\Mysqldump;
use Illuminate\Support\Facades\Config;
use ZipArchive;

class ExportService
{
  private string $exportPath;

  public function __construct()
  {
    $this->exportPath = storage_path('app/exports');
    $this->ensureDirectoriesExist();
  }

  private function ensureDirectoriesExist(): void
  {
    if (!file_exists($this->exportPath)) {
      mkdir($this->exportPath, 0755, true);
    }
  }

  public function exportDatabase(array $options = []): array
  {
    $timestamp = now()->format('Y-m-d_His');
    $filename = "database_backup_{$timestamp}.sql";
    $filepath = $this->exportPath . '/' . $filename;

    try {
      $dump = new Mysqldump(
        $this->getDsn(),
        Config::get('database.connections.mysql.username'),
        Config::get('database.connections.mysql.password'),
        $this->getDumpSettings($options)
      );

      $dump->start($filepath);

      if ($options['compress'] ?? false) {
        return $this->compressFile($filepath, $timestamp);
      }

      return [
        'success' => true,
        'filename' => $filename,
        'filepath' => $filepath,
        'size' => filesize($filepath),
        'formatted_size' => $this->formatBytes(filesize($filepath))
      ];

    } catch (\Exception $exception) {
      return [
        'success' => false,
        'error' => $exception->getMessage()
      ];
    }
  }

  private function getDsn(): string
  {
    $host = Config::get('database.connections.mysql.host');
    $port = Config::get('database.connections.mysql.port', 3306);
    $database = Config::get('database.connections.mysql.database');
    $charset = Config::get('database.connections.mysql.charset', 'utf8mb4');

    return "mysql:host={$host};port={$port};dbname={$database};charset={$charset}";
  }

  private function getDumpSettings(array $options): array
  {
    $settings = [
      'add-drop-table' => true,
      'add-locks' => true,
      'extended-insert' => true,
      'lock-tables' => false,
      'single-transaction' => true,
      'default-character-set' => 'utf8mb4',
    ];

    if ($options['include_data'] ?? true) {
      $settings['no-data'] = false;
    } else {
      $settings['no-data'] = true;
    }

    if (!empty($options['exclude_tables'])) {
      $settings['exclude-tables'] = $options['exclude_tables'];
    }

    return $settings;
  }

  private function compressFile(string $filepath, string $timestamp): array
  {
    $zipFilename = "database_backup_{$timestamp}.zip";
    $zipFilepath = $this->exportPath . '/' . $zipFilename;

    $zip = new ZipArchive();
    
    if ($zip->open($zipFilepath, ZipArchive::CREATE) !== true) {
      return [
        'success' => false,
        'error' => 'Cannot create zip file'
      ];
    }

    $zip->addFile($filepath, basename($filepath));
    $zip->close();

    unlink($filepath);

    return [
      'success' => true,
      'filename' => $zipFilename,
      'filepath' => $zipFilepath,
      'size' => filesize($zipFilepath),
      'formatted_size' => $this->formatBytes(filesize($zipFilepath))
    ];
  }

  public function getDatabaseInfo(): array
  {
    $databaseName = Config::get('database.connections.mysql.database');
    
    $tables = \DB::select('SHOW TABLES');
    $tableCount = count($tables);
    
    $totalSize = \DB::select("
      SELECT 
        SUM(data_length + index_length) as size
      FROM information_schema.TABLES 
      WHERE table_schema = ?
    ", [$databaseName]);

    return [
      'database_name' => $databaseName,
      'table_count' => $tableCount,
      'total_size' => $totalSize[0]->size ?? 0,
      'formatted_size' => $this->formatBytes($totalSize[0]->size ?? 0)
    ];
  }

  public function listExports(): array
  {
    $files = glob($this->exportPath . '/*');
    $exports = [];

    foreach ($files as $file) {
      if (is_file($file)) {
        $exports[] = [
          'filename' => basename($file),
          'size' => filesize($file),
          'formatted_size' => $this->formatBytes(filesize($file)),
          'created_at' => filemtime($file),
          'formatted_date' => date('Y-m-d H:i:s', filemtime($file))
        ];
      }
    }

    usort($exports, function($a, $b) {
      return $b['created_at'] - $a['created_at'];
    });

    return $exports;
  }

  public function deleteExports(array $filenames): int
  {
    $deleted = 0;

    foreach ($filenames as $filename) {
      $filepath = $this->exportPath . '/' . basename($filename);
      
      if (file_exists($filepath) && is_file($filepath)) {
        unlink($filepath);
        $deleted++;
      }
    }

    return $deleted;
  }

  public function deleteAllExports(): int
  {
    $files = glob($this->exportPath . '/*');
    $deleted = 0;

    foreach ($files as $file) {
      if (is_file($file)) {
        unlink($file);
        $deleted++;
      }
    }

    return $deleted;
  }

  public function downloadMultiple(array $filenames): string
  {
    $timestamp = now()->format('Y-m-d_His');
    $zipFilename = "exports_bundle_{$timestamp}.zip";
    $zipFilepath = $this->exportPath . '/' . $zipFilename;

    $zip = new ZipArchive();
    
    if ($zip->open($zipFilepath, ZipArchive::CREATE) !== true) {
      throw new \Exception('Cannot create zip file');
    }

    foreach ($filenames as $filename) {
      $filepath = $this->exportPath . '/' . basename($filename);
      if (file_exists($filepath)) {
        $zip->addFile($filepath, basename($filename));
      }
    }

    $zip->close();

    return $zipFilename;
  }

  public function downloadFile(string $filename): \Symfony\Component\HttpFoundation\BinaryFileResponse
  {
    $filepath = $this->exportPath . '/' . basename($filename);

    if (!file_exists($filepath)) {
      abort(404);
    }

    return response()->download($filepath)->deleteFileAfterSend(false);
  }

  private function formatBytes(int $bytes, int $precision = 2): string
  {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];

    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
      $bytes /= 1024;
    }

    return round($bytes, $precision) . ' ' . $units[$i];
  }
}