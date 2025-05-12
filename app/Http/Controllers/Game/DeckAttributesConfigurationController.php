<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Http\Requests\Game\DeckAttributesConfigurationRequest;
use App\Services\Game\DeckAttributesConfigurationService;

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
   * Show the form for editing the deck attributes configuration.
   *
   * @return \Illuminate\View\View
   */
  public function edit()
  {
    $configuration = $this->deckAttributesConfigurationService->getConfiguration();
    
    return view('admin.deck-attributes-configurations.edit', compact('configuration'));
  }

  /**
   * Update the deck attributes configuration.
   *
   * @param DeckAttributesConfigurationRequest $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function update(DeckAttributesConfigurationRequest $request)
  {
    try {
      $this->deckAttributesConfigurationService->updateConfiguration($request->validated());
      
      return redirect()->route('admin.deck-attributes-configurations.edit')
        ->with('success', __('deck_attributes.config_updated_successfully'));
    } catch (\Exception $e) {
      return back()->withInput()
        ->with('error', $e->getMessage());
    }
  }
}