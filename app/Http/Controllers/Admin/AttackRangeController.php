<?php

namespace App\Http\Controllers\Admin;

use App\Models\AttackRange;
use App\Http\Controllers\Controller;
use App\Services\AttackRangeService;
use App\Http\Requests\Admin\AttackRangeRequest;
use Illuminate\Support\Facades\App;

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
    $availableLocales = available_locales();
    return view('admin.attack-ranges.create', compact('availableLocales'));
  }

  /**
   * Store a newly created attack range in storage.
   */
  public function store(AttackRangeRequest $request)
  {
    $validated = $request->validated();

    // Procesamos las traducciones
    $translations = [];
    foreach (available_locales() as $locale) {
      if ($request->has($locale)) {
        $translations[$locale] = $request->input($locale);
      }
    }
    
    if (!empty($translations)) {
      $validated['translations'] = $translations;
    }

    try {
      $attackRange = $this->attackRangeService->create($validated);
      return redirect()->route('admin.attack-ranges.index')
        ->with('success', __('app.created_successfully', ['entity' => __('app.attack_range')]));
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al crear el rango de habilidad: ' . $e->getMessage());
    }
  }

  /**
   * Show the form for editing the specified attack range.
   */
  public function edit(AttackRange $attackRange)
  {
    $availableLocales = available_locales();
    return view('admin.attack-ranges.edit', compact('attackRange', 'availableLocales'));
  }

  /**
   * Update the specified attack range in storage.
   */
  public function update(AttackRangeRequest $request, AttackRange $attackRange)
  {
    $validated = $request->validated();

    // Procesamos las traducciones
    $translations = [];
    foreach (available_locales() as $locale) {
      if ($request->has($locale)) {
        $translations[$locale] = $request->input($locale);
      }
    }
    
    if (!empty($translations)) {
      $validated['translations'] = $translations;
    }

    try {
      $this->attackRangeService->update($attackRange, $validated);
      return redirect()->route('admin.attack-ranges.index')
        ->with('success', __('app.updated_successfully', ['entity' => __('app.attack_range')]));
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
        ->with('success', __('app.deleted_successfully', ['entity' => __('app.attack_range')]));
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }
  }
}