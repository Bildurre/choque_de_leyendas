<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Http\Requests\Game\HeroAttributesConfigurationRequest;
use App\Services\Game\HeroAttributesConfigurationService;

class HeroAttributesConfigurationController extends Controller
{
  protected $heroAttributesConfigurationService;

  /**
   * Create a new controller instance.
   *
   * @param HeroAttributesConfigurationService $heroAttributesConfigurationService
   */
  public function __construct(HeroAttributesConfigurationService $heroAttributesConfigurationService)
  {
    $this->heroAttributesConfigurationService = $heroAttributesConfigurationService;
  }

  /**
   * Show the form for editing the attributes configuration.
   *
   * @return \Illuminate\View\View
   */
  public function edit()
  {
    $configuration = $this->heroAttributesConfigurationService->getConfiguration();
    
    return view('admin.hero-attributes-configurations.edit', compact('configuration'));
  }

  /**
   * Update the attributes configuration.
   *
   * @param HeroAttributesConfigurationRequest $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function update(HeroAttributesConfigurationRequest $request)
  {
    try {
      $this->heroAttributesConfigurationService->updateConfiguration($request->validated());
      
      return redirect()->route('admin.hero-attributes-configurations.edit')
        ->with('success', __('hero_attributes.config_updated_successfully'));
    } catch (\Exception $e) {
      return back()->withInput()
        ->with('error', $e->getMessage());
    }
  }
}