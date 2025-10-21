<?php

namespace App\Http\Controllers\Game;

use App\Models\HeroSuperclass;
use App\Services\Game\HeroSuperclassService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\HeroSuperclassRequest;
use Illuminate\Http\Request;

class HeroSuperclassController extends Controller
{
  protected $heroSuperclassService;

  /**
   * Create a new controller instance.
   *
   * @param HeroSuperclassService $heroSuperclassService
   */
  public function __construct(HeroSuperclassService $heroSuperclassService)
  {
    $this->heroSuperclassService = $heroSuperclassService;
  }

  public function index(Request $request)
  {
    $tab = $request->get('tab', 'published');
    $trashed = ($tab === 'trashed');
    
    // Get counters for tabs directly using Eloquent
    $activeCount = HeroSuperclass::count();
    $trashedCount = HeroSuperclass::onlyTrashed()->count();
    
    // Get hero superclasses with filtering and pagination
    $heroSuperclasses = $this->heroSuperclassService->getAllHeroSuperclasses(
      $request, // request para filtros
      12,       // perPage
      false,    // withTrashed
      $trashed  // onlyTrashed
    );
    
    // Create a HeroSuperclass instance for filter component
    $heroSuperclassModel = new HeroSuperclass();
    
    // Get counts from the paginated result
    $totalCount = $heroSuperclasses->totalCount ?? 0;
    $filteredCount = $heroSuperclasses->filteredCount ?? 0;
    
    return view('admin.hero-superclasses.index', compact(
      'heroSuperclasses', 
      'trashed', 
      'activeCount', 
      'trashedCount',
      'heroSuperclassModel',
      'request',
      'totalCount',
      'filteredCount'
    ));
  }

  /**
   * Show the form for creating a new hero superclass.
   */
  public function create()
  {
    return view('admin.hero-superclasses.create');
  }

  /**
   * Store a newly created hero superclass in storage.
   */
  public function store(HeroSuperclassRequest $request)
  {
    $validated = $request->validated();

    try {
      $heroSuperclass = $this->heroSuperclassService->create($validated);
      return redirect()->route('admin.hero-superclasses.index')
        ->with('success', __('hero_superclasses.created_successfully', ['name' => $heroSuperclass->name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.create', ['entity' => __('entities.hero_superclasses.singular')]))
        ->withInput();
    }
  }

  /**
   * Show the form for editing the specified hero superclass.
   */
  public function edit(HeroSuperclass $heroSuperclass)
  {
    return view('admin.hero-superclasses.edit', compact('heroSuperclass'));
  }

  /**
   * Update the specified hero superclass in storage.
   */
  public function update(HeroSuperclassRequest $request, HeroSuperclass $heroSuperclass)
  {
    $validated = $request->validated();

    try {
      $this->heroSuperclassService->update($heroSuperclass, $validated);
      return redirect()->route('admin.hero-superclasses.index')
        ->with('success', __('hero_superclasses.updated_successfully', ['name' => $heroSuperclass->name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.update', ['entity' => __('entities.hero_superclasses.singular')]))
        ->withInput();
    }
  }

  /**
   * Remove the specified hero superclass from storage.
   */
  public function destroy(HeroSuperclass $heroSuperclass)
  {
    try {
      $heroSuperclassName = $heroSuperclass->name;
      $this->heroSuperclassService->delete($heroSuperclass);
      
      return redirect()->route('admin.hero-superclasses.index')
        ->with('success', __('hero_superclasses.deleted_successfully', ['name' => $heroSuperclassName]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.delete', ['entity' => __('entities.hero_superclasses.singular')]));
    }
  }

  /**
   * Restore the specified hero superclass from trash.
   */
  public function restore($id)
  {
    try {
      $this->heroSuperclassService->restore($id);
      $heroSuperclass = HeroSuperclass::find($id);
      
      return redirect()->route('admin.hero-superclasses.index', ['trashed' => 1])
        ->with('success', __('hero_superclasses.restored_successfully', ['name' => $heroSuperclass->name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.restore', ['entity' => __('entities.hero_superclasses.singular')]));
    }
  }

  /**
   * Force delete the specified hero superclass from storage.
   */
  public function forceDelete($id)
  {
    try {
      $heroSuperclass = HeroSuperclass::onlyTrashed()->findOrFail($id);
      $name = $heroSuperclass->name;
      
      $this->heroSuperclassService->forceDelete($id);
      
      return redirect()->route('admin.hero-superclasses.index', ['trashed' => 1])
        ->with('success', __('hero_superclasses.force_deleted_successfully', ['name' => $name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.force_delete', ['entity' => __('entities.hero_superclasses.singular')]));
    }
  }
}