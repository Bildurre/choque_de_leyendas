<?php

namespace App\Http\Controllers\Admin;

use App\Models\AttackRange;
use App\Http\Controllers\Controller;
use App\Services\AttackRangeService;
use App\Http\Requests\Admin\AttackRange\StoreAttackRangeRequest;
use App\Http\Requests\Admin\AttackRange\UpdateAttackRangeRequest;

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
  public function index()
  {
    $attackRanges = $this->attackRangeService->getAllRanges();
    return view('admin.attack-ranges.index', compact('attackRanges'));
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
  public function store(StoreAttackRangeRequest $request)
  {
    $validated = $request->validated();

    try {
      $attackRange = $this->attackRangeService->create($validated);
      return redirect()->route('admin.attack-ranges.index')
        ->with('success', "El rango de habilidad {$attackRange->name} ha sido creado correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al crear el rango de habilidad: ' . $e->getMessage());
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
  public function update(UpdateAttackRangeRequest $request, AttackRange $attackRange)
  {
    $validated = $request->validated();

    try {
      $this->attackRangeService->update($attackRange, $validated);
      return redirect()->route('admin.attack-ranges.index')
        ->with('success', "El rango de habilidad {$attackRange->name} ha sido actualizado correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al actualizar el rango de habilidad: ' . $e->getMessage());
    }
  }

  /**
   * Remove the specified attack range from storage.
   */
  public function destroy(AttackRange $attackRange)
  {
    try {
      $rangeName = $attackRange->name;
      $this->attackRangeService->delete($attackRange);
      return redirect()->route('admin.attack-ranges.index')
        ->with('success', "El rango de habilidad {$rangeName} ha sido eliminado correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }
  }
}