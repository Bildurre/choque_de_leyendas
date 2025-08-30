<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Hero;
use App\Models\Card;
use App\Models\Faction;
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
    {action : The action to perform (generate-all, generate, regenerate, clean, status, generate-faction, regenerate-faction, delete, delete-all, delete-heroes, delete-cards, delete-faction)}
    {--model= : Model type (hero|card) for specific actions}
    {--id= : Model ID for specific actions}
    {--faction= : Faction ID for faction-specific actions}
    {--type= : Type filter for faction actions (all|heroes|cards)}
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
        
      case 'generate-faction':
        return $this->generateFaction();
        
      case 'regenerate-faction':
        return $this->regenerateFaction();
        
      case 'delete':
        return $this->deleteSpecific();
        
      case 'delete-all':
        return $this->deleteAll();
        
      case 'delete-heroes':
        return $this->deleteAllHeroes();
        
      case 'delete-cards':
        return $this->deleteAllCards();
        
      case 'delete-faction':
        return $this->deleteFaction();
        
      default:
        $this->error("Unknown action: {$action}");
        $this->line('Available actions: generate-all, generate, regenerate, clean, status, generate-faction, regenerate-faction, delete, delete-all, delete-heroes, delete-cards, delete-faction');
        return 1;
    }
  }
  
  /**
   * Generate preview images for a faction
   */
  protected function generateFaction(): int
  {
    $factionId = $this->option('faction');
    $type = $this->option('type') ?? 'all';
    $force = $this->option('force');
    $sync = $this->option('sync');
    $dryRun = $this->option('dry-run');
    
    if (!$factionId) {
      $this->error('--faction option is required for generate-faction action');
      return 1;
    }
    
    $faction = Faction::find($factionId);
    if (!$faction) {
      $this->error("No faction found with ID {$factionId}");
      return 1;
    }
    
    if (!in_array($type, ['all', 'heroes', 'cards'])) {
      $this->error('--type must be one of: all, heroes, cards');
      return 1;
    }
    
    $this->info("Generating preview images for faction: {$faction->name}");
    
    $heroesCount = 0;
    $cardsCount = 0;
    $locales = array_keys(config('laravellocalization.supportedLocales', ['es' => []]));
    
    // Process Heroes
    if ($type === 'all' || $type === 'heroes') {
      $heroes = $faction->heroes;
      
      if ($heroes->count() > 0) {
        $bar = $this->output->createProgressBar($heroes->count());
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% Heroes');
        
        foreach ($heroes as $hero) {
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
      }
    }
    
    // Process Cards
    if ($type === 'all' || $type === 'cards') {
      $cards = $faction->cards;
      
      if ($cards->count() > 0) {
        $bar = $this->output->createProgressBar($cards->count());
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% Cards');
        
        foreach ($cards as $card) {
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
        $this->newLine();
      }
    }
    
    $this->newLine();
    
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
   * Regenerate preview images for a faction (force)
   */
  protected function regenerateFaction(): int
  {
    $this->input->setOption('force', true);
    return $this->generateFaction();
  }
  
  /**
   * Delete preview images for specific model
   */
  protected function deleteSpecific(): int
  {
    $modelType = $this->option('model');
    $id = $this->option('id');
    $dryRun = $this->option('dry-run');
    
    if (!$modelType || !$id) {
      $this->error('Both --model and --id options are required for delete action');
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
    
    $this->info("Deleting preview images for {$modelType}: {$model->name}");
    
    $images = $model->getAllPreviewImages();
    $deletedCount = 0;
    
    foreach ($images as $imagePath) {
      if (Storage::disk('public')->exists($imagePath)) {
        if (!$dryRun) {
          Storage::disk('public')->delete($imagePath);
        }
        $deletedCount++;
        $this->line("  - Deleted: {$imagePath}");
      }
    }
    
    if ($dryRun) {
      $this->warn('DRY RUN - No images were deleted');
    } else {
      $this->info("Deleted {$deletedCount} preview images");
    }
    
    return 0;
  }
  
  /**
   * Delete all preview images
   */
  protected function deleteAll(): int
  {
    $dryRun = $this->option('dry-run');
    
    $this->warn('This will delete ALL preview images!');
    
    if (!$dryRun && !$this->option('no-interaction')) {
      if (!$this->confirm('Are you sure you want to delete ALL preview images?')) {
        $this->info('Operation cancelled');
        return 0;
      }
    }
    
    $directories = [
      'images/previews/heroes',
      'images/previews/cards'
    ];
    
    $deletedCount = 0;
    
    foreach ($directories as $dir) {
      if (Storage::disk('public')->exists($dir)) {
        $files = Storage::disk('public')->allFiles($dir);
        $deletedCount += count($files);
        
        if (!$dryRun) {
          Storage::disk('public')->deleteDirectory($dir);
          Storage::disk('public')->makeDirectory($dir);
        }
      }
    }
    
    if ($dryRun) {
      $this->warn("DRY RUN - Would delete {$deletedCount} files");
    } else {
      $this->info("Deleted {$deletedCount} preview images");
    }
    
    return 0;
  }
  
  /**
   * Delete all hero preview images
   */
  protected function deleteAllHeroes(): int
  {
    $dryRun = $this->option('dry-run');
    
    $this->warn('This will delete all HERO preview images!');
    
    if (!$dryRun && !$this->option('no-interaction')) {
      if (!$this->confirm('Are you sure you want to delete all hero preview images?')) {
        $this->info('Operation cancelled');
        return 0;
      }
    }
    
    $directory = 'images/previews/heroes';
    $deletedCount = 0;
    
    if (Storage::disk('public')->exists($directory)) {
      $files = Storage::disk('public')->allFiles($directory);
      $deletedCount = count($files);
      
      if (!$dryRun) {
        Storage::disk('public')->deleteDirectory($directory);
        Storage::disk('public')->makeDirectory($directory);
      }
    }
    
    if ($dryRun) {
      $this->warn("DRY RUN - Would delete {$deletedCount} hero preview images");
    } else {
      $this->info("Deleted {$deletedCount} hero preview images");
    }
    
    return 0;
  }
  
  /**
   * Delete all card preview images
   */
  protected function deleteAllCards(): int
  {
    $dryRun = $this->option('dry-run');
    
    $this->warn('This will delete all CARD preview images!');
    
    if (!$dryRun && !$this->option('no-interaction')) {
      if (!$this->confirm('Are you sure you want to delete all card preview images?')) {
        $this->info('Operation cancelled');
        return 0;
      }
    }
    
    $directory = 'images/previews/cards';
    $deletedCount = 0;
    
    if (Storage::disk('public')->exists($directory)) {
      $files = Storage::disk('public')->allFiles($directory);
      $deletedCount = count($files);
      
      if (!$dryRun) {
        Storage::disk('public')->deleteDirectory($directory);
        Storage::disk('public')->makeDirectory($directory);
      }
    }
    
    if ($dryRun) {
      $this->warn("DRY RUN - Would delete {$deletedCount} card preview images");
    } else {
      $this->info("Deleted {$deletedCount} card preview images");
    }
    
    return 0;
  }
  
  /**
   * Delete preview images for a faction
   */
  protected function deleteFaction(): int
  {
    $factionId = $this->option('faction');
    $type = $this->option('type') ?? 'all';
    $dryRun = $this->option('dry-run');
    
    if (!$factionId) {
      $this->error('--faction option is required for delete-faction action');
      return 1;
    }
    
    $faction = Faction::find($factionId);
    if (!$faction) {
      $this->error("No faction found with ID {$factionId}");
      return 1;
    }
    
    if (!in_array($type, ['all', 'heroes', 'cards'])) {
      $this->error('--type must be one of: all, heroes, cards');
      return 1;
    }
    
    $this->warn("This will delete preview images for faction: {$faction->name}");
    
    if (!$dryRun && !$this->option('no-interaction')) {
        if (!$this->confirm('Are you sure you want to continue?')) {
            $this->info('Operation cancelled');
            return 0;
        }
    }
    
    $deletedCount = 0;
    
    // Delete Heroes previews
    if ($type === 'all' || $type === 'heroes') {
      foreach ($faction->heroes as $hero) {
        $images = $hero->getAllPreviewImages();
        foreach ($images as $imagePath) {
          if (Storage::disk('public')->exists($imagePath)) {
            if (!$dryRun) {
              Storage::disk('public')->delete($imagePath);
            }
            $deletedCount++;
          }
        }
      }
    }
    
    // Delete Cards previews
    if ($type === 'all' || $type === 'cards') {
      foreach ($faction->cards as $card) {
        $images = $card->getAllPreviewImages();
        foreach ($images as $imagePath) {
          if (Storage::disk('public')->exists($imagePath)) {
            if (!$dryRun) {
              Storage::disk('public')->delete($imagePath);
            }
            $deletedCount++;
          }
        }
      }
    }
    
    if ($dryRun) {
      $this->warn("DRY RUN - Would delete {$deletedCount} preview images");
    } else {
      $this->info("Deleted {$deletedCount} preview images from faction {$faction->name}");
    }
    
    return 0;
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