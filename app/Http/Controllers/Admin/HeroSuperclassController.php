<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSuperclass;
use App\Services\HeroSuperclassService;
use App\Http\Requests\Admin\HeroSuperclass\StoreHeroSuperclassRequest;
use App\Http\Requests\Admin\HeroSuperclass\UpdateHeroSuperclassRequest;

class HeroSuperclassController extends Controller
{
  protected $heroSuperclassService;

  /**
   * Create a new controller instance.
   *
   * @param HeroSuperclassService $heroSuperclassService
   */
  public function __construct(HeroSuperclassService $heroSuperclassService)
  {
    $this->heroSuperclassService = $heroSuperclassService;
  }

  /**
   * Display a listing of superclasses.
   */
  public function index()
  {
    $heroSuperclasses = $this->heroSuperclassService->getAllHeroSuperclasses();
    return view('admin.hero-superclasses.index', compact('heroSuperclasses'));
  }

  /**
   * Show the form for creating a new superclass.
   */
  public function create()
  {
    return view('admin.hero-superclasses.create');
  }

  /**
   * Store a newly created superclass in storage.
   */
  public function store(StoreHeroSuperclassRequest $request)
  {
    $validated = $request->validated();

    try {
      $heroSuperclass = $this->heroSuperclassService->create($validated);
      return redirect()->route('admin.hero-superclasses.index')
        ->with('success', "La superclase {$heroSuperclass->name} ha sido creada correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al crear la Superclase: ' . $e->getMessage());
    }
  }

  /**
   * Show the form for editing the specified superclass.
   */
  public function edit(HeroSuperclass $heroSuperclass)
  {
    return view('admin.hero-superclasses.edit', compact('heroSuperclass'));
  }

  /**
   * Update the specified superclass in storage.
   */
  public function update(UpdateHeroSuperclassRequest $request, HeroSuperclass $heroSuperclass)
  {
    $validated = $request->validated();

    try {
      $this->heroSuperclassService->update($heroSuperclass, $validated);
      return redirect()->route('admin.hero-superclasses.index')
        ->with('success', "La superclase {$heroSuperclass->name} ha sido actualizada correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al actualizar la Superclase: ' . $e->getMessage());
    }
  }

  /**
   * Remove the specified superclass from storage.
   */
  public function destroy(HeroSuperclass $heroSuperclass)
  {
    try {
      $superclassName = $heroSuperclass->name;
      $this->heroSuperclassService->delete($heroSuperclass);

      return redirect()->route('admin.hero-superclasses.index')
        ->with('success', "La superclase {$superclassName} ha sido eliminada correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al borrar la Superclase: ' . $e->getMessage());
    }
  }
}