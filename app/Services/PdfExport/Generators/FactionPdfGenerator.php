<?php

namespace App\Services\PdfExport\Generators;

use App\Models\Faction;

class FactionPdfGenerator extends BasePdfGenerator
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
    return 'pdfs.collection'; // Reuse the same template
  }
  
  /**
   * Generate filename
   */
  protected function generateFilename(array $data): string
  {
    $slug = \Str::slug($data['faction_name'] ?? 'faction');
    $date = now()->format('Y-m-d');
    
    return "alanda-{$slug}-{$date}.pdf";
  }
  
  /**
   * Prepare view data
   */
  protected function prepareViewData(array $data): array
  {
    $faction = Faction::findOrFail($data['faction_id']);
    $items = [];
    
    // Get all heroes (1 copy each)
    $heroes = $faction->heroes()->published()
      ->with($this->getHeroRelations())
      ->get();
    
    foreach ($heroes as $hero) {
      $items[] = [
        'type' => 'hero',
        'entity' => $hero,
      ];
    }
    
    // Get all cards (2 copies each)
    $cards = $faction->cards()->published()
      ->with($this->getCardRelations())
      ->get();
    
    foreach ($cards as $card) {
      for ($i = 0; $i < 2; $i++) {
        $items[] = [
          'type' => 'card',
          'entity' => $card,
        ];
      }
    }
    
    return [
      'items' => $items,
      'reduceHeroes' => $data['reduce_heroes'] ?? false,
      'withGap' => $data['with_gap'] ?? true,
      'totalCopies' => count($items),
      'faction' => $faction,
      'generatedAt' => now(),
    ];
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