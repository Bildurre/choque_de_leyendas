<?php
namespace App\Services\Public;

use App\Models\ContentPage;

class ContentPageService
{
  public function getPublishedBySlug(string $slug): ?ContentPage
  {
    return ContentPage::where('slug', $slug)
      ->where('is_published', true)
      ->with(['sections' => function($query) {
        $query->orderBy('order')->with(['blocks' => function($blockQuery) {
          $blockQuery->orderBy('order');
        }]);
      }])
      ->first();
  }

  public function getPublishedHome(): ?ContentPage
  {
    return ContentPage::where('type', 'home')
      ->where('is_published', true)
      ->with(['sections' => function($query) {
        $query->orderBy('order')->with(['blocks' => function($blockQuery) {
          $blockQuery->orderBy('order');
        }]);
      }])
      ->first();
  }

  public function getPublishedRulesPage(): ?ContentPage
  {
    return ContentPage::where('type', 'rules')
      ->where('is_published', true)
      ->with(['sections' => function($query) {
        $query->orderBy('order')->with(['blocks' => function($blockQuery) {
          $blockQuery->orderBy('order');
        }]);
      }])
      ->first();
  }

  public function getPublishedComponentsPage(): ?ContentPage
  {
    return ContentPage::where('type', 'components')
      ->where('is_published', true)
      ->with(['sections' => function($query) {
        $query->orderBy('order')->with(['blocks' => function($blockQuery) {
          $blockQuery->orderBy('order');
        }]);
      }])
      ->first();
  }

  public function getPublishedAnnexesPage(): ?ContentPage
  {
    return ContentPage::where('type', 'annexes')
      ->where('is_published', true)
      ->with(['sections' => function($query) {
        $query->orderBy('order')->with(['blocks' => function($blockQuery) {
          $blockQuery->orderBy('order');
        }]);
      }])
      ->first();
  }

  public function getAllPublishedPages(): \Illuminate\Database\Eloquent\Collection
  {
    return ContentPage::where('is_published', true)
      ->select(['id', 'title', 'slug', 'type', 'meta_description'])
      ->orderBy('order')
      ->get();
  }
}