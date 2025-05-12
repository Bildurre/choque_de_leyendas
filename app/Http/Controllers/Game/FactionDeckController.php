<?php

namespace App\Http\Controllers\Game;

use App\Models\FactionDeck;
use App\Models\Faction;
use App\Models\GameMode;
use App\Services\Game\FactionDeckService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\FactionDeckRequest;
use Illuminate\Http\Request;

class FactionDeckController extends Controller
{
  protected $factionDeckService;

  /**
   * Create a new controller instance.
   *
   * @param FactionDeckService $factionDeckService
   */
  public function __construct(FactionDeckService $factionDeckService)
  {
    $this->factionDeckService = $factionDeckService;
  }

  /**
   * Display a listing of faction decks.
   */
  public function index(Request $request)
  {
    $trashed = $request->has('trashed');
    $factionId = $request->get('faction_id');
    $gameModeId = $request->get('game_mode_id');
    
    // Get counters for tabs
    $activeCount = FactionDeck::count();
    $trashedCount = FactionDeck::onlyTrashed()->count();
    
    // Get faction decks with pagination
    $factionDecks = $this->factionDeckService->getAllFactionDecks(
      12, 
      false, 
      $trashed,
      $factionId,
      $gameModeId
    );
    
    // Get all factions and game modes for filters
    $factions = Faction::orderBy('id')->get();
    $gameModes = GameMode::orderBy('id')->get();
    
    return view('admin.faction-decks.index', compact(
      'factionDecks', 
      'trashed', 
      'activeCount', 
      'trashedCount',
      'factions',
      'gameModes',
      'factionId',
      'gameModeId'
    ));
  }

  /**
   * Show the form for creating a new faction deck.
   */
  public function create(Request $request)
  {
    $factions = Faction::orderBy('id')->get();
    $gameModes = GameMode::orderBy('id')->get();
    
    // Optional faction preselected from request
    $selectedFactionId = $request->get('faction_id');
    $availableCards = [];
    $availableHeroes = [];
    
    // If faction is preselected, get available cards and heroes
    if ($selectedFactionId) {
      $availableCards = $this->factionDeckService->getAvailableCards($selectedFactionId);
      $availableHeroes = $this->factionDeckService->getAvailableHeroes($selectedFactionId);
    }
    
    return view('admin.faction-decks.create', compact(
      'factions',
      'gameModes',
      'selectedFactionId',
      'availableCards',
      'availableHeroes'
    ));
  }

  /**
   * Store a newly created faction deck in storage.
   */
  public function store(FactionDeckRequest $request)
  {
    $validated = $request->validated();

    try {
      $factionDeck = $this->factionDeckService->create($validated);
      return redirect()->route('admin.faction-decks.show', $factionDeck)
        ->with('success', __('faction_decks.created_successfully', ['name' => $factionDeck->name]));
    } catch (\Exception $e) {
      return back()
        ->with('error', 'Ha ocurrido un error al crear el Mazo de Facción: ' . $e->getMessage())
        ->withInput();
    }
  }

  /**
   * Display the specified faction deck.
   */
  public function show(FactionDeck $factionDeck)
  {
    $factionDeck = $this->factionDeckService->getFactionDeckWithRelations($factionDeck);
    
    return view('admin.faction-decks.show', compact('factionDeck'));
  }

  /**
   * Show the form for editing the specified faction deck.
   */
  public function edit(FactionDeck $factionDeck)
  {
    $factions = Faction::orderBy('id')->get();
    $gameModes = GameMode::orderBy('id')->get();
    
    // Get available cards and heroes for the faction
    $availableCards = $this->factionDeckService->getAvailableCards($factionDeck->faction_id);
    $availableHeroes = $this->factionDeckService->getAvailableHeroes($factionDeck->faction_id);
    
    // Load current cards and heroes with pivot
    $factionDeck->load(['cards', 'heroes']);
    
    // Format cards and heroes for the form
    $selectedCards = $factionDeck->cards->map(function ($card) {
      return [
        'id' => $card->id,
        'copies' => $card->pivot->copies
      ];
    })->toArray();
    
    $selectedHeroes = $factionDeck->heroes->map(function ($hero) {
      return [
        'id' => $hero->id,
        'copies' => $hero->pivot->copies
      ];
    })->toArray();
    
    return view('admin.faction-decks.edit', compact(
      'factionDeck',
      'factions',
      'gameModes',
      'availableCards',
      'availableHeroes',
      'selectedCards',
      'selectedHeroes'
    ));
  }

  /**
   * Update the specified faction deck in storage.
   */
  public function update(FactionDeckRequest $request, FactionDeck $factionDeck)
  {
    $validated = $request->validated();

    try {
      $this->factionDeckService->update($factionDeck, $validated);
      return redirect()->route('admin.faction-decks.show', $factionDeck)
        ->with('success', __('faction_decks.updated_successfully', ['name' => $factionDeck->name]));
    } catch (\Exception $e) {
      return back()
        ->with('error', 'Ha ocurrido un error al actualizar el Mazo de Facción: ' . $e->getMessage())
        ->withInput();
    }
  }

  /**
   * Remove the specified faction deck from storage.
   */
  public function destroy(FactionDeck $factionDeck)
  {
    try {
      $factionDeckName = $factionDeck->name;
      $this->factionDeckService->delete($factionDeck);
      
      return redirect()->route('admin.faction-decks.index')
        ->with('success', __('faction_decks.deleted_successfully', ['name' => $factionDeckName]));
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al eliminar el Mazo de Facción: ' . $e->getMessage());
    }
  }

  /**
   * Restore the specified faction deck from trash.
   */
  public function restore($id)
  {
    try {
      $this->factionDeckService->restore($id);
      $factionDeck = FactionDeck::find($id);
      
      return redirect()->route('admin.faction-decks.index', ['trashed' => 1])
        ->with('success', __('faction_decks.restored_successfully', ['name' => $factionDeck->name]));
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al restaurar el Mazo de Facción: ' . $e->getMessage());
    }
  }

  /**
   * Force delete the specified faction deck from storage.
   */
  public function forceDelete($id)
  {
    try {
      $factionDeck = FactionDeck::onlyTrashed()->findOrFail($id);
      $name = $factionDeck->name;
      
      $this->factionDeckService->forceDelete($id);
      
      return redirect()->route('admin.faction-decks.index', ['trashed' => 1])
        ->with('success', __('faction_decks.force_deleted_successfully', ['name' => $name]));
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al eliminar permanentemente el Mazo de Facción: ' . $e->getMessage());
    }
  }

  /**
   * Get available cards and heroes for a faction (AJAX)
   */
  public function getAvailableItems(Request $request)
  {
    $factionId = $request->get('faction_id');
    
    if (!$factionId) {
      return response()->json([
        'error' => 'Faction ID is required'
      ], 400);
    }
    
    try {
      $availableCards = $this->factionDeckService->getAvailableCards($factionId);
      $availableHeroes = $this->factionDeckService->getAvailableHeroes($factionId);
      
      return response()->json([
        'cards' => $availableCards,
        'heroes' => $availableHeroes
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }
}