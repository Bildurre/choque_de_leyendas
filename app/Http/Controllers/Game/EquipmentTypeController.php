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
    $trashed = $request->has('trashed');
    $category = $request->input('category');
    
    // Obtener contadores para las pestañas
    $activeCount = EquipmentType::count();
    $trashedCount = EquipmentType::onlyTrashed()->count();
    
    // Obtener conteos por categoría usando Eloquent
    $categoriesQuery = EquipmentType::selectRaw('category, count(*) as count')
      ->groupBy('category');
      
    if ($trashed) {
      $categoriesQuery->onlyTrashed();
    }
    
    $categoriesCollection = $categoriesQuery->get();
    
    $categoryCounts = [];
    foreach ($categoriesCollection as $categoryItem) {
      $categoryCounts[$categoryItem->category] = $categoryItem->count;
    }
    
    // Obtener equipment types con conteos incorporados
    $equipmentTypes = $this->equipmentTypeService->getAllEquipmentTypes(12, false, $trashed, $category);
    
    // Obtener categorías para el filtro
    $categories = EquipmentType::getCategories();
    
    return view('admin.equipment-types.index', compact(
      'equipmentTypes', 
      'trashed', 
      'activeCount', 
      'trashedCount', 
      'categories', 
      'category',
      'categoryCounts'
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
      return back()->with('error', 'Ha ocurrido un error al crear el Tipo de Equipo: ' . $e->getMessage())
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
      return back()->with('error', 'Ha ocurrido un error al actualizar el Tipo de Equipo: ' . $e->getMessage())
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
      return back()->with('error', 'Ha ocurrido un error al eliminar el Tipo de Equipo: ' . $e->getMessage());
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
      return back()->with('error', 'Ha ocurrido un error al restaurar el Tipo de Equipo: ' . $e->getMessage());
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
      return back()->with('error', 'Ha ocurrido un error al eliminar permanentemente el Tipo de Equipo: ' . $e->getMessage());
    }
  }
}