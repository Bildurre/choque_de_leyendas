<?php

namespace App\Services\PdfGenerator\PrintCollection;

use App\Models\Card;
use App\Models\Faction;
use App\Models\FactionDeck;
use App\Models\Hero;
use Illuminate\Support\Collection;

class PrintCollectionService
{
  /**
   * Add a hero to the collection
   */
  public function addHero(int $id, array $collection): array
  {
    $hero = Hero::published()->findOrFail($id);
    $key = 'hero_' . $id;

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
    $key = 'card_' . $id;

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
    
    // Add all heroes from faction (1 copy each)
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

    // Add all cards from faction (2 copies each)
    foreach ($faction->cards()->published()->get() as $card) {
      $key = 'card_' . $card->id;
      $copiesToAdd = 2;
      
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
   * Add all items from a deck to the collection
   */
  public function addDeck(int $id, array $collection): array
  {
    $deck = FactionDeck::published()->findOrFail($id);

    // Add heroes with their copies from the deck
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

    // Add cards with their copies from the deck
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
   * Update the quantity of an item
   */
  public function updateItemQuantity(string $type, int $id, int $copies, array $collection): array
  {
    $key = $type . '_' . $id;
    
    if ($type === 'hero' && isset($collection['heroes'][$key])) {
      $collection['heroes'][$key]['copies'] = $copies;
    } elseif ($type === 'card' && isset($collection['cards'][$key])) {
      $collection['cards'][$key]['copies'] = $copies;
    }

    return $collection;
  }

  /**
   * Remove an item from the collection
   */
  public function removeItem(string $type, int $id, array $collection): array
  {
    $key = $type . '_' . $id;
    
    if ($type === 'hero') {
      unset($collection['heroes'][$key]);
    } elseif ($type === 'card') {
      unset($collection['cards'][$key]);
    }

    return $collection;
  }

  /**
   * Get total count of unique items in collection
   */
  public function getTotalCount(array $collection): int
  {
    return count($collection['heroes']) + count($collection['cards']);
  }

  /**
   * Get total copies count in collection
   */
  public function getTotalCopies(array $collection): int
  {
    $total = 0;
    
    foreach ($collection['heroes'] as $hero) {
      $total += $hero['copies'];
    }
    
    foreach ($collection['cards'] as $card) {
      $total += $card['copies'];
    }
    
    return $total;
  }

  /**
   * Load collection models with all necessary relationships
   */
  public function loadCollectionModels(array $collection): array
  {
    $heroIds = array_column($collection['heroes'], 'id');
    $cardIds = array_column($collection['cards'], 'id');

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
   * Get success message for add action
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
}