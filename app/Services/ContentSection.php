<?php
namespace App\Services;

use App\Models\ContentSection;
use Illuminate\Support\Str;

class ContentSectionService
{
  public function __construct(
    protected ContentBlockService $blockService
  ) {}

  public function create(array $data): ContentSection
  {
    if (empty($data['anchor_id'])) {
      $data['anchor_id'] = $this->generateAnchorId($data['title']);
    }
    
    return ContentSection::create($data);
  }

  public function update(ContentSection $section, array $data): ContentSection
  {
    if (empty($data['anchor_id']) && !empty($data['title'])) {
      $data['anchor_id'] = $this->generateAnchorId($data['title']);
    }
    
    $section->update($data);
    return $section;
  }

  public function delete(ContentSection $section): bool
  {
    foreach ($section->blocks as $block) {
      $this->blockService->delete($block);
    }
    
    return $section->delete();
  }

  public function reorder(ContentSection $section, int $newOrder): void
  {
    $page = $section->page;
    $sections = $page->sections()->where('id', '!=', $section->id)
      ->orderBy('order')
      ->get();
    
    $sections->splice($newOrder, 0, [$section]);
    
    foreach ($sections as $index => $item) {
      $item->order = $index;
      $item->save();
    }
  }

  protected function generateAnchorId(string $title): string
  {
    $anchor = Str::slug($title);
    
    // Asegurar que el anchor es único
    $count = ContentSection::where('anchor_id', $anchor)->count();
    
    if ($count > 0) {
      $anchor = "{$anchor}-{$count}";
    }
    
    return $anchor;
  }
}