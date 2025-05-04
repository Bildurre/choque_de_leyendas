<?php

namespace App\Http\Controllers\Game;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Services\Game\HeroAttributesConfigurationService;
use App\Http\Requests\Game\HeroAttributesConfigurationRequest;

class HeroAttributesConfigurationController extends Controller
{
  protected $configurationService;

  /**
   * Create a new controller instance.
   *
   * @param HeroAttributesConfigurationService $configurationService
   */
  public function __construct(HeroAttributesConfigurationService $configurationService)
  {
    $this->configurationService = $configurationService;
  }

  /**
   * Show the form for editing hero attributes configuration
   */
  public function edit(): View
  {
    // Get the configuration
    $configuration = $this->configurationService->getConfiguration();
    return view('admin.hero-attributes-configurations.edit', compact('configuration'));
  }

  /**
   * Update hero attributes configuration
   */
  public function update(HeroAttributesConfigurationRequest $request): RedirectResponse
  {
    // Validate the input
    $validated = $request->validated();

    try {
      $this->configurationService->updateConfiguration($validated);
      
      // Redirect with success message
      return redirect()
        ->route('admin.hero-attributes-configurations.edit')
        ->with('success', 'Configuración de atributos actualizada correctamente.');
    } catch (\Exception $e) {
      return back()->with('error', 'Error al actualizar la configuración: ' . $e->getMessage())
        ->withInput();
    }
  }
}