<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Hero;
use App\Models\Card;
use App\Jobs\GeneratePreviewImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PreviewManage extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'preview:manage 
    {action : The action to perform (generate-all, generate, regenerate, clean, status)}
    {--model= : Model type (hero|card) for specific actions}
    {--id= : Model ID for specific actions}
    {--force : Force regeneration even if preview exists}
    {--sync : Execute jobs synchronously instead of queuing}
    {--dry-run : Show what would be done without actually doing it}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Manage preview images for heroes and cards';

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle()
  {
    $action = $this->argument('action');
    
    switch ($action) {
      case 'generate-all':
        return $this->generateAll();
        
      case 'generate':
        return $this->generateSpecific();
        
      case 'regenerate':
        return $this->regenerateAll();
        
      case 'clean':
        return $this->cleanOrphaned();
        
      case 'status':
        return $this->showStatus();
        
      default:
        $this->error("Unknown action: {$action}");
        $this->line('Available actions: generate-all, generate, regenerate, clean, status');
        return 1;
    }
  }
  
  /**
   * Generate all missing preview images
   */
  protected function generateAll(): int
  {
    $force = $this->option('force');
    $sync = $this->option('sync');
    $dryRun = $this->option('dry-run');
    
    $this->info('Generating missing preview images...');
    
    $heroesCount = 0;
    $cardsCount = 0;
    
    // Process Heroes
    $heroes = Hero::all();
    $bar = $this->output->createProgressBar($heroes->count());
    $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% Heroes');
    
    foreach ($heroes as $hero) {
      $locales = array_keys(config('laravellocalization.supportedLocales', ['es' => []]));
      $needsGeneration = false;
      
      foreach ($locales as $locale) {
        if ($force || !$hero->hasPreviewImage($locale)) {
          $needsGeneration = true;
          break;
        }
      }
      
      if ($needsGeneration) {
        if (!$dryRun) {
          if ($sync) {
            $job = new GeneratePreviewImage($hero);
            $job->handle();
          } else {
            GeneratePreviewImage::dispatch($hero);
          }
        }
        $heroesCount++;
      }
      
      $bar->advance();
    }
    
    $bar->finish();
    $this->newLine();
    
    // Process Cards
    $cards = Card::all();
    $bar = $this->output->createProgressBar($cards->count());
    $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% Cards');
    
    foreach ($cards as $card) {
      $locales = array_keys(config('laravellocalization.supportedLocales', ['es' => []]));
      $needsGeneration = false;
      
      foreach ($locales as $locale) {
        if ($force || !$card->hasPreviewImage($locale)) {
          $needsGeneration = true;
          break;
        }
      }
      
      if ($needsGeneration) {
        if (!$dryRun) {
          if ($sync) {
            $job = new GeneratePreviewImage($card);
            $job->handle();
          } else {
            GeneratePreviewImage::dispatch($card);
          }
        }
        $cardsCount++;
      }
      
      $bar->advance();
    }
    
    $bar->finish();
    $this->newLine(2);
    
    if ($dryRun) {
      $this->warn('DRY RUN - No images were generated');
    }
    
    $this->info("Heroes needing generation: {$heroesCount}");
    $this->info("Cards needing generation: {$cardsCount}");
    
    if (!$sync && !$dryRun && ($heroesCount + $cardsCount) > 0) {
      $this->info('Jobs have been queued. Run queue:work to process them.');
    }
    
    return 0;
  }
  
  /**
   * Generate preview for specific model
   */
  protected function generateSpecific(): int
  {
    $modelType = $this->option('model');
    $id = $this->option('id');
    $sync = $this->option('sync');
    $dryRun = $this->option('dry-run');
    
    if (!$modelType || !$id) {
      $this->error('Both --model and --id options are required for generate action');
      return 1;
    }
    
    if (!in_array($modelType, ['hero', 'card'])) {
      $this->error('Model must be either "hero" or "card"');
      return 1;
    }
    
    $model = $modelType === 'hero' 
      ? Hero::find($id)
      : Card::find($id);
      
    if (!$model) {
      $this->error("No {$modelType} found with ID {$id}");
      return 1;
    }
    
    $this->info("Generating preview images for {$modelType}: {$model->name}");
    
    if (!$dryRun) {
      if ($sync) {
        $job = new GeneratePreviewImage($model);
        $job->handle();
        $this->info('Preview images generated successfully!');
      } else {
        GeneratePreviewImage::dispatch($model);
        $this->info('Job queued successfully!');
      }
    } else {
      $this->warn('DRY RUN - No images were generated');
    }
    
    return 0;
  }
  
  /**
   * Regenerate all preview images (force)
   */
  protected function regenerateAll(): int
  {
    // Force the --force option to be true
    $this->input->setOption('force', true);
    return $this->generateAll();
  }
  
  /**
   * Clean orphaned preview images
   */
  protected function cleanOrphaned(): int
  {
    $dryRun = $this->option('dry-run');
    
    $this->info('Scanning for orphaned preview images...');
    
    $orphanedFiles = [];
    $validPaths = [];
    
    // Collect all valid preview paths
    foreach (Hero::withTrashed()->get() as $hero) {
      $images = $hero->getAllPreviewImages();
      foreach ($images as $path) {
        $validPaths[] = $path;
      }
    }
    
    foreach (Card::withTrashed()->get() as $card) {
      $images = $card->getAllPreviewImages();
      foreach ($images as $path) {
        $validPaths[] = $path;
      }
    }
    
    // Scan directories for orphaned files
    $directories = [
      'images/previews/heroes',
      'images/previews/cards'
    ];
    
    foreach ($directories as $dir) {
      $locales = array_keys(config('laravellocalization.supportedLocales', ['es' => []]));
      
      foreach ($locales as $locale) {
        $localePath = $dir . '/' . $locale;
        
        if (Storage::disk('public')->exists($localePath)) {
          $files = Storage::disk('public')->files($localePath);
          
          foreach ($files as $file) {
            if (!in_array($file, $validPaths)) {
              $orphanedFiles[] = $file;
            }
          }
        }
      }
    }
    
    if (empty($orphanedFiles)) {
      $this->info('No orphaned files found!');
      return 0;
    }
    
    $this->warn('Found ' . count($orphanedFiles) . ' orphaned files:');
    foreach ($orphanedFiles as $file) {
      $this->line('  - ' . $file);
    }
    
    if (!$dryRun) {
      if ($this->confirm('Do you want to delete these files?')) {
        foreach ($orphanedFiles as $file) {
          Storage::disk('public')->delete($file);
        }
        $this->info('Orphaned files deleted successfully!');
      }
    } else {
      $this->warn('DRY RUN - No files were deleted');
    }
    
    return 0;
  }
  
  /**
   * Show preview generation status
   */
  protected function showStatus(): int
  {
    $locales = array_keys(config('laravellocalization.supportedLocales', ['es' => []]));
    
    // Heroes status
    $this->info('=== HEROES STATUS ===');
    $heroes = Hero::all();
    $heroStats = [
      'total' => $heroes->count(),
      'complete' => 0,
      'partial' => 0,
      'missing' => 0
    ];
    
    foreach ($heroes as $hero) {
      $hasAll = true;
      $hasAny = false;
      
      foreach ($locales as $locale) {
        if ($hero->hasPreviewImage($locale)) {
          $hasAny = true;
        } else {
          $hasAll = false;
        }
      }
      
      if ($hasAll) {
        $heroStats['complete']++;
      } elseif ($hasAny) {
        $heroStats['partial']++;
      } else {
        $heroStats['missing']++;
      }
    }
    
    $this->table(
      ['Status', 'Count', 'Percentage'],
      [
        ['Complete', $heroStats['complete'], $this->percentage($heroStats['complete'], $heroStats['total'])],
        ['Partial', $heroStats['partial'], $this->percentage($heroStats['partial'], $heroStats['total'])],
        ['Missing', $heroStats['missing'], $this->percentage($heroStats['missing'], $heroStats['total'])],
        ['Total', $heroStats['total'], '100%'],
      ]
    );
    
    // Cards status
    $this->newLine();
    $this->info('=== CARDS STATUS ===');
    $cards = Card::all();
    $cardStats = [
      'total' => $cards->count(),
      'complete' => 0,
      'partial' => 0,
      'missing' => 0
    ];
    
    foreach ($cards as $card) {
      $hasAll = true;
      $hasAny = false;
      
      foreach ($locales as $locale) {
        if ($card->hasPreviewImage($locale)) {
          $hasAny = true;
        } else {
          $hasAll = false;
        }
      }
      
      if ($hasAll) {
        $cardStats['complete']++;
      } elseif ($hasAny) {
        $cardStats['partial']++;
      } else {
        $cardStats['missing']++;
      }
    }
    
    $this->table(
      ['Status', 'Count', 'Percentage'],
      [
        ['Complete', $cardStats['complete'], $this->percentage($cardStats['complete'], $cardStats['total'])],
        ['Partial', $cardStats['partial'], $this->percentage($cardStats['partial'], $cardStats['total'])],
        ['Missing', $cardStats['missing'], $this->percentage($cardStats['missing'], $cardStats['total'])],
        ['Total', $cardStats['total'], '100%'],
      ]
    );
    
    // Disk usage
    $this->newLine();
    $this->info('=== DISK USAGE ===');
    
    $heroesSize = $this->getDirectorySize('images/previews/heroes');
    $cardsSize = $this->getDirectorySize('images/previews/cards');
    $totalSize = $heroesSize + $cardsSize;
    
    $this->table(
      ['Type', 'Size'],
      [
        ['Heroes', $this->formatBytes($heroesSize)],
        ['Cards', $this->formatBytes($cardsSize)],
        ['Total', $this->formatBytes($totalSize)],
      ]
    );
    
    return 0;
  }
  
  /**
   * Calculate percentage
   */
  protected function percentage($value, $total): string
  {
    if ($total == 0) return '0%';
    return round(($value / $total) * 100, 1) . '%';
  }
  
  /**
   * Get directory size
   */
  protected function getDirectorySize($directory): int
  {
    $size = 0;
    $files = Storage::disk('public')->allFiles($directory);
    
    foreach ($files as $file) {
      $size += Storage::disk('public')->size($file);
    }
    
    return $size;
  }
  
  /**
   * Format bytes to human readable
   */
  protected function formatBytes($bytes, $precision = 2): string
  {
    $units = ['B', 'KB', 'MB', 'GB'];
    
    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
      $bytes /= 1024;
    }
    
    return round($bytes, $precision) . ' ' . $units[$i];
  }
}