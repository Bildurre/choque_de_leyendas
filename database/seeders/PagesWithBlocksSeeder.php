<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Block;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class PagesWithBlocksSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Read JSON files
    $pagesJson = File::get(database_path('data/pages.json'));
    $pagesData = json_decode($pagesJson, true);
    
    $blocksJson = File::get(database_path('data/blocks.json'));
    $blocksData = json_decode($blocksJson, true);
    
    // Store created pages for parent reference
    $createdPages = [];
    
    // Create pages
    foreach ($pagesData as $index => $data) {
      $page = new Page();
      
      $page->title = $data['title'];
      $page->description = $data['description'] ?? null;
      $page->is_published = $data['is_published'] ?? false;
      $page->is_printable = $data['is_printable'] ?? false;
      $page->is_home = $data['is_home'] ?? false;
      $page->meta_title = $data['meta_title'] ?? null;
      $page->meta_description = $data['meta_description'] ?? null;
      $page->template = $data['template'] ?? 'default';
      $page->order = $data['order'] ?? 0;
      $page->background_image = $data['background_image'] ?? null;
      
      // Handle parent reference
      if (isset($data['parent_reference'])) {
        $parentIndex = $data['parent_reference'];
        if (isset($createdPages[$parentIndex])) {
          $page->parent_id = $createdPages[$parentIndex]->id;
        }
      } else {
        $page->parent_id = $data['parent_id'] ?? null;
      }
      
      $page->save();
      
      // Store reference for potential children
      $createdPages[$index] = $page;
    }
    
    // Create blocks
    foreach ($blocksData as $blockData) {
      // Get the page reference
      if (isset($blockData['page_reference']) && isset($createdPages[$blockData['page_reference']])) {
        $block = new Block();
        
        $block->page_id = $createdPages[$blockData['page_reference']]->id;
        $block->type = $blockData['type'];
        $block->title = $blockData['title'] ?? null;
        $block->subtitle = $blockData['subtitle'] ?? null;
        $block->content = $blockData['content'] ?? null;
        $block->order = $blockData['order'] ?? 0;
        $block->is_printable = $blockData['is_printable'] ?? false;
        $block->background_color = $blockData['background_color'] ?? 'none';
        $block->settings = $blockData['settings'] ?? null;
        $block->image = $blockData['image'] ?? null;
        
        $block->save();
      }
    }
    
    $this->command->info("Pages and blocks created successfully.");
  }
}