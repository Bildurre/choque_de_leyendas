<?php

namespace App\Http\Controllers\Admin;

use App\Models\Superclass;
use Illuminate\Http\Request;
use App\Services\SuperclassService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Superclass\StoreSuperclassRequest;
use App\Http\Requests\Admin\Superclass\UpdateSuperclassRequest;

class SuperclassController extends Controller
{
  protected $superclassService;

  /**
   * Create a new controller instance.
   *
   * @param SuperclassService $superclassService
   */
  public function __construct(SuperclassService $superclassService)
  {
    $this->superclassService = $superclassService;
  }

  /**
   * Display a listing of superclasses.
   */
  public function index()
  {
    $superclasses = $this->superclassService->getAllSuperclasses();
    return view('admin.superclasses.index', compact('superclasses'));
  }

  /**
   * Show the form for creating a new superclass.
   */
  public function create()
  {
    return view('admin.superclasses.create');
  }

  /**
   * Store a newly created superclass in storage.
   */
  public function store(StoreSuperclassRequest $request)
  {
    $validated = $request->validated();

    try {
      $superclass = $this->superclassService->create($validated);
      return redirect()->route('admin.superclasses.index')
        ->with('success', "La superclase {$superclass->name} ha sido creada correctamente.");
    } catch (\Exception $e) {
      return back()->withInput()->with('error', $e->getMessage());
    }
  }

  /**
   * Show the form for editing the specified superclass.
   */
  public function edit(Superclass $superclass)
  {
    return view('admin.superclasses.edit', compact('superclass'));
  }

  /**
   * Update the specified superclass in storage.
   */
  public function update(UpdateSuperclassRequest $request, Superclass $superclass)
  {
    $validated = $request->validated();

    try {
      $this->superclassService->update($superclass, $validated);
      return redirect()->route('admin.superclasses.index')
        ->with('success', "La superclase {$superclass->name} ha sido actualizada correctamente.");
    } catch (\Exception $e) {
      return back()->withInput()->with('error', $e->getMessage());
    }
  }

  /**
   * Remove the specified superclass from storage.
   */
  public function destroy(Superclass $superclass)
  {
    // Verificar si hay clases de héroe que usan esta superclase
    if ($superclass->heroClasses()->count() > 0) {
      return redirect()->route('admin.superclasses.index')
        ->with('error', "No se puede eliminar la superclase {$superclass->name} porque está siendo utilizada por clases de héroe.");
    }

    try {
      $superclassName = $superclass->name;
      $this->superclassService->delete($superclass);

      return redirect()->route('admin.superclasses.index')
        ->with('success', "La superclase {$superclassName} ha sido eliminada correctamente.");
    } catch (\Exception $e) {
      return redirect()->route('admin.superclasses.index')
        ->with('error', $e->getMessage());
    }
  }
}