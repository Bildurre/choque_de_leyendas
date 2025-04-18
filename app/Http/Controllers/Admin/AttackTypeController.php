<?php

namespace App\Http\Controllers\Admin;

use App\Models\AttackType;
use App\Http\Controllers\Controller;
use App\Services\AttackTypeService;
use App\Http\Requests\Admin\AttackTypeRequest;

class AttackTypeController extends Controller
{
  protected $attackTypeService;

  /**
   * Create a new controller instance.
   *
   * @param AttackTypeService $attackTypeService
   */
  public function __construct(AttackTypeService $attackTypeService)
  {
    $this->attackTypeService = $attackTypeService;
  }

  /**
   * Display a listing of attack types.
   */
  public function index()
  {
    $attackTypes = $this->attackTypeService->getAllTypes();
    return view('admin.attack-types.index', compact('attackTypes'));
  }

  /**
   * Show the form for creating a new attack type.
   */
  public function create()
  {
    return view('admin.attack-types.create');
  }

  /**
   * Store a newly created attack type in storage.
   */
  public function store(AttackTypeRequest $request)
  {
    $validated = $request->validated();

    try {
      $attackType = $this->attackTypeService->create($validated);
      return redirect()->route('admin.attack-types.index')
        ->with('success', "El tipo de habilidad {$attackType->name} ha sido creado correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al crear el tipo de habilidad: ' . $e->getMessage());
    }
  }

  /**
   * Show the form for editing the specified attack type.
   */
  public function edit(AttackType $attackType)
  {
    return view('admin.attack-types.edit', compact('attackType'));
  }

  /**
   * Update the specified attack type in storage.
   */
  public function update(AttackTypeRequest $request, AttackType $attackType)
  {
    $validated = $request->validated();

    try {
      $this->attackTypeService->update($attackType, $validated);
      return redirect()->route('admin.attack-types.index')
        ->with('success', "El tipo de habilidad {$attackType->name} ha sido actualizado correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al actualizar el tipo de habilidad: ' . $e->getMessage());
    }
  }

  /**
   * Remove the specified attack type from storage.
   */
  public function destroy(AttackType $attackType)
  {
    try {
      $typeName = $attackType->name;
      $this->attackTypeService->delete($attackType);
      return redirect()->route('admin.attack-types.index')
        ->with('success', "El tipo de habilidad {$typeName} ha sido eliminado correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al borrar el tipo de habilidad: ' . $e->getMessage());
    }
  }
}