<?php

namespace App\Http\Controllers\Game;

use App\Models\AttackSubtype;
use App\Services\Game\AttackSubtypeService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\AttackSubtypeRequest;
use Illuminate\Http\Request;

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
  public function index(Request $request)
{
  $trashed = $request->has('trashed');
  
  // Obtener contadores para las pestañas
  $activeCount = AttackSubtype::count();
  $trashedCount = AttackSubtype::onlyTrashed()->count();
  
  // Obtener attack subtypes con filtros
  $attackSubtypes = $this->attackSubtypeService->getAllAttackSubtypes(
    12,       // perPage
    $request, // request para filtros
    false,    // withTrashed
    $trashed  // onlyTrashed
  );
  
  // Crear una instancia del modelo para los filtros
  $attackSubtypeModel = new AttackSubtype();
  
  // Obtener contadores totales y filtrados
  $totalCount = $attackSubtypes->totalCount ?? AttackSubtype::count();
  $filteredCount = $attackSubtypes->filteredCount ?? count($attackSubtypes);
  
  return view('admin.attack-subtypes.index', compact(
    'attackSubtypes', 
    'trashed', 
    'activeCount', 
    'trashedCount',
    'attackSubtypeModel',
    'request',
    'totalCount',
    'filteredCount'
  ));
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
        ->with('success', __('entities.attack_subtypes.created_successfully', ['name' => $attackSubtype->name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.create', ['entity' => __('entities.attack_subtypes.singular')]))
        ->withInput();
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
        ->with('success', __('entities.attack_subtypes.updated_successfully', ['name' => $attackSubtype->name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.update', ['entity' => __('entities.attack_subtypes.singular')]))
        ->withInput();
    }
  }

  /**
   * Remove the specified attack subtype from storage.
   */
  public function destroy(AttackSubtype $attackSubtype)
  {
    try {
      $attackSubtypeName = $attackSubtype->name;
      $this->attackSubtypeService->delete($attackSubtype);
      
      return redirect()->route('admin.attack-subtypes.index')
        ->with('success', __('entities.attack_subtypes.deleted_successfully', ['name' => $attackSubtypeName]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.delete', ['entity' => __('entities.attack_subtypes.singular')]));
    }
  }

  /**
   * Restore the specified attack subtype from trash.
   */
  public function restore($id)
  {
    try {
      $this->attackSubtypeService->restore($id);
      $attackSubtype = AttackSubtype::find($id);
      
      return redirect()->route('admin.attack-subtypes.index', ['trashed' => 1])
        ->with('success', __('attack_subtypes.restored_successfully', ['name' => $attackSubtype->name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.restore', ['entity' => __('entities.attack_subtypes.singular')]));
    }
  }

  /**
   * Force delete the specified attack subtype from storage.
   */
  public function forceDelete($id)
  {
    try {
      $attackSubtype = AttackSubtype::onlyTrashed()->findOrFail($id);
      $name = $attackSubtype->name;
      
      $this->attackSubtypeService->forceDelete($id);
      
      return redirect()->route('admin.attack-subtypes.index', ['trashed' => 1])
        ->with('success', __('attack_subtypes.force_deleted_successfully', ['name' => $name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.force_delete', ['entity' => __('entities.attack_subtypes.singular')]));
    }
  }
}