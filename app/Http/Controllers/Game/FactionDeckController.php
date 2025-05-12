<?php

namespace App\Http\Controllers\Game;

use App\Models\FactionDeck;
use App\Models\Faction;
use App\Models\GameMode;
use App\Services\Game\FactionDeckService;
use App\Services\Game\DeckAttributesConfigurationService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\FactionDeckRequest;
use Illuminate\Http\Request;

class FactionDeckController extends Controller
{
  protected $factionDeckService;
  protected $deckAttributesConfigurationService;

  /**
   * Create a new controller instance.
   *
   * @param FactionDeckService $factionDeckService
   * @param DeckAttributesConfigurationService $deckAttributesConfigurationService
   */
  public function __construct(
    FactionDeckService $factionDeckService,
    DeckAttributesConfigurationService $deckAttributesConfigurationService
  ) {
    $this->factionDeckService = $factionDeckService;
    $this->deckAttributesConfigurationService = $deckAttributesConfigurationService;
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
    
    // Get all game modes for the dropdown
    $gameModes = GameMode::orderBy('name')->get();
    
    // Get all factions for filters
    $factions = Faction::orderBy('name')->get();
    
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
    // Obtenemos todas las facciones disponibles
    $factions = Faction::orderBy('name')->get();
    
    // Obtenemos todos los modos de juego disponibles
    $gameModes = GameMode::orderBy('name')->get();
        
    return view('admin.faction-decks.create', compact(
      'factions',
      'gameModes'
    ));
  }

  /**
   * Store a newly created faction deck in storage.
   */
  public function store(FactionDeckRequest $request)
  {
    $validated = $request->validated();

    try {
      // Verificamos que la facción de todas las cartas coincida con la facción del mazo
      $this->validateCardsFaction($validated);
      
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
    // Obtenemos la configuración para el modo de juego de este mazo
    $deckConfig = $this->deckAttributesConfigurationService->getConfiguration($factionDeck->game_mode_id);
    
    // Obtenemos todas las facciones disponibles
    $factions = Faction::orderBy('name')->get();
    
    // Obtenemos todos los modos de juego
    $gameModes = GameMode::orderBy('name')->get();
    
    // Obtenemos las cartas y héroes para la vista inicial
    $availableCards = $this->factionDeckService->getAvailableCards($factionDeck->faction_id);
    $availableHeroes = $this->factionDeckService->getAvailableHeroes($factionDeck->faction_id);
    
    // Format current cards and heroes for the form
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
      'deckConfig',
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
      // Verificamos que la facción de todas las cartas coincida con la facción del mazo
      $this->validateCardsFaction($validated);
      
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
   * Validate that all cards belong to the selected faction.
   */
  private function validateCardsFaction(array $data)
  {
    $factionId = $data['faction_id'];
    
    // Verificar cartas
    if (isset($data['cards']) && is_array($data['cards'])) {
      foreach ($data['cards'] as $cardData) {
        if (!isset($cardData['id'])) continue;
        
        $card = \App\Models\Card::find($cardData['id']);
        if ($card && $card->faction_id != $factionId) {
          throw new \Exception(
            __('faction_decks.card_faction_mismatch', [
              'card' => $card->name,
              'faction' => \App\Models\Faction::find($factionId)->name
            ])
          );
        }
      }
    }
    
    // Verificar héroes
    if (isset($data['heroes']) && is_array($data['heroes'])) {
      foreach ($data['heroes'] as $heroData) {
        if (!isset($heroData['id'])) continue;
        
        $hero = \App\Models\Hero::find($heroData['id']);
        if ($hero && $hero->faction_id != $factionId) {
          throw new \Exception(
            __('faction_decks.hero_faction_mismatch', [
              'hero' => $hero->name,
              'faction' => \App\Models\Faction::find($factionId)->name
            ])
          );
        }
      }
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
}