<?php

namespace App\Http\Controllers\Game;

use App\Models\Faction;
use App\Services\Game\FactionService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\FactionRequest;
use Illuminate\Http\Request;

class FactionController extends Controller
{
  protected $factionService;

  /**
   * Create a new controller instance.
   *
   * @param FactionService $factionService
   */
  public function __construct(FactionService $factionService)
  {
    $this->factionService = $factionService;
  }

  /**
   * Display a listing of factions.
   */
  public function index(Request $request)
  {
    $trashed = $request->has('trashed');
    
    // Get counters for tabs
    $activeCount = Faction::count();
    $trashedCount = Faction::onlyTrashed()->count();
    
    // Get factions with related counts, filtering and pagination
    $factions = $this->factionService->getAllFactions(
      $request, // request para filtros
      12,       // perPage
      false,    // withTrashed
      $trashed  // onlyTrashed
    );
    
    // Create a Faction instance for filter component
    $factionModel = new Faction();
    
    // Get counts from the paginated result
    $totalCount = $factions->totalCount ?? 0;
    $filteredCount = $factions->filteredCount ?? 0;
    
    return view('admin.factions.index', compact(
      'factions', 
      'trashed', 
      'activeCount', 
      'trashedCount',
      'factionModel',
      'request',
      'totalCount',
      'filteredCount'
    ));
  }

  /**
   * Show the form for creating a new faction.
   */
  public function create()
  {
    return view('admin.factions.create');
  }

  /**
   * Store a newly created faction in storage.
   */
  public function store(FactionRequest $request)
  {
    $validated = $request->validated();

    try {
      $faction = $this->factionService->create($validated);
      return redirect()->route('admin.factions.index')
        ->with('success', __('factions.created_successfully', ['name' => $faction->name]));
    } catch (\Exception $e) {
      return back()
        ->with('error', __('common.errors.create', ['entity' => __('entities.factions.singular')]))
        ->withInput();
    }
  }

  /**
   * Show the form for editing the specified faction.
   */
  public function edit(Faction $faction)
  {
    return view('admin.factions.edit', compact('faction'));
  }

  /**
   * Display the specified faction with tab support.
   */
  public function show(Faction $faction, Request $request)
  {
    $tab = $request->query('tab', 'details'); // Default to 'details' tab
    
    $data = $this->factionService->getFactionWithTabData($faction, $tab);
    
    return view('admin.factions.show', $data);
  }

  /**
   * Update the specified faction in storage.
   */
  public function update(FactionRequest $request, Faction $faction)
  {
    $validated = $request->validated();

    try {
      $this->factionService->update($faction, $validated);
      return redirect()->route('admin.factions.index')
        ->with('success', __('factions.updated_successfully', ['name' => $faction->name]));
    } catch (\Exception $e) {
      return back()
        ->with('error', __('common.errors.update', ['entity' => __('entities.factions.singular')]))
        ->withInput();
    }
  }

  /**
   * Remove the specified faction from storage.
   */
  public function destroy(Faction $faction)
  {
    try {
      $factionName = $faction->name;
      $this->factionService->delete($faction);
      
      return redirect()->route('admin.factions.index')
        ->with('success', __('factions.deleted_successfully', ['name' => $factionName]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.delete', ['entity' => __('entities.factions.singular')]));
    }
  }

  /**
   * Restore the specified faction from trash.
   */
  public function restore($id)
  {
    try {
      $this->factionService->restore($id);
      $faction = Faction::find($id);
      
      return redirect()->route('admin.factions.index', ['trashed' => 1])
        ->with('success', __('factions.restored_successfully', ['name' => $faction->name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.restore', ['entity' => __('entities.factions.singular')]));
    }
  }

  /**
   * Force delete the specified faction from storage.
   */
  public function forceDelete($id)
  {
    try {
      $faction = Faction::onlyTrashed()->findOrFail($id);
      $name = $faction->name;
      
      $this->factionService->forceDelete($id);
      
      return redirect()->route('admin.factions.index', ['trashed' => 1])
        ->with('success', __('factions.force_deleted_successfully', ['name' => $name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.force_delete', ['entity' => __('entities.factions.singular')]));
    }
  }

  /**
   * Toggle the published status of the specified faction.
   */
  public function togglePublished(Faction $faction)
  {
    $faction->togglePublished();

    $statusMessage = $faction->isPublished() 
      ? __('factions.published_successfully', ['name' => $faction->name])
      : __('factions.unpublished_successfully', ['name' => $faction->name]);

    return back()->with('success', $statusMessage);
  }
}