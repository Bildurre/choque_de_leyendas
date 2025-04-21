<?php

namespace App\Http\Controllers\Admin;

use App\Models\Hero;
use App\Models\Faction;
use App\Models\HeroRace;
use App\Models\HeroClass;
use App\Models\HeroAbility;
use App\Services\HeroService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HeroRequest;
use App\Services\HeroAttributesConfigurationService;

class HeroController extends Controller
{
  protected $heroService;
  protected $attributesConfigService;

  /**
   * Create a new controller instance.
   *
   * @param HeroService $heroService
   * @param HeroAttributesConfigurationService $attributesConfigService
   */
  public function __construct(
    HeroService $heroService,
    HeroAttributesConfigurationService $attributesConfigService
  ) {
    $this->heroService = $heroService;
    $this->attributesConfigService = $attributesConfigService;
  }

  /**
   * Display a listing of heroes.
   */
  public function index()
  {
    $heroes = $this->heroService->getAllHeroes();
    return view('admin.heroes.index', compact('heroes'));
  }

  /**
   * Show the form for creating a new hero.
   */
  public function create()
  {
    $factions = Faction::all();
    $heroRaces = HeroRace::all();
    $heroClasses = HeroClass::with('heroSuperclass')->get();
    $attributesConfig = $this->attributesConfigService->getConfiguration();
    $abilities = HeroAbility::with(['subtype.type', 'range'])->get();
    
    return view('admin.heroes.create', compact(
      'factions',
      'heroRaces',
      'heroClasses',
      'attributesConfig',
      'abilities'
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
        ->with('success', "El héroe {$hero->name} ha sido creado correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al crear el héroe: ' . $e->getMessage())
        ->withInput();
    }
  }

  /**
   * Display the specified hero.
   */
  public function show(Hero $hero)
  {
    $hero->load(['faction', 'race', 'heroClass', 'heroClass.heroSuperclass']);
    return view('admin.heroes.show', compact('hero'));
  }

  /**
   * Show the form for editing the specified hero.
   */
  public function edit(Hero $hero)
  {
    $factions = Faction::all();
    $heroRaces = HeroRace::all();
    $heroClasses = HeroClass::with('heroSuperclass')->get();
    $attributesConfig = $this->attributesConfigService->getConfiguration();
    
    // Asegurarnos de cargar todas las habilidades con sus relaciones
    $abilities = HeroAbility::with(['subtype.type', 'range'])->get();
    
    // Cargar las habilidades relacionadas con el héroe
    $hero->load('abilities');
    
    return view('admin.heroes.edit', compact(
      'hero',
      'factions',
      'heroRaces',
      'heroClasses',
      'attributesConfig',
      'abilities'
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
        ->with('success', "El héroe {$hero->name} ha sido actualizado correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al actualizar el héroe: ' . $e->getMessage())
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
        ->with('success', "El héroe {$heroName} ha sido eliminado correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al eliminar el héroe: ' . $e->getMessage());
    }
  }
}