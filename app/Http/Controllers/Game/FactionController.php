<?php

namespace App\Http\Controllers\Game;

use App\Models\Faction;
use App\Services\FactionService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\FactionRequest;

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
  public function index()
  {
    $factions = $this->factionService->getAllFactions();
    return view('admin.factions.index', compact('factions'));
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
    // La validación ya se ha realizado en el request
    $validated = $request->validated();

    try {
      $this->factionService->create($validated);
      return redirect()->route('admin.factions.index')
        ->with('success', 'Facción creada correctamente.');
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al crear la Facción');
    }
  }

  /**
   * Display the specified faction.
   */
  public function show(Faction $faction)
  {
    // Cargar relaciones necesarias para la vista detallada
    $faction->load(['heroes.heroClass', 'cards']);
    $faction->heroes_count = $faction->heroes->count();
    $faction->cards_count = $faction->cards->count();
    
    return view('admin.factions.show', compact('faction'));
  }

  /**
   * Show the form for editing the specified faction.
   */
  public function edit(Faction $faction)
  {
    return view('admin.factions.edit', compact('faction'));
  }

  /**
   * Update the specified faction in storage.
   */
  public function update(FactionRequest $request, Faction $faction)
  {
    // La validación ya se ha realizado en el request
    $validated = $request->validated();

    try {
      // Verificar si se ha solicitado eliminar la imagen
      if (isset($request->remove_icon) && $request->remove_icon == "1") {
          // Agregamos esta información al array de datos validados
          $validated['remove_icon'] = true;
      }

      $this->factionService->update($faction, $validated);
      return redirect()->route('admin.factions.index')
          ->with('success', 'Facción actualizada correctamente.');
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al actualizar la Facción');
    }
  }

  /**
   * Remove the specified faction from storage.
   */
  public function destroy(Faction $faction)
  {
    // Verificar si la facción tiene héroes o cartas asociadas
    if ($faction->heroes()->count() > 0 || $faction->cards()->count() > 0) {
      return redirect()->route('admin.factions.index')
        ->with('error', 'No se puede eliminar la facción porque tiene héroes o cartas asociadas.');
    }
        
    try {
      $this->factionService->delete($faction);
      if ($faction->icon) {
        Storage::disk('public')->delete($faction->icon);
      }
      return redirect()->route('admin.factions.index')
        ->with('success', 'Facción eliminada correctamente.');
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al eliminar la Facción');
    }
  }
}