<?php

namespace App\Http\Controllers\Game;

use App\Models\AttackRange;
use App\Services\Game\AttackRangeService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\AttackRangeRequest;
use Illuminate\Http\Request;

class AttackRangeController extends Controller
{
  protected $attackRangeService;

  /**
   * Create a new controller instance.
   *
   * @param AttackRangeService $attackRangeService
   */
  public function __construct(AttackRangeService $attackRangeService)
  {
    $this->attackRangeService = $attackRangeService;
  }

  /**
   * Display a listing of attack ranges.
   */
  public function index(Request $request)
  {
    $tab = $request->get('tab', 'published');
    $trashed = ($tab === 'trashed');
    
    // Obtener contadores para las pestañas
    $activeCount = AttackRange::count();
    $trashedCount = AttackRange::onlyTrashed()->count();
    
    // Obtener attack ranges con todos los filtros aplicados
    $attackRanges = $this->attackRangeService->getAllAttackRanges(
      $request, // request para filtros
      12,       // perPage
      false,    // withTrashed
      $trashed  // onlyTrashed
    );
    
    // Crear instancia de modelo para componente de filtros
    $attackRangeModel = new AttackRange();
    
    // Obtener conteos de la respuesta paginada
    $totalCount = $attackRanges->totalCount ?? 0;
    $filteredCount = $attackRanges->filteredCount ?? 0;
    
    return view('admin.attack-ranges.index', compact(
      'attackRanges', 
      'trashed', 
      'activeCount', 
      'trashedCount',
      'attackRangeModel',
      'request',
      'totalCount',
      'filteredCount'
    ));
  }

  /**
   * Show the form for creating a new attack range.
   */
  public function create()
  {
    return view('admin.attack-ranges.create');
  }

  /**
   * Store a newly created attack range in storage.
   */
  public function store(AttackRangeRequest $request)
  {
    $validated = $request->validated();

    try {
      $attackRange = $this->attackRangeService->create($validated);
      return redirect()->route('admin.attack-ranges.index')
        ->with('success', __('attack_ranges.created_successfully', ['name' => $attackRange->name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.create', ['entity' => __('entities.attack_ranges.singular')]))
        ->withInput();
    }
  }

  /**
   * Show the form for editing the specified attack range.
   */
  public function edit(AttackRange $attackRange)
  {
    return view('admin.attack-ranges.edit', compact('attackRange'));
  }

  /**
   * Update the specified attack range in storage.
   */
  public function update(AttackRangeRequest $request, AttackRange $attackRange)
  {
    $validated = $request->validated();

    try {
      $this->attackRangeService->update($attackRange, $validated);
      return redirect()->route('admin.attack-ranges.index')
        ->with('success', __('attack_ranges.updated_successfully', ['name' => $attackRange->name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.update', ['entity' => __('entities.attack_ranges.singular')]))
        ->withInput();
    }
  }

  /**
   * Remove the specified attack range from storage.
   */
  public function destroy(AttackRange $attackRange)
  {
    try {
      $attackRangeName = $attackRange->name;
      $this->attackRangeService->delete($attackRange);
      
      return redirect()->route('admin.attack-ranges.index')
        ->with('success', __('attack_ranges.deleted_successfully', ['name' => $attackRangeName]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.delete', ['entity' => __('entities.attack_ranges.singular')]));
    }
  }

  /**
   * Restore the specified attack range from trash.
   */
  public function restore($id)
  {
    try {
      $this->attackRangeService->restore($id);
      $attackRange = AttackRange::find($id);
      
      return redirect()->route('admin.attack-ranges.index', ['trashed' => 1])
        ->with('success', __('attack_ranges.restored_successfully', ['name' => $attackRange->name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.restore', ['entity' => __('entities.attack_ranges.singular')]));
    }
  }

  /**
   * Force delete the specified attack range from storage.
   */
  public function forceDelete($id)
  {
    try {
      $attackRange = AttackRange::onlyTrashed()->findOrFail($id);
      $name = $attackRange->name;
      
      $this->attackRangeService->forceDelete($id);
      
      return redirect()->route('admin.attack-ranges.index', ['trashed' => 1])
        ->with('success', __('attack_ranges.force_deleted_successfully', ['name' => $name]));
    } catch (\Exception $e) {
      return back()->with('error',__('common.errors.force_delete', ['entity' => __('entities.attack_ranges.singular')]));
    }
  }
}