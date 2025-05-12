<?php

namespace App\Http\Controllers\Game;

use App\Models\HeroClass;
use App\Models\HeroSuperclass;
use App\Services\Game\HeroClassService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\HeroClassRequest;
use Illuminate\Http\Request;

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
  public function index(Request $request)
  {
    $trashed = $request->has('trashed');
    
    // Obtener contadores para las pestañas
    $activeCount = HeroClass::count();
    $trashedCount = HeroClass::onlyTrashed()->count();
    
    $heroClasses = $this->heroClassService->getAllHeroClasses(12, false, $trashed);
    
    return view('admin.hero-classes.index', compact('heroClasses', 'trashed', 'activeCount', 'trashedCount'));
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
        ->with('success', __('hero_classes.created_successfully', ['name' => $heroClass->name]));
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al crear la Clase: ' . $e->getMessage());
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
        ->with('success', __('hero_classes.updated_successfully', ['name' => $heroClass->name]));
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al actualizar la Clase: ' . $e->getMessage());
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
        ->with('success', __('hero_classes.deleted_successfully', ['name' => $heroName]));
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al eliminar la Clase: ' . $e->getMessage());
    }
  }

  /**
   * Restore the specified hero class from trash.
   */
  public function restore($id)
  {
    try {
      $this->heroClassService->restore($id);
      $heroClass = HeroClass::find($id);
      
      return redirect()->route('admin.hero-classes.index', ['trashed' => 1])
        ->with('success', __('hero_classes.restored_successfully', ['name' => $heroClass->name]));
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al restaurar la Clase: ' . $e->getMessage());
    }
  }

  /**
   * Force delete the specified hero class from storage.
   */
  public function forceDelete($id)
  {
    try {
      $heroClass = HeroClass::onlyTrashed()->findOrFail($id);
      $name = $heroClass->name;
      
      $this->heroClassService->forceDelete($id);
      
      return redirect()->route('admin.hero-classes.index', ['trashed' => 1])
        ->with('success', __('hero_classes.force_deleted_successfully', ['name' => $name]));
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al eliminar permanentemente la Clase: ' . $e->getMessage());
    }
  }
}