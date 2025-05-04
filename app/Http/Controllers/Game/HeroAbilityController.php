<?php

namespace App\Http\Controllers\Game;

use App\Models\AttackRange;
use App\Models\HeroAbility;
use App\Models\AttackSubtype;
use App\Http\Controllers\Controller;
use App\Services\Game\HeroAbilityService;
use App\Services\Game\CostTranslatorService;
use App\Http\Requests\Game\HeroAbilityRequest;

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
    $heroAbilities = $this->heroAbilityService->getPaginatedAbilities(12);
    return view('admin.hero-abilities.index', compact('heroAbilities'));
  }

  /**
   * Show the form for creating a new hero ability.
   */
  public function create()
  {
    $ranges = AttackRange::all();
    $subtypes = AttackSubtype::all();
    
    return view('admin.hero-abilities.create', compact('subtypes', 'ranges'));
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
        ->with('success', "La habilidad {$heroAbility->name} ha sido creada correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al crear la habilidad: ' . $e->getMessage())
        ->withInput();
    }
  }

  /**
   * Show the form for editing the specified hero ability.
   */
  public function edit(HeroAbility $heroAbility)
  {
    $ranges = AttackRange::all();
    $subtypes = AttackSubtype::all();
    $heroAbility->load('heroes');
    
    return view('admin.hero-abilities.edit', compact(
      'heroAbility',
      'ranges',
      'subtypes'
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