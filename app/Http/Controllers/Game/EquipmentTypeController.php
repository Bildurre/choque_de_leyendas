<?php

namespace App\Http\Controllers\Game;

use App\Models\EquipmentType;
use App\Services\Game\EquipmentTypeService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\EquipmentTypeRequest;
use Illuminate\Http\Request;

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
  public function index(Request $request)
  {
    $tab = $request->get('tab', 'published');
    $trashed = ($tab === 'trashed');
    
    // Obtener contadores para las pestañas
    $activeCount = EquipmentType::count();
    $trashedCount = EquipmentType::onlyTrashed()->count();
    
    // Obtener equipment types con filtrado y paginación
    $equipmentTypes = $this->equipmentTypeService->getAllEquipmentTypes(
      $request, // request para filtros
      12,       // perPage
      false,    // withTrashed
      $trashed  // onlyTrashed
    );
    
    // Crear instancia de modelo para componente de filtros
    $equipmentTypeModel = new EquipmentType();
    
    // Obtener conteos de la respuesta paginada
    $totalCount = $equipmentTypes->totalCount ?? 0;
    $filteredCount = $equipmentTypes->filteredCount ?? 0;
    
    return view('admin.equipment-types.index', compact(
      'equipmentTypes', 
      'trashed', 
      'activeCount', 
      'trashedCount',
      'equipmentTypeModel',
      'request',
      'totalCount',
      'filteredCount'
    ));
  }

  /**
   * Show the form for creating a new equipment type.
   */
  public function create()
  {
    $categories = EquipmentType::getCategories();
    return view('admin.equipment-types.create', compact('categories'));
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
        ->with('success', __('equipment_types.created_successfully', ['name' => $equipmentType->name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.create', ['entity' => __('entities.equipment_types.singular')]))
        ->withInput();
    }
  }

  /**
   * Show the form for editing the specified equipment type.
   */
  public function edit(EquipmentType $equipmentType)
  {
    $categories = EquipmentType::getCategories();
    return view('admin.equipment-types.edit', compact('equipmentType', 'categories'));
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
        ->with('success', __('equipment_types.updated_successfully', ['name' => $equipmentType->name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.update', ['entity' => __('entities.equipment_types.singular')]))
        ->withInput();
    }
  }

  /**
   * Remove the specified equipment type from storage.
   */
  public function destroy(EquipmentType $equipmentType)
  {
    try {
      $equipmentTypeName = $equipmentType->name;
      $this->equipmentTypeService->delete($equipmentType);
      
      return redirect()->route('admin.equipment-types.index')
        ->with('success', __('equipment_types.deleted_successfully', ['name' => $equipmentTypeName]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.delete', ['entity' => __('entities.equipment_types.singular')]));
    }
  }

  /**
   * Restore the specified equipment type from trash.
   */
  public function restore($id)
  {
    try {
      $this->equipmentTypeService->restore($id);
      $equipmentType = EquipmentType::find($id);
      
      return redirect()->route('admin.equipment-types.index', ['trashed' => 1])
        ->with('success', __('equipment_types.restored_successfully', ['name' => $equipmentType->name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.restore', ['entity' => __('entities.equipment_types.singular')]));
    }
  }

  /**
   * Force delete the specified equipment type from storage.
   */
  public function forceDelete($id)
  {
    try {
      $equipmentType = EquipmentType::onlyTrashed()->findOrFail($id);
      $name = $equipmentType->name;
      
      $this->equipmentTypeService->forceDelete($id);
      
      return redirect()->route('admin.equipment-types.index', ['trashed' => 1])
        ->with('success', __('equipment_types.force_deleted_successfully', ['name' => $name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.force_delete', ['entity' => __('entities.equipment_types.singular')]));
    }
  }
}