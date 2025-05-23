<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Hero;
use App\Models\Card;
use App\Jobs\GeneratePreviewImage;

class PreviewDebug extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'preview:debug {model} {id} {locale=es}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Generate a debug HTML file for preview testing';

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle()
  {
    $modelType = $this->argument('model');
    $id = $this->argument('id');
    $locale = $this->argument('locale');
    
    // Get the model
    if ($modelType === 'hero') {
      $model = Hero::findOrFail($id);
    } elseif ($modelType === 'card') {
      $model = Card::findOrFail($id);
    } else {
      $this->error('Model type must be "hero" or "card"');
      return 1;
    }
    
    // Create job instance
    $job = new GeneratePreviewImage($model, [$locale]);
    
    // Use reflection to access protected methods
    $reflection = new \ReflectionMethod($job, 'generatePreviewHtml');
    $reflection->setAccessible(true);
    $html = $reflection->invoke($job, $locale);
    
    // Save to file
    $filename = storage_path("app/preview-debug-{$modelType}-{$id}-{$locale}.html");
    file_put_contents($filename, $html);
    
    $this->info("Debug HTML saved to: {$filename}");
    $this->info("You can open this file in your browser to check the preview.");
    
    return 0;
  }
}