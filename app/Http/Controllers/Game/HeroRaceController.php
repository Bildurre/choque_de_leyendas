<?php

namespace App\Http\Controllers\Game;

use App\Models\HeroRace;
use App\Http\Controllers\Controller;
use App\Services\Game\HeroRaceService;
use App\Http\Requests\Game\HeroRaceRequest;

class HeroRaceController extends Controller
{
  protected $heroRaceService;

  /**
   * Create a new controller instance.
   *
   * @param HeroRaceService $heroRaceService
   */
  public function __construct(HeroRaceService $heroRaceService)
  {
    $this->heroRaceService = $heroRaceService;
  }

  /**
   * Display a listing of hero races.
   */
  public function index()
  {
    $heroRaces = $this->heroRaceService->getAllHeroRaces();
    
    return view('admin.hero-races.index', compact('heroRaces'));
  }

  /**
   * Show the form for creating a new hero race.
   */
  public function create()
  {
    return view('admin.hero-races.create');
  }

  /**
   * Store a newly created hero race in storage.
   */
  public function store(HeroRaceRequest $request)
  {
    $validated = $request->validated();

    try {
      $heroRace = $this->heroRaceService->create($validated);
      return redirect()->route('admin.hero-races.index')
        ->with('success', "La raza {$heroRace->name} ha sido creada correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al crear la Raza: ' . $e->getMessage());
    }
  }

  /**
   * Show the form for editing the specified hero race.
   */
  public function edit(HeroRace $heroRace)
  {
    return view('admin.hero-races.edit', compact('heroRace'));
  }

  /**
   * Update the specified hero race in storage.
   */
  public function update(HeroRaceRequest $request, HeroRace $heroRace)
  {
    $validated = $request->validated();

    try {
      $this->heroRaceService->update($heroRace, $validated);
      return redirect()->route('admin.hero-races.index')
        ->with('success', "La raza {$heroRace->name} ha sido actualizada correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al actualizar la Raza: ' . $e->getMessage());
    }
  }

  /**
   * Remove the specified hero race from storage.
   */
  public function destroy(HeroRace $heroRace)
  {
    try {
      $raceName = $heroRace->name;
      $this->heroRaceService->delete($heroRace);
      return redirect()->route('admin.hero-races.index')
        ->with('success', "La raza {$raceName} ha sido eliminada correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al eliminar la Raza: ' . $e->getMessage());
    }
  }
}