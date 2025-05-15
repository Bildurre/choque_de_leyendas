<?php

namespace App\Http\Controllers\Game;

use App\Models\HeroRace;
use App\Services\Game\HeroRaceService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\HeroRaceRequest;
use Illuminate\Http\Request;

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
  public function index(Request $request)
  {
    $trashed = $request->has('trashed');
    
    // Get counters for tabs directly using Eloquent
    $activeCount = HeroRace::count();
    $trashedCount = HeroRace::onlyTrashed()->count();
    
    // Get hero races with filtering and pagination
    $heroRaces = $this->heroRaceService->getAllHeroRaces(
      $request, // request para filtros
      12,       // perPage
      false,    // withTrashed
      $trashed  // onlyTrashed
    );
    
    // Create a HeroRace instance for filter component
    $heroRaceModel = new HeroRace();
    
    // Get counts from the paginated result
    $totalCount = $heroRaces->totalCount ?? 0;
    $filteredCount = $heroRaces->filteredCount ?? 0;
    
    return view('admin.hero-races.index', compact(
      'heroRaces', 
      'trashed', 
      'activeCount', 
      'trashedCount',
      'heroRaceModel',
      'request',
      'totalCount',
      'filteredCount'
    ));
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
        ->with('success', __('hero_races.created_successfully', ['name' => $heroRace->name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.create', ['entity' => __('entities.hero_races.singular')]))
        ->withInput();
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
        ->with('success', __('hero_races.updated_successfully', ['name' => $heroRace->name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.update', ['entity' => __('entities.hero_races.singular')]))
        ->withInput();
    }
  }

  /**
   * Remove the specified hero race from storage.
   */
  public function destroy(HeroRace $heroRace)
  {
    try {
      $heroRaceName = $heroRace->name;
      $this->heroRaceService->delete($heroRace);
      
      return redirect()->route('admin.hero-races.index')
        ->with('success', __('hero_races.deleted_successfully', ['name' => $heroRaceName]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.delete', ['entity' => __('entities.hero_races.singular')]));
    }
  }

  /**
   * Restore the specified hero race from trash.
   */
  public function restore($id)
  {
    try {
      $this->heroRaceService->restore($id);
      $heroRace = HeroRace::find($id);
      
      return redirect()->route('admin.hero-races.index', ['trashed' => 1])
        ->with('success', __('hero_races.restored_successfully', ['name' => $heroRace->name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.restore', ['entity' => __('entities.hero_races.singular')]));
    }
  }

  /**
   * Force delete the specified hero race from storage.
   */
  public function forceDelete($id)
  {
    try {
      $heroRace = HeroRace::onlyTrashed()->findOrFail($id);
      $name = $heroRace->name;
      
      $this->heroRaceService->forceDelete($id);
      
      return redirect()->route('admin.hero-races.index', ['trashed' => 1])
        ->with('success', __('hero_races.force_deleted_successfully', ['name' => $name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.force_delete', ['entity' => __('entities.hero_races.singular')]));
    }
  }
}