<?php

namespace App\Http\Controllers\Admin;

use App\Models\Hero;
use App\Models\HeroAbility;
use App\Models\AttackRange;
use App\Models\AttackSubtype;
use App\Http\Controllers\Controller;
use App\Services\HeroAbilityService;
use App\Services\CostTranslatorService;
use App\Http\Requests\Admin\HeroAbility\StoreHeroAbilityRequest;
use App\Http\Requests\Admin\HeroAbility\UpdateHeroAbilityRequest;

class HeroAbilityController extends Controller
{
  protected $heroAbilityService;
  protected $costTranslator;

  /**
   * Create a new controller instance.
   *
   * @param HeroAbilityService $heroAbilityService
   * @param CostTranslatorService $costTranslator
   */
  public function __construct(HeroAbilityService $heroAbilityService, CostTranslatorService $costTranslator)
  {
    $this->heroAbilityService = $heroAbilityService;
    $this->costTranslator = $costTranslator;
  }

  /**
   * Display a listing of hero abilities.
   */
  public function index()
  {
    $heroAbilities = $this->heroAbilityService->getPaginatedAbilities();
    return view('admin.hero-abilities.index', compact('heroAbilities'));
  }

  /**
   * Show the form for creating a new hero ability.
   */
  public function create()
  {
    $subtypes = AttackSubtype::with('type')->get();
    $ranges = AttackRange::all();
    $heroes = Hero::all();
    
    return view('admin.hero-abilities.create', compact('subtypes', 'ranges', 'heroes'));
  }

  /**
   * Store a newly created hero ability in storage.
   */
  public function store(StoreHeroAbilityRequest $request)
  {
    $validated = $request->validated();

    try {
      $heroAbility = $this->heroAbilityService->create($validated);
      return redirect()->route('admin.hero-abilities.index')
        ->with('success', "La habilidad {$heroAbility->name} ha sido creada correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al crear la habilidad: ' . $e->getMessage())
        ->withInput();
    }
  }

  /**
   * Display the specified hero ability.
   */
  public function show(HeroAbility $heroAbility)
  {
    $heroAbility->load(['subtype.type', 'range', 'heroes']);
    return view('admin.hero-abilities.show', compact('heroAbility'));
  }

  /**
   * Show the form for editing the specified hero ability.
   */
  public function edit(HeroAbility $heroAbility)
  {
    $subtypes = AttackSubtype::with('type')->get();
    $ranges = AttackRange::all();
    $heroes = Hero::all();
    $heroAbility->load('heroes');
    
    $selectedHeroes = $heroAbility->heroes->pluck('id')->toArray();
    
    return view('admin.hero-abilities.edit', compact(
      'heroAbility', 
      'subtypes', 
      'ranges', 
      'heroes', 
      'selectedHeroes'
    ));
  }

  /**
   * Update the specified hero ability in storage.
   */
  public function update(UpdateHeroAbilityRequest $request, HeroAbility $heroAbility)
  {
    $validated = $request->validated();

    try {
      $this->heroAbilityService->update($heroAbility, $validated);
      return redirect()->route('admin.hero-abilities.index')
        ->with('success', "La habilidad {$heroAbility->name} ha sido actualizada correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al actualizar la habilidad: ' . $e->getMessage())
        ->withInput();
    }
  }

  /**
   * Remove the specified hero ability from storage.
   */
  public function destroy(HeroAbility $heroAbility)
  {
    try {
      $abilityName = $heroAbility->name;
      $this->heroAbilityService->delete($heroAbility);
      return redirect()->route('admin.hero-abilities.index')
        ->with('success', "La habilidad {$abilityName} ha sido eliminada correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al eliminar la habilidad: ' . $e->getMessage());
    }
  }

  /**
   * Validate a cost string via AJAX
   */
  public function validateCost()
  {
    $cost = request()->get('cost');
    $isValid = $this->costTranslator->isValidCost($cost);
    
    if ($isValid) {
      $formattedCost = $this->costTranslator->translateToArray($cost);
      $totalDiceCount = $this->costTranslator->getTotalDiceCount($cost);
      
      return response()->json([
        'valid' => true,
        'formattedCost' => $formattedCost,
        'totalDiceCount' => $totalDiceCount
      ]);
    }
    
    return response()->json([
      'valid' => false,
      'message' => 'El coste debe contener solo los caracteres R, G, B y tener un máximo de 5 dados.'
    ]);
  }
}