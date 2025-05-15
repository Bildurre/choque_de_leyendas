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
    
    // Obtener hero classes con filtrado y paginación
    $heroClasses = $this->heroClassService->getAllHeroClasses(
      $request, // request para filtros
      12,       // perPage
      false,    // withTrashed
      $trashed  // onlyTrashed
    );
    
    // Crear instancia de modelo para componente de filtros
    $heroClassModel = new HeroClass();
    
    // Obtener conteos de la respuesta paginada
    $totalCount = $heroClasses->totalCount ?? 0;
    $filteredCount = $heroClasses->filteredCount ?? 0;
    
    return view('admin.hero-classes.index', compact(
      'heroClasses', 
      'trashed', 
      'activeCount', 
      'trashedCount',
      'heroClassModel',
      'request',
      'totalCount',
      'filteredCount'
    ));
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
      return back()->with('error', __('common.errors.create', ['entity' => __('entities.hero_classes.singular')]));
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
      return back()->with('error', __('common.errors.update', ['entity' => __('entities.hero_classes.singular')]));
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
      return back()->with('error', __('common.errors.delete', ['entity' => __('entities.hero_classes.singular')]));
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
      return back()->with('error', __('common.errors.restore', ['entity' => __('entities.hero_classes.singular')]));
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
      return back()->with('error', __('common.errors.force_delete', ['entity' => __('entities.hero_classes.singular')]));
    }
  }
}