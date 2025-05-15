<?php

namespace App\Http\Controllers\Game;

use App\Models\Counter;
use App\Services\Game\CounterService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\CounterRequest;
use Illuminate\Http\Request;

class CounterController extends Controller
{
  protected $counterService;

  /**
   * Create a new controller instance.
   *
   * @param CounterService $counterService
   */
  public function __construct(CounterService $counterService)
  {
    $this->counterService = $counterService;
  }

  /**
   * Display a listing of counters.
   */
  public function index(Request $request)
  {
    $trashed = $request->has('trashed');
    
    // Get counters for tabs directly using Eloquent
    $activeCount = Counter::count();
    $trashedCount = Counter::onlyTrashed()->count();
    
    // Get counters with filtering and pagination
    $counters = $this->counterService->getAllCounters(
      $request, // request para filtros
      12,       // perPage
      false,    // withTrashed
      $trashed  // onlyTrashed
    );
    
    // Create a Counter instance for filter component
    $counterModel = new Counter();
    
    // Get counts from the paginated result
    $totalCount = $counters->totalCount ?? 0;
    $filteredCount = $counters->filteredCount ?? 0;
    
    return view('admin.counters.index', compact(
      'counters', 
      'trashed', 
      'activeCount', 
      'trashedCount',
      'counterModel',
      'request',
      'totalCount',
      'filteredCount'
    ));
  }

  /**
   * Show the form for creating a new counter.
   */
  public function create(Request $request)
  {
    $type = $request->query('type', 'boon');
    
    if (!in_array($type, ['boon', 'bane'])) {
      $type = 'boon';
    }
    
    $types = Counter::getTypes();
    
    return view('admin.counters.create', compact('type', 'types'));
  }

  /**
   * Store a newly created counter in storage.
   */
  public function store(CounterRequest $request)
  {
    $validated = $request->validated();

    try {
      $counter = $this->counterService->create($validated);
      $tab = $counter->type === 'boon' ? 'boons' : 'banes';
      
      return redirect()->route('admin.counters.index', ['tab' => $tab])
        ->with('success', __('counters.created_successfully', [
          'name' => $counter->name,
          'type' => $counter->type_name
        ]));
    } catch (\Exception $e) {
      return back()
        ->with('error', __('counters.creation_error'))
        ->withInput();
    }
  }

  /**
   * Show the form for editing the specified counter.
   */
  public function edit(Counter $counter)
  {
    $types = Counter::getTypes();
    
    return view('admin.counters.edit', compact('counter', 'types'));
  }

  /**
   * Update the specified counter in storage.
   */
  public function update(CounterRequest $request, Counter $counter)
  {
    $validated = $request->validated();

    try {
      $oldType = $counter->type;
      $this->counterService->update($counter, $validated);
      
      $tab = $counter->type === 'boon' ? 'boons' : 'banes';
      
      return redirect()->route('admin.counters.index', ['tab' => $tab])
        ->with('success', __('counters.updated_successfully', [
          'name' => $counter->name,
          'type' => $counter->type_name
        ]));
    } catch (\Exception $e) {
      return back()
        ->with('error', __('counters.update_error'))
        ->withInput();
    }
  }

  /**
   * Remove the specified counter from storage.
   */
  public function destroy(Counter $counter)
  {
    try {
      $counterName = $counter->name;
      $counterType = $counter->type_name;
      $tab = $counter->type === 'boon' ? 'boons' : 'banes';
      
      $this->counterService->delete($counter);
      
      return redirect()->route('admin.counters.index', ['tab' => $tab])
        ->with('success', __('counters.deleted_successfully', [
          'name' => $counterName,
          'type' => $counterType
        ]));
    } catch (\Exception $e) {
      return back()
        ->with('error', __('counters.delete_error'));
    }
  }

  /**
   * Restore the specified counter from trash.
   */
  public function restore($id)
  {
    try {
      $this->counterService->restore($id);
      $counter = Counter::find($id);
      $tab = $counter->type === 'boon' ? 'boons' : 'banes';
      
      return redirect()->route('admin.counters.index', ['tab' => $tab])
        ->with('success', __('counters.restored_successfully', [
          'name' => $counter->name,
          'type' => $counter->type_name
        ]));
    } catch (\Exception $e) {
      return back()
        ->with('error', __('counters.restore_error'));
    }
  }

  /**
   * Force delete the specified counter from storage.
   */
  public function forceDelete($id)
  {
    try {
      $counter = Counter::onlyTrashed()->findOrFail($id);
      $name = $counter->name;
      $typeName = $counter->type_name;
      
      $this->counterService->forceDelete($id);
      
      return redirect()->route('admin.counters.index', ['tab' => 'trashed'])
        ->with('success', __('counters.force_deleted_successfully', [
          'name' => $name,
          'type' => $typeName
        ]));
    } catch (\Exception $e) {
      return back()
        ->with('error', __('counters.force_delete_error'));
    }
  }
}