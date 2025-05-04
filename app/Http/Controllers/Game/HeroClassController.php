<?php

namespace App\Http\Controllers\Game;

use App\Models\HeroClass;
use App\Models\HeroSuperclass;
use App\Services\Game\HeroClassService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\HeroClassRequest;

class HeroClassController extends Controller
{
  protected $heroClassService;

  /**
   * Create a new controller instance.
   *
   * @param HeroClassService $heroClassService
   */
  public function __construct(HeroClassService $heroClassService)
  {
    $this->heroClassService = $heroClassService;
  }

  /**
   * Display a listing of hero classes.
   */
  public function index()
  {
    $heroClasses = $this->heroClassService->getAllHeroClasses();
    
    // Obtenemos el conteo de héroes por cada clase
    // Asumiendo que tienes una relación heroes() en el modelo HeroClass
    $heroClasses->each(function($heroClass) {
      $heroClass->heroCount = $heroClass->heroes()->count();
    });
    
    return view('admin.hero-classes.index', compact('heroClasses'));
  }

  /**
   * Show the form for creating a new hero class.
   */
  public function create()
  {
    $heroSuperclasses = HeroSuperclass::all();
    return view('admin.hero-classes.create', compact('heroSuperclasses'));
  }

  /**
   * Store a newly created hero class in storage.
   */
  public function store(HeroClassRequest $request)
  {
    $validated = $request->validated();

    try {
      $heroClass = $this->heroClassService->create($validated);
      return redirect()->route('admin.hero-classes.index')
        ->with('success', "La clase {$heroClass->name} ha sido creada correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al crear la Clase');
    }
  }

  /**
   * Show the form for editing the specified hero class.
   */
  public function edit(HeroClass $heroClass)
  {
    $heroSuperclasses = HeroSuperclass::all();
    return view('admin.hero-classes.edit', compact('heroClass', 'heroSuperclasses'));
  }

  /**
   * Update the specified hero class in storage.
   */
  public function update(HeroClassRequest $request, HeroClass $heroClass)
  {
    $validated = $request->validated();

    try {
      $this->heroClassService->update($heroClass, $validated);
      return redirect()->route('admin.hero-classes.index')
        ->with('success', "La clase {$heroClass->name} ha sido actualizada correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al actualizar la Clase');
    }
  }

    /**
     * Remove the specified hero class from storage.
     */
    public function destroy(HeroClass $heroClass)
    {
      try {
        $heroName = $heroClass->name;
        $this->heroClassService->delete($heroClass);
        
        return redirect()->route('admin.hero-classes.index')
          ->with('success', "La clase {$heroName} ha sido eliminada correctamente.");
      } catch (\Exception $e) {
        return back()->with('error', 'Ha ocurrido un error al eliminar la Clase');
      }
    }
}