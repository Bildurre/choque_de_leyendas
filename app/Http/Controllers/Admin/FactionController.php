<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faction;
use App\Http\Requests\Admin\Faction\StoreFactionRequest;
use App\Http\Requests\Admin\Faction\UpdateFactionRequest;
use Illuminate\Support\Facades\Storage;

class FactionController extends Controller
{
  /**
   * Display a listing of factions.
   */
  public function index()
  {
    $factions = Faction::all();
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
  public function store(StoreFactionRequest $request)
  {
    // La validación ya se ha realizado en el request
    $validated = $request->validated();

    // Crear facción
    $faction = new Faction();
    $faction->name = $validated['name'];
    $faction->lore_text = $validated['lore_text'];
    $faction->color = $validated['color'];
    
    // Determinar automáticamente el color del texto
    $faction->setTextColorBasedOnBackground();
    
    // Guardar icono si existe
    if ($request->hasFile('icon')) {
      $iconPath = $request->file('icon')->store('faction-icons', 'public');
      $faction->icon = $iconPath;
    }
    
    $faction->save();
    
    return redirect()->route('admin.factions.index')
      ->with('success', 'Facción creada correctamente.');
  }

  /**
   * Display the specified faction.
   */
  public function show(Faction $faction)
  {
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
  public function update(UpdateFactionRequest $request, Faction $faction)
  {
    // La validación ya se ha realizado en el request
    $validated = $request->validated();

    $faction->name = $validated['name'];
    $faction->lore_text = $validated['lore_text'];
    $faction->color = $validated['color'];
    
    // Determinar automáticamente el color del texto
    $faction->setTextColorBasedOnBackground();
    
    // Manejar la eliminación del icono si se seleccionó la opción
    if ($request->has('remove_icon') && $request->remove_icon == "1") {
      if ($faction->icon) {
        Storage::disk('public')->delete($faction->icon);
        $faction->icon = null;
      }
    }
    // Actualizar icono si existe un nuevo archivo
    elseif ($request->hasFile('icon')) {
      // Eliminar el icono anterior si existe
      if ($faction->icon) {
        Storage::disk('public')->delete($faction->icon);
      }
      
      $iconPath = $request->file('icon')->store('faction-icons', 'public');
      $faction->icon = $iconPath;
    }
    
    $faction->save();
    
    return redirect()->route('admin.factions.index')
      ->with('success', 'Facción actualizada correctamente.');
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
    
    // Eliminar el icono si existe
    if ($faction->icon) {
      Storage::disk('public')->delete($faction->icon);
    }
    
    $faction->delete();
    
    return redirect()->route('admin.factions.index')
      ->with('success', 'Facción eliminada correctamente.');
  }
}