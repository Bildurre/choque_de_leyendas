<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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
  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255|unique:factions',
      'lore_text' => 'nullable|string',
      'color' => 'required|string|max:7|regex:/^#[0-9A-F]{6}$/i',
      'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

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
  public function update(Request $request, Faction $faction)
  {
    $validated = $request->validate([
      'name' => [
        'required',
        'string',
        'max:255',
        Rule::unique('factions')->ignore($faction->id),
      ],
      'lore_text' => 'nullable|string',
      'color' => 'required|string|max:7|regex:/^#[0-9A-F]{6}$/i',
      'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $faction->name = $validated['name'];
    $faction->lore_text = $validated['lore_text'];
    $faction->color = $validated['color'];
    
    // Determinar automáticamente el color del texto
    $faction->setTextColorBasedOnBackground();
    
    // Actualizar icono si existe
    if ($request->hasFile('icon')) {
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