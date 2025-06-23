<?php

namespace App\Services\Public;

use App\Models\Card;
use App\Models\Hero;
use App\Models\Faction;
use App\Models\FactionDeck;
use Illuminate\Support\Collection;
use Barryvdh\DomPDF\Facade\Pdf;

class PrintCollectionService
{
  /**
   * Add a hero to the collection
   */
  public function addHero(int $id, array $collection): array
  {
    $hero = Hero::published()->findOrFail($id);
    $key = 'hero_' . $hero->id;

    if (isset($collection['heroes'][$key])) {
      $collection['heroes'][$key]['copies']++;
    } else {
      $collection['heroes'][$key] = [
        'id' => $id,
        'copies' => 1,
        'name' => $hero->name
      ];
    }

    return $collection;
  }

  /**
   * Add a card to the collection
   */
  public function addCard(int $id, array $collection): array
  {
    $card = Card::published()->findOrFail($id);
    $key = 'card_' . $card->id;

    if (isset($collection['cards'][$key])) {
      $collection['cards'][$key]['copies']++;
    } else {
      $collection['cards'][$key] = [
        'id' => $id,
        'copies' => 1,
        'name' => $card->name
      ];
    }

    return $collection;
  }

  /**
   * Add all items from a faction to the collection
   */
  public function addFaction(int $id, array $collection): array
  {
    $faction = Faction::published()->findOrFail($id);
    
    // Add all heroes from faction
    foreach ($faction->heroes()->published()->get() as $hero) {
      $key = 'hero_' . $hero->id;
      if (isset($collection['heroes'][$key])) {
        $collection['heroes'][$key]['copies']++;
      } else {
        $collection['heroes'][$key] = [
          'id' => $hero->id,
          'copies' => 1,
          'name' => $hero->name
        ];
      }
    }

    // Add all cards from faction
    foreach ($faction->cards()->published()->get() as $card) {
      $key = 'card_' . $card->id;
      if (isset($collection['cards'][$key])) {
        $collection['cards'][$key]['copies']++;
      } else {
        $collection['cards'][$key] = [
          'id' => $card->id,
          'copies' => 1,
          'name' => $card->name
        ];
      }
    }

    return $collection;
  }

  /**
   * Add all items from a deck to the collection
   */
  public function addDeck(int $id, array $collection): array
  {
    $deck = FactionDeck::published()->findOrFail($id);
    
    // Add heroes with their copies
    foreach ($deck->heroes as $hero) {
      $key = 'hero_' . $hero->id;
      $copiesToAdd = $hero->pivot->copies;
      
      if (isset($collection['heroes'][$key])) {
        $collection['heroes'][$key]['copies'] += $copiesToAdd;
      } else {
        $collection['heroes'][$key] = [
          'id' => $hero->id,
          'copies' => $copiesToAdd,
          'name' => $hero->name
        ];
      }
    }

    // Add cards with their copies
    foreach ($deck->cards as $card) {
      $key = 'card_' . $card->id;
      $copiesToAdd = $card->pivot->copies;
      
      if (isset($collection['cards'][$key])) {
        $collection['cards'][$key]['copies'] += $copiesToAdd;
      } else {
        $collection['cards'][$key] = [
          'id' => $card->id,
          'copies' => $copiesToAdd,
          'name' => $card->name
        ];
      }
    }

    return $collection;
  }

  /**
   * Update the quantity of an item in the collection
   */
  public function updateItemQuantity(string $type, int $id, int $copies, array $collection): array
  {
    $key = $type . '_' . $id;
    $collectionType = $type === 'hero' ? 'heroes' : 'cards';

    if (isset($collection[$collectionType][$key])) {
      $collection[$collectionType][$key]['copies'] = $copies;
    }

    return $collection;
  }

  /**
   * Remove an item from the collection
   */
  public function removeItem(string $type, int $id, array $collection): array
  {
    $key = $type . '_' . $id;
    $collectionType = $type === 'hero' ? 'heroes' : 'cards';

    if (isset($collection[$collectionType][$key])) {
      unset($collection[$collectionType][$key]);
    }

    return $collection;
  }

  /**
   * Get the total count of items in the collection
   */
  public function getTotalCount(array $collection): int
  {
    return count($collection['heroes']) + count($collection['cards']);
  }

  /**
   * Get the total number of copies in the collection
   */
  public function getTotalCopies(array $collection): int
  {
    $heroCopies = collect($collection['heroes'])->sum('copies');
    $cardCopies = collect($collection['cards'])->sum('copies');
    
    return $heroCopies + $cardCopies;
  }

  /**
   * Get success message for adding an item
   */
  public function getSuccessMessage(string $type): string
  {
    return match($type) {
      'hero' => __('public.hero_added_to_collection'),
      'card' => __('public.card_added_to_collection'),
      'faction' => __('public.faction_added_to_collection'),
      'deck' => __('public.deck_added_to_collection'),
      default => __('public.added_to_collection')
    };
  }

  /**
   * Load models for the collection
   */
  public function loadCollectionModels(array $collection): array
  {
    $heroIds = array_map(fn($item) => $item['id'], $collection['heroes']);
    $cardIds = array_map(fn($item) => $item['id'], $collection['cards']);

    $heroes = Hero::with([
      'faction',
      'heroClass.heroSuperclass',
      'heroRace',
      'heroAbilities.attackRange',
      'heroAbilities.attackSubtype'
    ])
    ->whereIn('id', $heroIds)
    ->get()
    ->keyBy('id');

    $cards = Card::with([
      'faction',
      'cardType.heroSuperclass',
      'equipmentType',
      'attackRange',
      'attackSubtype',
      'heroAbility.attackRange',
      'heroAbility.attackSubtype'
    ])
    ->whereIn('id', $cardIds)
    ->get()
    ->keyBy('id');

    return [
      'heroes' => $heroes,
      'cards' => $cards
    ];
  }

  /**
   * Prepare items for PDF generation
   */
  public function prepareItemsForPdf(array $collection, Collection $heroes, Collection $cards): array
  {
    $items = [];

    // Add heroes with their copies
    foreach ($collection['heroes'] as $heroData) {
      $hero = $heroes->get($heroData['id']);
      if ($hero) {
        for ($i = 0; $i < $heroData['copies']; $i++) {
          $items[] = [
            'type' => 'hero',
            'entity' => $hero
          ];
        }
      }
    }

    // Add cards with their copies
    foreach ($collection['cards'] as $cardData) {
      $card = $cards->get($cardData['id']);
      if ($card) {
        for ($i = 0; $i < $cardData['copies']; $i++) {
          $items[] = [
            'type' => 'card',
            'entity' => $card
          ];
        }
      }
    }

    return $items;
  }

  /**
   * Generate PDF for a faction
   */
  public function generateFactionPdf(Faction $faction)
  {
    // Get all heroes from faction (1 copy each)
    $heroes = $faction->heroes()->published()
      ->with([
        'faction',
        'heroClass.heroSuperclass',
        'heroRace',
        'heroAbilities.attackRange',
        'heroAbilities.attackSubtype'
      ])
      ->get();
    
    // Get all cards from faction (2 copies each)
    $cards = $faction->cards()->published()
      ->with([
        'faction',
        'cardType.heroSuperclass',
        'equipmentType',
        'attackRange',
        'attackSubtype',
        'heroAbility.attackRange',
        'heroAbility.attackSubtype'
      ])
      ->get();
    
    // Prepare items for PDF
    $items = [];
    
    // Add heroes (1 copy each)
    foreach ($heroes as $hero) {
      $items[] = [
        'type' => 'hero',
        'entity' => $hero
      ];
    }
    
    // Add cards (2 copies each)
    foreach ($cards as $card) {
      for ($i = 0; $i < 2; $i++) {
        $items[] = [
          'type' => 'card',
          'entity' => $card
        ];
      }
    }
    
    return $items;
  }

  /**
   * Generate PDF for a deck
   */
  public function generateDeckPdf(FactionDeck $deck)
  {
    // Load all necessary relationships if not already loaded
    $deck->load([
      'heroes.faction',
      'heroes.heroClass.heroSuperclass',
      'heroes.heroRace',
      'heroes.heroAbilities.attackRange',
      'heroes.heroAbilities.attackSubtype',
      'cards.faction',
      'cards.cardType.heroSuperclass',
      'cards.equipmentType',
      'cards.attackRange',
      'cards.attackSubtype',
      'cards.heroAbility.attackRange',
      'cards.heroAbility.attackSubtype'
    ]);
    
    $items = [];
    
    // Add heroes with their copies
    foreach ($deck->heroes as $hero) {
      for ($i = 0; $i < $hero->pivot->copies; $i++) {
        $items[] = [
          'type' => 'hero',
          'entity' => $hero
        ];
      }
    }
    
    // Add cards with their copies
    foreach ($deck->cards as $card) {
      for ($i = 0; $i < $card->pivot->copies; $i++) {
        $items[] = [
          'type' => 'card',
          'entity' => $card
        ];
      }
    }
    
    return $items;
  }
}