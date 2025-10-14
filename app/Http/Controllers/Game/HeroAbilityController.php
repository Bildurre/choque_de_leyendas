<?php

namespace App\Http\Controllers\Game;

use App\Models\HeroAbility;
use App\Models\AttackRange;
use App\Models\AttackSubtype;
use App\Services\Game\HeroAbilityService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\HeroAbilityRequest;
use Illuminate\Http\Request;

class HeroAbilityController extends Controller
{
  protected $heroAbilityService;

  /**
   * Create a new controller instance.
   *
   * @param HeroAbilityService $heroAbilityService
   */
  public function __construct(HeroAbilityService $heroAbilityService)
  {
    $this->heroAbilityService = $heroAbilityService;
  }

  /**
   * Display a listing of hero abilities.
   */
  public function index(Request $request)
  {
    $trashed = $request->has('trashed');
    
    // Get counters for tabs
    $activeCount = HeroAbility::count();
    $trashedCount = HeroAbility::onlyTrashed()->count();
    
    // Get hero abilities with filtering and pagination
    $heroAbilities = $this->heroAbilityService->getAllHeroAbilities(
      $request, // request para filtros
      12,       // perPage
      false,    // withTrashed
      $trashed  // onlyTrashed
    );
    
    // Create a HeroAbility instance for filter component
    $heroAbilityModel = new HeroAbility();
    
    // Get counts from the paginated result
    $totalCount = $heroAbilities->totalCount ?? 0;
    $filteredCount = $heroAbilities->filteredCount ?? 0;
    
    return view('admin.hero-abilities.index', compact(
      'heroAbilities', 
      'trashed', 
      'activeCount', 
      'trashedCount',
      'heroAbilityModel',
      'request',
      'totalCount',
      'filteredCount'
    ));
  }

  /**
   * Show the form for creating a new hero ability.
   */
  public function create()
  {
    $attackRanges = AttackRange::orderBy('id')->get();
    $attackSubtypes = AttackSubtype::orderBy('id')->get();
    
    return view('admin.hero-abilities.create', compact(
      'attackRanges',
      'attackSubtypes'
    ));
  }

  /**
   * Store a newly created hero ability in storage.
   */
  public function store(HeroAbilityRequest $request)
  {
    $validated = $request->validated();

    try {
      $heroAbility = $this->heroAbilityService->create($validated);
      return redirect()->route('admin.hero-abilities.index')
        ->with('success', __('hero_abilities.created_successfully', ['name' => $heroAbility->name]));
    } catch (\Exception $e) {
      return back()
        ->with('error', __('common.errors.create', ['entity' => __('entities.hero_abilities.singular')]))
        ->withInput();
    }
  }

  /**
   * Show the form for editing the specified hero ability.
   */
  public function edit(HeroAbility $heroAbility)
  {
    $attackRanges = AttackRange::orderBy('id')->get();
    $attackSubtypes = AttackSubtype::orderBy('id')->get();
    
    return view('admin.hero-abilities.edit', compact(
      'heroAbility',
      'attackRanges',
      'attackSubtypes'
    ));
  }

  /**
   * Update the specified hero ability in storage.
   */
  public function update(HeroAbilityRequest $request, HeroAbility $heroAbility)
  {
    $validated = $request->validated();

    try {
      $this->heroAbilityService->update($heroAbility, $validated);
      return redirect()->route('admin.hero-abilities.index')
        ->with('success', __('hero_abilities.updated_successfully', ['name' => $heroAbility->name]));
    } catch (\Exception $e) {
      return back()
        ->with('error', __('common.errors.update', ['entity' => __('entities.hero_abilities.singular')]))
        ->withInput();
    }
  }

  /**
   * Remove the specified hero ability from storage.
   */
  public function destroy(HeroAbility $heroAbility)
  {
    try {
      $heroAbilityName = $heroAbility->name;
      $this->heroAbilityService->delete($heroAbility);
      
      return redirect()->route('admin.hero-abilities.index')
        ->with('success', __('hero_abilities.deleted_successfully', ['name' => $heroAbilityName]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.delete', ['entity' => __('entities.hero_abilities.singular')]));
    }
  }

  /**
   * Restore the specified hero ability from trash.
   */
  public function restore($id)
  {
    try {
      $this->heroAbilityService->restore($id);
      $heroAbility = HeroAbility::find($id);
      
      return redirect()->route('admin.hero-abilities.index', ['trashed' => 1])
        ->with('success', __('hero_abilities.restored_successfully', ['name' => $heroAbility->name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.restore', ['entity' => __('entities.hero_abilities.singular')]));
    }
  }

  /**
   * Force delete the specified hero ability from storage.
   */
  public function forceDelete($id)
  {
    try {
      $heroAbility = HeroAbility::onlyTrashed()->findOrFail($id);
      $name = $heroAbility->name;
      
      $this->heroAbilityService->forceDelete($id);
      
      return redirect()->route('admin.hero-abilities.index', ['trashed' => 1])
        ->with('success', __('hero_abilities.force_deleted_successfully', ['name' => $name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.force_delete', ['entity' => __('entities.hero_abilities.singular')]));
    }
  }
}