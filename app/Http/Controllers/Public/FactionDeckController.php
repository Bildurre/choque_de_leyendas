<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\FactionDeck;
use App\Models\GameMode;
use App\Services\Public\FactionDeckService;
use Illuminate\Http\Request;
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
   * Display a listing of faction decks grouped by game mode
   */
  public function index(Request $request): View
  {
    // Get all game modes
    $gameModes = GameMode::orderBy('name')->get();
    
    // Validate tab parameter
    $activeTab = $request->get('tab', $gameModes->first()->id ?? null);
    if ($activeTab) {
      $validGameModeIds = $gameModes->pluck('id')->toArray();
      $request->validate([
        'tab' => ['nullable', 'in:' . implode(',', $validGameModeIds)]
      ]);
    }
    
    // Get faction decks for the active game mode with pagination
    $selectedGameMode = $gameModes->firstWhere('id', $activeTab);
    $factionDecks = collect();
    
    if ($selectedGameMode) {
      $factionDecks = FactionDeck::where('game_mode_id', $selectedGameMode->id)
        ->published()
        ->with(['factions', 'heroes', 'cards', 'gameMode'])
        ->orderBy('name')
        ->paginate(12)
        ->appends(['tab' => $activeTab]);
    }
    
    // Get counts for all game modes
    $deckCounts = FactionDeck::published()
      ->selectRaw('game_mode_id, count(*) as count')
      ->groupBy('game_mode_id')
      ->pluck('count', 'game_mode_id');
    
    return view('public.faction-decks.index', compact('gameModes', 'factionDecks', 'selectedGameMode', 'activeTab', 'deckCounts'));
  }

  /**
   * Display the specified faction deck
   */
  public function show(Request $request, FactionDeck $factionDeck): View
  {
    $factionDeck = $this->factionDeckService->getPublishedFactionDeck($factionDeck);
    
    // Validate tab parameter
    $request->validate([
      'tab' => 'nullable|in:info,heroes,cards'
    ]);
    
    // Get deck statistics
    $statistics = $this->factionDeckService->getDeckStatistics($factionDeck);
    
    return view('public.faction-decks.show', compact('factionDeck', 'statistics'));
  }
}