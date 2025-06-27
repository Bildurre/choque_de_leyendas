<?php

namespace App\Services\PdfExport\Generators;

use App\Models\FactionDeck;

class DeckPdfGenerator extends BasePdfGenerator
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
    $slug = \Str::slug($data['deck_name'] ?? 'deck');
    $date = now()->format('Y-m-d');
    
    return "alanda-{$slug}-{$date}.pdf";
  }
  
  /**
   * Prepare view data
   */
  protected function prepareViewData(array $data): array
  {
    $deck = FactionDeck::findOrFail($data['deck_id']);
    $items = [];
    
    // Get heroes with their specific copies
    $heroes = $deck->heroes()
      ->with($this->getHeroRelations())
      ->get();
    
    foreach ($heroes as $hero) {
      $copies = $hero->pivot->copies;
      for ($i = 0; $i < $copies; $i++) {
        $items[] = [
          'type' => 'hero',
          'entity' => $hero,
        ];
      }
    }
    
    // Get cards with their specific copies
    $cards = $deck->cards()
      ->with($this->getCardRelations())
      ->get();
    
    foreach ($cards as $card) {
      $copies = $card->pivot->copies;
      for ($i = 0; $i < $copies; $i++) {
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
      'deck' => $deck,
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