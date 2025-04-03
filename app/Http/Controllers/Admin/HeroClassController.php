<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroClass;
use App\Models\Superclass;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HeroClassController extends Controller
{
  /**
   * Display a listing of hero classes.
   */
  public function index()
  {
     // Cargar las clases con sus superclases relacionadas
    $heroClasses = HeroClass::with('superclass')->get();
    
    // Modificar la colección para agregar el nombre de la superclase como propiedad
    $heroClasses->each(function($heroClass) {
      $heroClass->superclass_label = $heroClass->superclass ? $heroClass->superclass->name : 'Sin superclase';
    });
    
    return view('admin.hero-classes.index', compact('heroClasses'));
  }

  /**
   * Show the form for creating a new hero class.
   */
  public function create()
  {
    $superclasses = Superclass::all();
    return view('admin.hero-classes.create', compact('superclasses'));
  }

  /**
   * Store a newly created hero class in storage.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255|unique:hero_classes',
      'passive' => 'nullable|string',
      'superclass_id' => 'required|exists:superclasses,id',
      'agility_modifier' => 'required|integer|between:-2,2',
      'mental_modifier' => 'required|integer|between:-2,2',
      'will_modifier' => 'required|integer|between:-2,2',
      'strength_modifier' => 'required|integer|between:-2,2',
      'armor_modifier' => 'required|integer|between:-2,2'
    ]);

    $heroClass = HeroClass::create($validated);

    return redirect()->route('admin.hero-classes.index')
      ->with('success', "La clase {$heroClass->name} ha sido creada correctamente.");
  }

  /**
   * Show the form for editing the specified hero class.
   */
  public function edit(HeroClass $heroClass)
  {
    $superclasses = Superclass::all();
    return view('admin.hero-classes.edit', compact('heroClass', 'superclasses'));
  }

  /**
   * Update the specified hero class in storage.
   */
  public function update(Request $request, HeroClass $heroClass)
  {
    $validated = $request->validate([
      'name' => [
        'required',
        'string',
        'max:255',
        Rule::unique('hero_classes')->ignore($heroClass->id)
      ],
      'passive' => 'nullable|string',
      'superclass_id' => 'required|exists:superclasses,id',
      'agility_modifier' => 'required|integer|between:-2,2',
      'mental_modifier' => 'required|integer|between:-2,2',
      'will_modifier' => 'required|integer|between:-2,2',
      'strength_modifier' => 'required|integer|between:-2,2',
      'armor_modifier' => 'required|integer|between:-2,2'
    ]);

    $heroClass->update($validated);

    return redirect()->route('admin.hero-classes.index')
      ->with('success', "La clase {$heroClass->name} ha sido actualizada correctamente.");
  }

    /**
     * Remove the specified hero class from storage.
     */
    public function destroy(HeroClass $heroClass)
    {
        $heroName = $heroClass->name;
        $heroClass->delete();

        return redirect()->route('admin.hero-classes.index')
            ->with('success', "La clase {$heroName} ha sido eliminada correctamente.");
    }
}