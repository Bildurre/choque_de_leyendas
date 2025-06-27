<?php

namespace App\Services\PdfExport\Generators;

use App\Models\Card;
use App\Models\Hero;
use App\Models\Faction;
use App\Models\FactionDeck;

class CollectionPdfGenerator extends BasePdfGenerator
{
  /**
   * Generate the PDF
   */
  public function generate(array $data): array
  {
    return $this->generatePdf($data, [
      'paper' => 'a4',
      'orientation' => 'portrait',
    ]);
  }
  
  /**
   * Get the view name
   */
  protected function getViewName(): string
  {
    return 'pdfs.collection';
  }
  
  /**
   * Generate filename
   */
  protected function generateFilename(array $data): string
  {
    $date = now()->format('Y-m-d');
    return "alanda-collection-{$date}.pdf";
  }
  
  /**
   * Prepare view data
   */
  protected function prepareViewData(array $data): array
  {
    $items = $this->prepareCollectionItems($data['collection']);
    
    return [
      'items' => $items,
      'reduceHeroes' => $data['reduce_heroes'] ?? false,
      'withGap' => $data['with_gap'] ?? true,
      'totalCopies' => count($items),
      'generatedAt' => now(),
    ];
  }
  
  /**
   * Prepare items from a collection session
   */
  protected function prepareCollectionItems(array $collection): array
  {
    $items = [];
    
    // Load heroes
    $heroIds = array_column($collection['heroes'] ?? [], 'id');
    $heroes = Hero::whereIn('id', $heroIds)
      ->with($this->getHeroRelations())
      ->get()
      ->keyBy('id');
    
    // Add heroes with copies
    foreach ($collection['heroes'] ?? [] as $heroData) {
      $hero = $heroes->get($heroData['id']);
      if ($hero) {
        for ($i = 0; $i < $heroData['copies']; $i++) {
          $items[] = [
            'type' => 'hero',
            'entity' => $hero,
          ];
        }
      }
    }
    
    // Load cards
    $cardIds = array_column($collection['cards'] ?? [], 'id');
    $cards = Card::whereIn('id', $cardIds)
      ->with($this->getCardRelations())
      ->get()
      ->keyBy('id');
    
    // Add cards with copies
    foreach ($collection['cards'] ?? [] as $cardData) {
      $card = $cards->get($cardData['id']);
      if ($card) {
        for ($i = 0; $i < $cardData['copies']; $i++) {
          $items[] = [
            'type' => 'card',
            'entity' => $card,
          ];
        }
      }
    }
    
    return $items;
  }
  
  /**
   * Get hero relations to load
   */
  protected function getHeroRelations(): array
  {
    return [
      'faction',
      'heroClass.heroSuperclass',
      'heroRace',
      'heroAbilities.attackRange',
      'heroAbilities.attackSubtype',
    ];
  }
  
  /**
   * Get card relations to load
   */
  protected function getCardRelations(): array
  {
    return [
      'faction',
      'cardType.heroSuperclass',
      'equipmentType',
      'attackRange',
      'attackSubtype',
      'heroAbility.attackRange',
      'heroAbility.attackSubtype',
    ];
  }
}