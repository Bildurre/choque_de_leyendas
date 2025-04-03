<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroAttributeConfiguration;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class HeroAttributeConfigurationController extends Controller
{
  /**
   * Show the form for editing hero attributes configuration
   */
  public function edit(): View
  {
    // Get the first (and only) configuration
    $configuration = HeroAttributeConfiguration::firstOrFail();
    return view('admin.hero-attributes.edit', compact('configuration'));
  }

  /**
   * Update hero attributes configuration
   */
  public function update(Request $request): RedirectResponse
  {
    // Validate the input
    $validated = $request->validate([
      'base_agility' => 'required|integer|min:0',
      'base_mental' => 'required|integer|min:0',
      'base_will' => 'required|integer|min:0',
      'base_strength' => 'required|integer|min:0',
      'base_armor' => 'required|integer|min:0',
      'total_points' => 'required|integer|min:1'
    ]);

    // Get the first configuration
    $configuration = HeroAttributeConfiguration::firstOrFail();

    // Calculate total base points
    $basePointsTotal = 
      $validated['base_agility'] + 
      $validated['base_mental'] + 
      $validated['base_will'] + 
      $validated['base_strength'] + 
      $validated['base_armor'];

    // Validate that base points don't exceed total points
    if ($basePointsTotal > $validated['total_points']) {
      return back()
        ->withInput()
        ->withErrors([
          'base_points' => 'The sum of base attributes cannot exceed the total points.'
        ]);
    }

    // Update the configuration
    $configuration->update($validated);

    // Redirect with success message
    return redirect()
      ->route('admin.hero-attributes.edit')
      ->with('success', 'Hero attribute configuration updated successfully.');
  }
}