<?php

namespace App\Http\Controllers\Game;

use App\Models\Hero;
use App\Models\Faction;
use App\Models\HeroRace;
use App\Models\HeroClass;
use App\Models\HeroAbility;
use App\Services\Game\HeroService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\HeroRequest;
use App\Services\Game\HeroAttributesConfigurationService;
use Illuminate\Http\Request;

class HeroController extends Controller
{
  protected $heroService;
  protected $attributesService;

  /**
   * Create a new controller instance.
   *
   * @param HeroService $heroService
   * @param HeroAttributesConfigurationService $attributesService
   */
  public function __construct(
    HeroService $heroService,
    HeroAttributesConfigurationService $attributesService
  ) {
    $this->heroService = $heroService;
    $this->attributesService = $attributesService;
  }

  /**
   * Display a listing of heroes.
   */
  public function index(Request $request)
  {
    $tab = $request->get('tab', 'published'); // Default to published
    
    // Get counters for tabs
    $publishedCount = Hero::where('is_published', true)->count();
    $unpublishedCount = Hero::where('is_published', false)->count();
    $trashedCount = Hero::onlyTrashed()->count();
    
    // Determine filters based on tab
    $trashed = ($tab === 'trashed');
    $onlyPublished = ($tab === 'published');
    $onlyUnpublished = ($tab === 'unpublished');
    
    // Get heroes with all filtering applied
    $heroes = $this->heroService->getAllHeroes(
      12,               // perPage
      $request,         // request for filters
      false,            // withTrashed
      $trashed,         // onlyTrashed
      $onlyPublished,   // onlyPublished
      $onlyUnpublished  // onlyUnpublished
    );
    
    // Create a Hero instance for filter component
    $heroModel = new Hero();
    
    // Get counts from the paginated result
    $totalCount = $heroes->totalCount ?? 0;
    $filteredCount = $heroes->filteredCount ?? 0;
    
    return view('admin.heroes.index', compact(
      'heroes', 
      'tab',
      'trashed', 
      'publishedCount',
      'unpublishedCount',
      'trashedCount', 
      'heroModel', 
      'request',
      'totalCount',
      'filteredCount'
    ));
  }

  /**
   * Show the form for creating a new hero.
   */
  public function create(Request $request)
  {
    $factions = Faction::orderBy('id')->get();
    $heroRaces = HeroRace::orderBy('id')->get();
    $heroClasses = HeroClass::orderBy('id')->get();
    $heroAbilities = HeroAbility::with(['attackRange', 'attackSubtype'])->orderBy('id')->get();
    $attributesConfig = $this->attributesService->getConfiguration();
    
    $selectedFactionId = $request->query('faction_id');

    $selectedAbilities = [];
    
    return view('admin.heroes.create', compact(
      'factions',
      'heroRaces',
      'heroClasses',
      'heroAbilities',
      'attributesConfig',
      'selectedFactionId',
      'selectedAbilities'
    ));
  }

  /**
   * Store a newly created hero in storage.
   */
  public function store(HeroRequest $request)
  {
    $validated = $request->validated();

    try {
      $hero = $this->heroService->create($validated);
      return redirect()->route('admin.heroes.index')
        ->with('success', __('heroes.created_successfully', ['name' => $hero->name]));
    } catch (\Exception $e) {
      return back()
        ->with('error', __('common.errors.create', ['entity' => __('entities.heroes.singular')]) . ' ' . $e->getMessage())
        ->withInput();
    }
  }

  /**
   * Display the specified hero.
   */
  public function show(Hero $hero)
  {
    // Load relationships with all necessary child relationships
    $hero->load([
      'faction', 
      'heroRace', 
      'heroClass', 
      'heroAbilities.attackRange', 
      'heroAbilities.attackSubtype'
    ]);
    
    return view('admin.heroes.show', compact('hero'));
  }

  /**
   * Show the form for editing the specified hero.
   */
  public function edit(Hero $hero)
  {
    $factions = Faction::orderBy('id')->get();
    $heroRaces = HeroRace::orderBy('id')->get();
    $heroClasses = HeroClass::orderBy('id')->get();
    $heroAbilities = HeroAbility::with(['attackRange', 'attackSubtype'])->orderBy('id')->get();
    $attributesConfig = $this->attributesService->getConfiguration();
    
    // Asegúrate de cargar las relaciones necesarias para el héroe
    $hero->load('heroAbilities.attackRange', 'heroAbilities.attackSubtype');
    
    // Get IDs of selected abilities
    $selectedAbilities = $hero->heroAbilities->map(function ($ability) {
      return [
        'id' => $ability->id,
        'copies' => 1 // Heroes don't have multiple copies of abilities
      ];
    })->toArray();
    
    return view('admin.heroes.edit', compact(
      'hero',
      'factions',
      'heroRaces',
      'heroClasses',
      'heroAbilities',
      'selectedAbilities',
      'attributesConfig'
    ));
  }

  /**
   * Update the specified hero in storage.
   */
  public function update(HeroRequest $request, Hero $hero)
  {
    $validated = $request->validated();

    try {
      $this->heroService->update($hero, $validated);
      return redirect()->route('admin.heroes.index')
        ->with('success', __('heroes.updated_successfully', ['name' => $hero->name]));
    } catch (\Exception $e) {
      return back()
        ->with('error', __('common.errors.update', ['entity' => __('entities.heroes.singular')]) . ' ' . $e->getMessage())
        ->withInput();
    }
  }

  /**
   * Remove the specified hero from storage.
   */
  public function destroy(Hero $hero)
  {
    try {
      $heroName = $hero->name;
      $this->heroService->delete($hero);
      
      return redirect()->route('admin.heroes.index')
        ->with('success', __('heroes.deleted_successfully', ['name' => $heroName]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.delete', ['entity' => __('entities.heroes.singular')]) . ' ' . $e->getMessage());
    }
  }

  /**
   * Restore the specified hero from trash.
   */
  public function restore($id)
  {
    try {
      $this->heroService->restore($id);
      $hero = Hero::find($id);
      
      return redirect()->route('admin.heroes.index', ['tab' => 'trashed'])
        ->with('success', __('heroes.restored_successfully', ['name' => $hero->name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.restore', ['entity' => __('entities.heroes.singular')]) . ' ' . $e->getMessage());
    }
  }

  /**
   * Force delete the specified hero from storage.
   */
  public function forceDelete($id)
  {
    try {
      $hero = Hero::onlyTrashed()->findOrFail($id);
      $name = $hero->name;
      
      $this->heroService->forceDelete($id);
      
      return redirect()->route('admin.heroes.index', ['tab' => 'trashed'])
        ->with('success', __('heroes.force_deleted_successfully', ['name' => $name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.force_delete', ['entity' => __('entities.heroes.singular')]) . ' ' . $e->getMessage());
    }
  }

  /**
   * Toggle the published status of the specified hero.
   */
  public function togglePublished(Hero $hero)
  {
    $hero->togglePublished();

    $statusMessage = $hero->isPublished() 
      ? __('heroes.published_successfully', ['name' => $hero->name])
      : __('heroes.unpublished_successfully', ['name' => $hero->name]);

    // Redirect to appropriate tab based on new status
    $tab = $hero->isPublished() ? 'published' : 'unpublished';
    
    return redirect()->route('admin.heroes.index', ['tab' => $tab])
      ->with('success', $statusMessage);
  }
}