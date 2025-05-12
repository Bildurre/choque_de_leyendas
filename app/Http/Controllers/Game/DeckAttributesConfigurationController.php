<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Http\Requests\Game\DeckAttributesConfigurationRequest;
use App\Models\DeckAttributesConfiguration;
use App\Models\GameMode;
use App\Services\Game\DeckAttributesConfigurationService;
use Illuminate\Http\Request;

class DeckAttributesConfigurationController extends Controller
{
  protected $deckAttributesConfigurationService;

  /**
   * Create a new controller instance.
   *
   * @param DeckAttributesConfigurationService $deckAttributesConfigurationService
   */
  public function __construct(DeckAttributesConfigurationService $deckAttributesConfigurationService)
  {
    $this->deckAttributesConfigurationService = $deckAttributesConfigurationService;
  }

  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $configurations = DeckAttributesConfiguration::with('gameMode')->paginate(12);
    return view('admin.deck-attributes-configurations.index', compact('configurations'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    // Get game modes that don't have a configuration yet
    $gameModes = GameMode::whereNotIn('id', function($query) {
      $query->select('game_mode_id')
        ->from('deck_attributes_configurations')
        ->whereNotNull('game_mode_id');
    })->get();
    
    if ($gameModes->isEmpty()) {
      return redirect()->route('admin.deck-attributes-configurations.index')
        ->with('warning', __('deck_attributes.all_game_modes_have_configuration'));
    }
    
    return view('admin.deck-attributes-configurations.create', compact('gameModes'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(DeckAttributesConfigurationRequest $request)
  {
    try {
      $this->deckAttributesConfigurationService->create($request->validated());
      return redirect()->route('admin.deck-attributes-configurations.index')
        ->with('success', __('deck_attributes.created_successfully'));
    } catch (\Exception $e) {
      return back()->withInput()
        ->with('error', $e->getMessage());
    }
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(DeckAttributesConfiguration $deckAttributesConfiguration)
  {
    $gameModes = GameMode::all();
    $configuration = $deckAttributesConfiguration;
    
    return view('admin.deck-attributes-configurations.edit', compact('configuration', 'gameModes'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(DeckAttributesConfigurationRequest $request, DeckAttributesConfiguration $deckAttributesConfiguration)
  {
    try {
      $this->deckAttributesConfigurationService->update($deckAttributesConfiguration, $request->validated());
      return redirect()->route('admin.deck-attributes-configurations.index')
        ->with('success', __('deck_attributes.updated_successfully'));
    } catch (\Exception $e) {
      return back()->withInput()
        ->with('error', $e->getMessage());
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(DeckAttributesConfiguration $deckAttributesConfiguration)
  {
    try {
      $this->deckAttributesConfigurationService->delete($deckAttributesConfiguration);
      return redirect()->route('admin.deck-attributes-configurations.index')
        ->with('success', __('deck_attributes.deleted_successfully'));
    } catch (\Exception $e) {
      return back()
        ->with('error', $e->getMessage());
    }
  }
}