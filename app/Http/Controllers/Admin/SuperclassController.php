<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Superclass;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255|unique:superclasses',
      'description' => 'nullable|string',
    ]);

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
  public function update(Request $request, Superclass $superclass)
  {
    $validated = $request->validate([
      'name' => [
        'required',
        'string',
        'max:255',
        Rule::unique('superclasses')->ignore($superclass->id)
      ],
      'description' => 'nullable|string',
    ]);

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