<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Models\HeroAttributeConfiguration;
use App\Services\HeroAttributeConfigurationService;
use App\Http\Requests\Admin\HeroAttributeConfiguration\UpdateHeroAttributeConfigurationRequest;

class HeroAttributeConfigurationController extends Controller
{
  protected $configurationService;

  /**
   * Create a new controller instance.
   *
   * @param HeroAttributeConfigurationService $configurationService
   */
  public function __construct(HeroAttributeConfigurationService $configurationService)
  {
    $this->configurationService = $configurationService;
  }

  /**
   * Show the form for editing hero attributes configuration
   */
  public function edit(): View
  {
    // Get the first (and only) configuration
    $configuration = $this->configurationService->getConfiguration();
    return view('admin.hero-attributes.edit', compact('configuration'));
  }

  /**
   * Update hero attributes configuration
   */
  public function update(UpdateHeroAttributeConfigurationRequest $request): RedirectResponse
  {
    // Validate the input
    $validated = $request->validated();

    try {
      $this->configurationService->updateConfiguration($validated);
      
      // Redirect with success message
      return redirect()
        ->route('admin.hero-attributes.edit')
        ->with('success', 'Hero attribute configuration updated successfully.');
    } catch (\Exception $e) {
      return back()
        ->withInput()
        ->withErrors([
          'base_points' => $e->getMessage()
        ]);
    }
  }
}