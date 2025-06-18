<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\FactionDeck;

class FactionDeckController extends Controller
{
  /**
   * Display the specified faction deck.
   */
  public function show(FactionDeck $factionDeck)
  {
    // Only show published decks
    if (!$factionDeck->isPublished()) {
      abort(404);
    }
    
    // Load relationships with all necessary nested relationships
    $factionDeck->load([
      'faction',
      'gameMode',
      'heroes.heroClass.heroSuperclass',
      'heroes.heroRace',
      'heroes.faction',
      'heroes.heroAbilities.attackRange',
      'heroes.heroAbilities.attackSubtype',
      'cards.cardType.heroSuperclass',
      'cards.equipmentType',
      'cards.attackRange',
      'cards.attackSubtype',
      'cards.heroAbility.attackRange',
      'cards.heroAbility.attackSubtype',
      'cards.faction'
    ]);
    
    return view('public.faction-decks.show', compact('factionDeck'));
  }
}