<?php

namespace App\Http\Controllers\Game;

use App\Models\AttackSubtype;
use App\Http\Controllers\Controller;
use App\Services\AttackSubtypeService;
use App\Http\Requests\Admin\AttackSubtypeRequest;

class AttackSubtypeController extends Controller
{
  protected $attackSubtypeService;

  /**
   * Create a new controller instance.
   *
   * @param AttackSubtypeService $attackSubtypeService
   */
  public function __construct(AttackSubtypeService $attackSubtypeService)
  {
    $this->attackSubtypeService = $attackSubtypeService;
  }

  /**
   * Display a listing of attack subtypes.
   */
  public function index()
  {
    $attackSubtypes = $this->attackSubtypeService->getAllSubtypes();
    return view('admin.attack-subtypes.index', compact('attackSubtypes'));
  }

  /**
   * Show the form for creating a new attack subtype.
   */
  public function create()
  {
    return view('admin.attack-subtypes.create');
  }

  /**
   * Store a newly created attack subtype in storage.
   */
  public function store(AttackSubtypeRequest $request)
  {
    $validated = $request->validated();

    try {
      $attackSubtype = $this->attackSubtypeService->create($validated);
      return redirect()->route('admin.attack-subtypes.index')
        ->with('success', "El subtipo de habilidad {$attackSubtype->name} ha sido creado correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al crear el subtipo de habilidad: ' . $e->getMessage());
    }
  }

  /**
   * Show the form for editing the specified attack subtype.
   */
  public function edit(AttackSubtype $attackSubtype)
  {
    return view('admin.attack-subtypes.edit', compact('attackSubtype'));
  }

  /**
   * Update the specified attack subtype in storage.
   */
  public function update(AttackSubtypeRequest $request, AttackSubtype $attackSubtype)
  {
    $validated = $request->validated();

    try {
      $this->attackSubtypeService->update($attackSubtype, $validated);
      return redirect()->route('admin.attack-subtypes.index')
        ->with('success', "El subtipo de habilidad {$attackSubtype->name} ha sido actualizado correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al actualizar el subtipo de habilidad: ' . $e->getMessage());
    }
  }

  /**
   * Remove the specified attack subtype from storage.
   */
  public function destroy(AttackSubtype $attackSubtype)
  {
    try {
      $subtypeName = $attackSubtype->name;
      $this->attackSubtypeService->delete($attackSubtype);
      return redirect()->route('admin.attack-subtypes.index')
        ->with('success', "El subtipo de habilidad {$subtypeName} ha sido eliminado correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }
  }
}