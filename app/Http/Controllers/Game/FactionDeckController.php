<?php
// app/Http/Controllers/Game/FactionDeckController.php

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
    
    // Obtener contadores para tabs
    $counts = $this->factionDeckService->getFactionDecksCount();
    $activeCount = $counts['active'];
    $trashedCount = $counts['trashed'];
    
    // Obtener los faction decks con paginación y filtrado
    $factionDecks = $this->factionDeckService->getAllFactionDecks(
      $request, // request para filtros
      12,       // perPage
      false,    // withTrashed
      $trashed  // onlyTrashed
    );
    
    // Crear instancia de modelo para componente de filtros
    $factionDeckModel = new FactionDeck();
    
    // Obtener conteos de la respuesta paginada
    $totalCount = $factionDecks->totalCount ?? 0;
    $filteredCount = $factionDecks->filteredCount ?? 0;

    $gameModes = GameMode::orderBy('name')->get();
    
    return view('admin.faction-decks.index', compact(
      'factionDecks', 
      'trashed', 
      'activeCount', 
      'trashedCount',
      'factionDeckModel',
      'request',
      'totalCount',
      'filteredCount',
      'gameModes'
    ));
  }

  /**
   * Show the form for creating a new faction deck.
   */
  public function create(Request $request)
  {
    // Verificar que se proporciona un game_mode_id
    $gameModeId = $request->input('game_mode_id');
    if (!$gameModeId) {
      return redirect()->route('admin.faction-decks.index')
        ->with('warning', __('faction_decks.select_game_mode'));
    }
    
    // Obtener facción si se proporciona
    $factionId = $request->input('faction_id');
    $selectedFaction = null;
    
    if ($factionId) {
      $selectedFaction = Faction::findOrFail($factionId);
    }
    
    // Obtener la configuración para el modo de juego seleccionado
    $gameMode = GameMode::findOrFail($gameModeId);
    $deckConfig = $this->deckAttributesConfigurationService->getConfiguration($gameModeId);
    
    // Obtener datos para el formulario
    $factions = Faction::orderBy('name')->get();
    $gameModes = GameMode::orderBy('name')->get();
    
    // Obtener cartas y héroes usando el servicio
    $allCards = $this->factionDeckService->getAllCards();
    $allHeroes = $this->factionDeckService->getAllHeroes();
        
    return view('admin.faction-decks.create', compact(
      'factions',
      'gameModes',
      'gameMode',
      'deckConfig',
      'gameModeId',
      'allCards',
      'allHeroes',
      'selectedFaction',
      'factionId'
    ));
  }

  /**
   * Store a newly created faction deck in storage.
   */
  public function store(FactionDeckRequest $request)
  {
    $validated = $request->validated();

    try {
      // Crear el mazo usando el servicio
      $factionDeck = $this->factionDeckService->create($validated);
      
      return redirect()->route('admin.faction-decks.show', $factionDeck)
        ->with('success', __('faction_decks.created_successfully', ['name' => $factionDeck->name]));
    } catch (\Exception $e) {
      return back()
        ->with('error', __('faction_decks.creation_error'))
        ->withInput();
    }
  }

  /**
   * Display the specified faction deck.
   */
  public function show(Request $request, FactionDeck $factionDeck)
  {
    // Validate tab parameter
    $request->validate([
        'tab' => 'nullable|in:info,heroes,cards'
    ]);
    
    $tab = $request->get('tab', 'info');
    
    // Load relations
    $factionDeck = $this->factionDeckService->getFactionDeckWithRelations($factionDeck);
    
    // Get deck statistics
    $statistics = $this->factionDeckService->getDeckStatistics($factionDeck);
    
    return view('admin.faction-decks.show', compact('factionDeck', 'statistics', 'tab'));
  }

  /**
   * Show the form for editing the specified faction deck.
   */
  public function edit(FactionDeck $factionDeck)
  {
    // Obtener la configuración para el modo de juego
    $deckConfig = $this->deckAttributesConfigurationService->getConfiguration($factionDeck->game_mode_id);
    
    // Obtener datos para el formulario
    $factions = Faction::orderBy('name')->get();
    $gameModes = GameMode::orderBy('name')->get();
    
    // Obtener cartas y héroes usando el servicio
    $allCards = $this->factionDeckService->getAllCards();
    $allHeroes = $this->factionDeckService->getAllHeroes();
    
    // Obtener entidades seleccionadas
    $selectedEntities = $this->factionDeckService->getSelectedEntities($factionDeck);
    $selectedCards = $selectedEntities['cards'];
    $selectedHeroes = $selectedEntities['heroes'];
    
    $gameMode = $factionDeck->gameMode;
    $gameModeId = $factionDeck->game_mode_id;
    
    return view('admin.faction-decks.edit', compact(
      'factionDeck',
      'factions',
      'gameModes',
      'deckConfig',
      'allCards',
      'allHeroes',
      'selectedCards',
      'selectedHeroes',
      'gameMode',
      'gameModeId'
    ));
  }

  /**
   * Update the specified faction deck in storage.
   */
  public function update(FactionDeckRequest $request, FactionDeck $factionDeck)
  {
    $validated = $request->validated();

    try {
      // Actualizar el mazo usando el servicio
      $this->factionDeckService->update($factionDeck, $validated);
      
      return redirect()->route('admin.faction-decks.show', $factionDeck)
        ->with('success', __('faction_decks.updated_successfully', ['name' => $factionDeck->name]));
    } catch (\Exception $e) {
      return back()
        ->with('error', __('faction_decks.update_error'))
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
      return back()
        ->with('error', __('faction_decks.delete_error'));
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
      return back()
        ->with('error', __('faction_decks.restore_error'));
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
      return back()
        ->with('error', __('faction_decks.force_delete_error'));
    }
  }

  /**
   * Toggle the published status of the specified faction deck.
   */
  public function togglePublished(FactionDeck $factionDeck)
  {
    $factionDeck->togglePublished();

    $statusMessage = $factionDeck->isPublished() 
      ? __('faction_decks.published_successfully', ['name' => $factionDeck->name])
      : __('faction_decks.unpublished_successfully', ['name' => $factionDeck->name]);

    return back()->with('success', $statusMessage);
  }
}