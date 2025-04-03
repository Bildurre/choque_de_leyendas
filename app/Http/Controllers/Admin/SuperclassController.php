<?php

namespace App\Http\Controllers\Admin;

use App\Models\Superclass;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Superclass\StoreSuperclassRequest;
use App\Http\Requests\Admin\Superclass\UpdateSuperclassRequest;

class SuperclassController extends Controller
{
  /**
   * Display a listing of superclasses.
   */
  public function index()
  {
    $superclasses = Superclass::withCount('heroClasses')->get();
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

    $superclass = Superclass::create($validated);

    return redirect()->route('admin.superclasses.index')
      ->with('success', "La superclase {$superclass->name} ha sido creada correctamente.");
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

    $superclass->update($validated);

    return redirect()->route('admin.superclasses.index')
      ->with('success', "La superclase {$superclass->name} ha sido actualizada correctamente.");
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

    $superclassName = $superclass->name;
    $superclass->delete();

    return redirect()->route('admin.superclasses.index')
      ->with('success', "La superclase {$superclassName} ha sido eliminada correctamente.");
  }
}