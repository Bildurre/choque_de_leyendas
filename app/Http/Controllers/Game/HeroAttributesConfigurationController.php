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
    
    // Calcular ejemplos de salud
    $healthExamples = [
      'min' => $this->heroAttributesConfigurationService->calculateHealth(
        $configuration->min_attribute_value,
        $configuration->min_attribute_value,
        $configuration->min_attribute_value,
        $configuration->min_attribute_value,
        $configuration->min_attribute_value
      ),
      'max' => $this->heroAttributesConfigurationService->calculateHealth(
        $configuration->max_attribute_value,
        $configuration->max_attribute_value,
        $configuration->max_attribute_value,
        $configuration->max_attribute_value,
        $configuration->max_attribute_value
      ),
      'balance' => $this->heroAttributesConfigurationService->calculateHealth(3, 3, 3, 3, 3),
    ];
    
    return view('admin.hero-attributes-configurations.edit', compact('configuration', 'healthExamples'));
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