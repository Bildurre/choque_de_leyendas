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

  public function restoreDatabase(string $filename): array
  {
    try {
      $filepath = $this->exportPath . '/' . basename($filename);

      if (!file_exists($filepath)) {
        return [
          'success' => false,
          'error' => 'File not found'
        ];
      }

      // Check if it's a ZIP file
      $isZip = pathinfo($filename, PATHINFO_EXTENSION) === 'zip';
      
      if ($isZip) {
        $sqlFilepath = $this->extractSqlFromZip($filepath);
        if (!$sqlFilepath) {
          return [
            'success' => false,
            'error' => 'Cannot extract SQL file from ZIP'
          ];
        }
      } else {
        $sqlFilepath = $filepath;
      }

      // Read SQL file content
      $sql = file_get_contents($sqlFilepath);

      if ($sql === false) {
        return [
          'success' => false,
          'error' => 'Cannot read SQL file'
        ];
      }

      // Disable foreign key checks
      \DB::statement('SET FOREIGN_KEY_CHECKS=0');

      // Execute SQL statements
      \DB::unprepared($sql);

      // Re-enable foreign key checks
      \DB::statement('SET FOREIGN_KEY_CHECKS=1');

      // Clean up temporary extracted file if it was a ZIP
      if ($isZip && $sqlFilepath !== $filepath) {
        unlink($sqlFilepath);
      }

      return [
        'success' => true
      ];

    } catch (\Exception $exception) {
      // Re-enable foreign key checks in case of error
      try {
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');
      } catch (\Exception $e) {
        // Ignore if this fails
      }

      return [
        'success' => false,
        'error' => $exception->getMessage()
      ];
    }
  }

  private function extractSqlFromZip(string $zipFilepath): ?string
  {
    $zip = new ZipArchive();
    
    if ($zip->open($zipFilepath) !== true) {
      return null;
    }

    // Find .sql file in the ZIP
    $sqlFilename = null;
    for ($i = 0; $i < $zip->numFiles; $i++) {
      $filename = $zip->getNameIndex($i);
      if (pathinfo($filename, PATHINFO_EXTENSION) === 'sql') {
        $sqlFilename = $filename;
        break;
      }
    }

    if (!$sqlFilename) {
      $zip->close();
      return null;
    }

    // Extract to temporary location
    $tempPath = $this->exportPath . '/temp_' . time() . '.sql';
    $fileContent = $zip->getFromName($sqlFilename);
    $zip->close();

    if ($fileContent === false) {
      return null;
    }

    file_put_contents($tempPath, $fileContent);

    return $tempPath;
  }

  public function uploadDatabaseFile($file): array
  {
    try {
      $originalName = $file->getClientOriginalName();
      $extension = $file->getClientOriginalExtension();
      
      // Validate extension
      if (!in_array($extension, ['sql', 'zip'])) {
        return [
          'success' => false,
          'error' => 'Invalid file type. Only .sql and .zip files are allowed.'
        ];
      }

      // Generate unique filename
      $timestamp = now()->format('Y-m-d_His');
      $filename = "uploaded_backup_{$timestamp}.{$extension}";
      
      // Move file to exports directory
      $file->move($this->exportPath, $filename);

      return [
        'success' => true,
        'filename' => $filename,
        'filepath' => $this->exportPath . '/' . $filename
      ];

    } catch (\Exception $exception) {
      return [
        'success' => false,
        'error' => $exception->getMessage()
      ];
    }
  }

  public function listExports(string $type = 'all'): array
  {
    $files = glob($this->exportPath . '/*');
    $exports = [];

    foreach ($files as $file) {
      if (is_file($file)) {
        $filename = basename($file);
        
        // Filter by type if specified
        if ($type !== 'all') {
          if ($type === 'database' && !str_contains($filename, 'database_backup') && !str_contains($filename, 'uploaded_backup')) {
            continue;
          }
          if ($type === 'json' && (str_contains($filename, 'database_backup') || str_contains($filename, 'uploaded_backup'))) {
            continue;
          }
        }
        
        $exports[] = [
          'filename' => $filename,
          'size' => filesize($file),
          'formatted_size' => $this->formatBytes(filesize($file)),
          'created_at' => filemtime($file),
          'formatted_date' => date('Y-m-d H:i:s', filemtime($file)),
          'is_database' => str_contains($filename, 'database_backup') || str_contains($filename, 'uploaded_backup'),
          'is_uploaded' => str_contains($filename, 'uploaded_backup')
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

  public function exportCards(): array
  {
    try {
      $cards = \App\Models\Card::with([
        'faction',
        'cardType.heroSuperclass',
        'cardSubtype',
        'equipmentType',
        'attackRange',
        'attackSubtype',
        'heroAbility.attackRange',
        'heroAbility.attackSubtype'
      ])->get();

      $data = $cards->map(function ($card) {
        $cardData = [
          'name' => $card->getTranslations('name'),
          'faction' => $card->faction ? $card->faction->getTranslations('name') : null,
          'is_unique' => $card->is_unique,
          'card_type' => $card->cardType ? $card->cardType->getTranslations('name') : null,
          'card_subtype' => $card->cardSubtype ? $card->cardSubtype->getTranslations('name') : null,
          'equipment_type' => $card->equipmentType ? $card->equipmentType->getTranslations('name') : null,
          'equipment_category' => $card->equipmentType ? $card->equipmentType->category : null,
          'hands' => $card->hands,
          'attack_range' => $card->attackRange ? $card->attackRange->getTranslations('name') : null,
          'attack_type' => $card->attack_type ? $card->attack_type : null,
          'attack_subtype' => $card->attackSubtype ? $card->attackSubtype->getTranslations('name') : null,
          'area' => $card->area,
          'cost' => $card->cost,
          'restriction' => $card->getTranslations('restriction'),
          'effect' => $card->getTranslations('effect'),
          'lore_text' => $card->getTranslations('lore_text'),
          'epic_quote' => $card->getTranslations('epic_quote'),
        ];

        if ($card->heroAbility) {
          $cardData['linked_ability'] = [
            'name' => $card->heroAbility->getTranslations('name'),
            'cost' => $card->heroAbility->cost,
            'attack_range' => $card->heroAbility->attackRange ? $card->heroAbility->attackRange->getTranslations('name') : null,
            'attack_type' => $card->heroAbility->attack_type ? $card->heroAbility->attack_type : null,
            'attack_subtype' => $card->heroAbility->attackSubtype ? $card->heroAbility->attackSubtype->getTranslations('name') : null,
            'area' => $card->heroAbility->area,
            'effect' => $card->heroAbility->getTranslations('description'),
          ];
        }

        return $cardData;
      });

      $timestamp = now()->format('Y-m-d_His');
      $filename = "cards_export_{$timestamp}.json";
      $filepath = $this->exportPath . '/' . $filename;

      file_put_contents($filepath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

      return [
        'success' => true,
        'filename' => $filename,
        'filepath' => $filepath
      ];

    } catch (\Exception $exception) {
      return [
        'success' => false,
        'error' => $exception->getMessage()
      ];
    }
  }

  public function exportHeroes(): array
  {
    try {
      $heroes = \App\Models\Hero::with([
        'faction',
        'heroRace',
        'heroClass.heroSuperclass',
        'heroAbilities.attackRange',
        'heroAbilities.attackSubtype'
      ])->get();

      $data = $heroes->map(function ($hero) {
        return [
          'name' => $hero->getTranslations('name'),
          'faction' => $hero->faction ? $hero->faction->getTranslations('name') : null,
          'race' => $hero->heroRace ? $hero->heroRace->getTranslations('name') : null,
          'class' => $hero->heroClass ? $hero->heroClass->getTranslations('name') : null,
          'superclass' => $hero->heroClass && $hero->heroClass->heroSuperclass ? $hero->heroClass->heroSuperclass->getTranslations('name') : null,
          'gender' => $hero->gender,
          'attributes' => [
            'agility' => $hero->agility,
            'mental' => $hero->mental,
            'will' => $hero->will,
            'strength' => $hero->strength,
            'armor' => $hero->armor,
            'health' => $hero->health,
          ],
          'passive_name' => $hero->getTranslations('passive_name'),
          'passive_description' => $hero->getTranslations('passive_description'),
          'lore_text' => $hero->getTranslations('lore_text'),
          'epic_quote' => $hero->getTranslations('epic_quote'),
          'abilities' => $hero->heroAbilities->map(function ($ability) {
            return [
              'name' => $ability->getTranslations('name'),
              'cost' => $ability->cost,
              'attack_range' => $ability->attackRange ? $ability->attackRange->getTranslations('name') : null,
              'attack_subtype' => $ability->attackSubtype ? $ability->attackSubtype->getTranslations('name') : null,
              'attack_type' => $ability->attack_type ? $ability->attack_type : null,
              'area' => $ability->area,
              'effect' => $ability->getTranslations('description'),
            ];
          })->toArray()
        ];
      });

      $timestamp = now()->format('Y-m-d_His');
      $filename = "heroes_export_{$timestamp}.json";
      $filepath = $this->exportPath . '/' . $filename;

      file_put_contents($filepath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

      return [
        'success' => true,
        'filename' => $filename,
        'filepath' => $filepath
      ];

    } catch (\Exception $exception) {
      return [
        'success' => false,
        'error' => $exception->getMessage()
      ];
    }
  }

  public function exportCounters(): array
  {
    try {
      $counters = \App\Models\Counter::all();

      $data = $counters->map(function ($counter) {
        return [
          'name' => $counter->getTranslations('name'),
          'effect' => $counter->getTranslations('effect'),
          'type' => $counter->type,
        ];
      });

      $timestamp = now()->format('Y-m-d_His');
      $filename = "counters_export_{$timestamp}.json";
      $filepath = $this->exportPath . '/' . $filename;

      file_put_contents($filepath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

      return [
        'success' => true,
        'filename' => $filename,
        'filepath' => $filepath
      ];

    } catch (\Exception $exception) {
      return [
        'success' => false,
        'error' => $exception->getMessage()
      ];
    }
  }

  public function exportClasses(): array
  {
    try {
      $classes = \App\Models\HeroClass::with('heroSuperclass')->get();

      $data = $classes->map(function ($class) {
        return [
          'name' => $class->getTranslations('name'),
          'superclass' => $class->heroSuperclass ? $class->heroSuperclass->getTranslations('name') : null,
          'passive' => $class->getTranslations('passive'),
        ];
      });

      $timestamp = now()->format('Y-m-d_His');
      $filename = "classes_export_{$timestamp}.json";
      $filepath = $this->exportPath . '/' . $filename;

      file_put_contents($filepath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

      return [
        'success' => true,
        'filename' => $filename,
        'filepath' => $filepath
      ];

    } catch (\Exception $exception) {
      return [
        'success' => false,
        'error' => $exception->getMessage()
      ];
    }
  }

  public function exportFaction(\App\Models\Faction $faction): array
  {
    try {
      $factionData = \App\Models\Faction::withoutTrashed()
        ->where('id', $faction->id)
        ->with([
          'heroes' => function ($query) {
            $query->withoutTrashed()->with([
              'heroRace',
              'heroClass.heroSuperclass',
              'heroAbilities' => function ($q) {
                $q->withoutTrashed();
              },
              'heroAbilities.attackRange',
              'heroAbilities.attackSubtype'
            ]);
          },
          'cards' => function ($query) {
            $query->withoutTrashed()->with([
              'cardType.heroSuperclass',
              'cardSubtype',
              'equipmentType',
              'attackRange',
              'attackSubtype',
              'heroAbility' => function ($q) {
                $q->withoutTrashed();
              },
              'heroAbility.attackRange',
              'heroAbility.attackSubtype'
            ]);
          }
        ])
        ->first();

      if (!$factionData) {
        return [
          'success' => false,
          'error' => 'Faction not found'
        ];
      }

      $data = [
        'name' => $factionData->getTranslations('name'),
        'lore_text' => $factionData->getTranslations('lore_text'),
        'epic_quote' => $factionData->getTranslations('epic_quote'),
        'heroes' => $factionData->heroes->map(function ($hero) {
          return [
            'name' => $hero->getTranslations('name'),
            'race' => $hero->heroRace ? $hero->heroRace->getTranslations('name') : null,
            'class' => $hero->heroClass ? $hero->heroClass->getTranslations('name') : null,
            'superclass' => $hero->heroClass && $hero->heroClass->heroSuperclass 
              ? $hero->heroClass->heroSuperclass->getTranslations('name') 
              : null,
            'gender' => $hero->gender,
            'attributes' => [
              'agility' => $hero->agility,
              'mental' => $hero->mental,
              'will' => $hero->will,
              'strength' => $hero->strength,
              'armor' => $hero->armor,
              'health' => $hero->health,
            ],
            'passive_name' => $hero->getTranslations('passive_name'),
            'passive_description' => $hero->getTranslations('passive_description'),
            'lore_text' => $hero->getTranslations('lore_text'),
            'epic_quote' => $hero->getTranslations('epic_quote'),
            'abilities' => $hero->heroAbilities->map(function ($ability) {
              return [
                'name' => $ability->getTranslations('name'),
                'cost' => $ability->cost,
                'attack_range' => $ability->attackRange 
                  ? $ability->attackRange->getTranslations('name') 
                  : null,
                'attack_subtype' => $ability->attackSubtype 
                  ? $ability->attackSubtype->getTranslations('name') 
                  : null,
                'attack_type' => $ability->attack_type ? $ability->attack_type : null,
                'area' => $ability->area,
                'effect' => $ability->getTranslations('description'),
              ];
            })->toArray()
          ];
        })->toArray(),
        'cards' => $factionData->cards->map(function ($card) {
          $cardData = [
            'name' => $card->getTranslations('name'),
            'is_unique' => $card->is_unique,
            'card_type' => $card->cardType 
              ? $card->cardType->getTranslations('name') 
              : null,
            'card_subtype' => $card->cardSubtype 
              ? $card->cardSubtype->getTranslations('name') 
              : null,
            'equipment_type' => $card->equipmentType 
              ? $card->equipmentType->getTranslations('name') 
              : null,
            'equipment_category' => $card->equipmentType 
              ? $card->equipmentType->category 
              : null,
            'hands' => $card->hands,
            'attack_range' => $card->attackRange 
              ? $card->attackRange->getTranslations('name') 
              : null,
            'attack_type' => $card->attack_type ? $card->attack_type : null,
            'attack_subtype' => $card->attackSubtype 
              ? $card->attackSubtype->getTranslations('name') 
              : null,
            'area' => $card->area,
            'cost' => $card->cost,
            'restriction' => $card->getTranslations('restriction'),
            'effect' => $card->getTranslations('effect'),
            'lore_text' => $card->getTranslations('lore_text'),
            'epic_quote' => $card->getTranslations('epic_quote'),
          ];

          if ($card->heroAbility) {
            $cardData['linked_ability'] = [
              'name' => $card->heroAbility->getTranslations('name'),
              'cost' => $card->heroAbility->cost,
              'attack_range' => $card->heroAbility->attackRange 
                ? $card->heroAbility->attackRange->getTranslations('name') 
                : null,
              'attack_type' => $card->heroAbility->attack_type 
                ? $card->heroAbility->attack_type 
                : null,
              'attack_subtype' => $card->heroAbility->attackSubtype 
                ? $card->heroAbility->attackSubtype->getTranslations('name') 
                : null,
              'area' => $card->heroAbility->area,
              'effect' => $card->heroAbility->getTranslations('description'),
            ];
          }

          return $cardData;
        })->toArray()
      ];

      $timestamp = now()->format('Y-m-d_His');
      $factionSlug = \Illuminate\Support\Str::slug($factionData->name);
      $filename = "faction_{$factionSlug}_export_{$timestamp}.json";
      $filepath = $this->exportPath . '/' . $filename;

      file_put_contents(
        $filepath, 
        json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
      );

      return [
        'success' => true,
        'filename' => $filename,
        'filepath' => $filepath
      ];

    } catch (\Exception $exception) {
      return [
        'success' => false,
        'error' => $exception->getMessage()
      ];
    }
  }

  public function exportAllFactions(): array
  {
    try {
      $factions = \App\Models\Faction::withoutTrashed()
        ->orderBy('name')
        ->get();

      if ($factions->isEmpty()) {
        return [
          'success' => false,
          'error' => 'No factions found'
        ];
      }

      $exportedFiles = [];

      foreach ($factions as $faction) {
        $result = $this->exportFaction($faction);
        
        if ($result['success']) {
          $exportedFiles[] = $result['filepath'];
        }
      }

      if (empty($exportedFiles)) {
        return [
          'success' => false,
          'error' => 'No factions were exported'
        ];
      }

      // If only one faction, return it directly
      if (count($exportedFiles) === 1) {
        return [
          'success' => true,
          'filepath' => $exportedFiles[0]
        ];
      }

      // Multiple factions - create ZIP
      $timestamp = now()->format('Y-m-d_His');
      $zipFilename = "all_factions_export_{$timestamp}.zip";
      $zipFilepath = $this->exportPath . '/' . $zipFilename;

      $zip = new ZipArchive();
      
      if ($zip->open($zipFilepath, ZipArchive::CREATE) !== true) {
        return [
          'success' => false,
          'error' => 'Cannot create zip file'
        ];
      }

      foreach ($exportedFiles as $file) {
        if (file_exists($file)) {
          $zip->addFile($file, basename($file));
        }
      }

      $zip->close();

      // Delete individual JSON files after adding to ZIP
      foreach ($exportedFiles as $file) {
        if (file_exists($file)) {
          unlink($file);
        }
      }

      return [
        'success' => true,
        'zip' => true,
        'filename' => $zipFilename,
        'filepath' => $zipFilepath,
        'count' => count($exportedFiles)
      ];

    } catch (\Exception $exception) {
      return [
        'success' => false,
        'error' => $exception->getMessage()
      ];
    }
  }
}