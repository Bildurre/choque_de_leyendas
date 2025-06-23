<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\FactionDeck;
use App\Services\Public\FactionDeckService;
use Illuminate\View\View;

class FactionDeckController extends Controller
{
  /**
   * The faction deck service instance
   */
  public function __construct(
    private FactionDeckService $factionDeckService
  ) {}

  /**
   * Display the specified faction deck
   */
  public function show(FactionDeck $factionDeck): View
  {
    $factionDeck = $this->factionDeckService->getPublishedFactionDeck($factionDeck);
    
    return view('public.faction-decks.show', compact('factionDeck'));
  }
}