<?php

namespace App\Http\Controllers\Game;

use App\Models\EquipmentType;
use App\Http\Controllers\Controller;
use App\Services\EquipmentTypeService;
use App\Http\Requests\Admin\EquipmentTypeRequest;

class EquipmentTypeController extends Controller
{
  protected $equipmentTypeService;

  /**
   * Create a new controller instance.
   *
   * @param EquipmentTypeService $equipmentTypeService
   */
  public function __construct(EquipmentTypeService $equipmentTypeService)
  {
    $this->equipmentTypeService = $equipmentTypeService;
  }

  /**
   * Display a listing of equipment types.
   */
  public function index()
  {
    $equipmentTypes = $this->equipmentTypeService->getAllTypes();
    return view('admin.equipment-types.index', compact('equipmentTypes'));
  }

  /**
   * Show the form for creating a new equipment type.
   */
  public function create()
  {
    return view('admin.equipment-types.create');
  }

  /**
   * Store a newly created equipment type in storage.
   */
  public function store(EquipmentTypeRequest $request)
  {
    $validated = $request->validated();

    try {
      $equipmentType = $this->equipmentTypeService->create($validated);
      return redirect()->route('admin.equipment-types.index')
        ->with('success', "El tipo de equipo {$equipmentType->name} ha sido creado correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al crear el tipo de equipo: ' . $e->getMessage());
    }
  }

  /**
   * Show the form for editing the specified equipment type.
   */
  public function edit(EquipmentType $equipmentType)
  {
    return view('admin.equipment-types.edit', compact('equipmentType'));
  }

  /**
   * Update the specified equipment type in storage.
   */
  public function update(EquipmentTypeRequest $request, EquipmentType $equipmentType)
  {
    $validated = $request->validated();

    try {
      $this->equipmentTypeService->update($equipmentType, $validated);
      return redirect()->route('admin.equipment-types.index')
        ->with('success', "El tipo de equipo {$equipmentType->name} ha sido actualizado correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al actualizar el tipo de equipo: ' . $e->getMessage());
    }
  }

  /**
   * Remove the specified equipment type from storage.
   */
  public function destroy(EquipmentType $equipmentType)
  {
    try {
      $typeName = $equipmentType->name;
      $this->equipmentTypeService->delete($equipmentType);
      return redirect()->route('admin.equipment-types.index')
        ->with('success', "El tipo de equipo {$typeName} ha sido eliminado correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }
  }
}