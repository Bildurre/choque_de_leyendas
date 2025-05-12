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
      $type = $request->input('type');
      
      // Obtener contadores para las pestañas
      $activeCount = AttackSubtype::count();
      $trashedCount = AttackSubtype::onlyTrashed()->count();
      
      // Obtener conteos por tipo directamente con consultas
      $typesQuery = AttackSubtype::selectRaw('type, count(*) as count')
          ->groupBy('type');
      
      if ($trashed) {
          $typesQuery->onlyTrashed();
      }
      
      $typeCountsCollection = $typesQuery->get();
      
      $typeCounts = [];
      foreach ($typeCountsCollection as $typeCount) {
          $typeCounts[$typeCount->type] = $typeCount->count;
      }
      
      // Obtener attack subtypes con conteos incorporados
      $attackSubtypes = $this->attackSubtypeService->getAllAttackSubtypes(12, false, $trashed, $type);
      
      // Obtener tipos para el filtro
      $types = AttackSubtype::getTypes();
      
      return view('admin.attack-subtypes.index', compact(
          'attackSubtypes', 
          'trashed', 
          'activeCount', 
          'trashedCount', 
          'types', 
          'type',
          'typeCounts'
      ));
  }

  /**
   * Show the form for creating a new attack subtype.
   */
  public function create()
  {
    $types = AttackSubtype::getTypes();
    return view('admin.attack-subtypes.create', compact('types'));
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
        ->with('success', __('attack_subtypes.created_successfully', ['name' => $attackSubtype->name]));
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al crear el Subtipo de Ataque: ' . $e->getMessage())
        ->withInput();
    }
  }

  /**
   * Show the form for editing the specified attack subtype.
   */
  public function edit(AttackSubtype $attackSubtype)
  {
    $types = AttackSubtype::getTypes();
    return view('admin.attack-subtypes.edit', compact('attackSubtype', 'types'));
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
        ->with('success', __('attack_subtypes.updated_successfully', ['name' => $attackSubtype->name]));
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al actualizar el Subtipo de Ataque: ' . $e->getMessage())
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
        ->with('success', __('attack_subtypes.deleted_successfully', ['name' => $attackSubtypeName]));
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al eliminar el Subtipo de Ataque: ' . $e->getMessage());
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
      return back()->with('error', 'Ha ocurrido un error al restaurar el Subtipo de Ataque: ' . $e->getMessage());
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
      return back()->with('error', 'Ha ocurrido un error al eliminar permanentemente el Subtipo de Ataque: ' . $e->getMessage());
    }
  }
}