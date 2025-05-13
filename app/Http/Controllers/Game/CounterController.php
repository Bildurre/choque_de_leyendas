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
    $activeTab = $request->query('tab', 'boons');
    
    // Determine what to display based on active tab
    $trashed = $activeTab === 'trashed';
    $type = null;
    
    if ($activeTab === 'boons') {
      $type = 'boon';
    } elseif ($activeTab === 'banes') {
      $type = 'bane';
    }
    
    // Get counters based on tab
    $counters = $this->counterService->getCountersByTab($activeTab, 12);
    
    // Get counts for tabs
    $counts = $this->counterService->getCountsByCategoryAndTrash();
    $boonsCount = $counts['boons'];
    $banesCount = $counts['banes'];
    $trashedCount = $counts['trashed'];
    
    return view('admin.counters.index', compact(
      'counters', 
      'activeTab', 
      'boonsCount', 
      'banesCount', 
      'trashedCount'
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
        ->with('error', __('counters.creation_error', ['error' => $e->getMessage()]))
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
        ->with('error', __('counters.update_error', ['error' => $e->getMessage()]))
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
        ->with('error', __('counters.delete_error', ['error' => $e->getMessage()]));
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
        ->with('error', __('counters.restore_error', ['error' => $e->getMessage()]));
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
        ->with('error', __('counters.force_delete_error', ['error' => $e->getMessage()]));
    }
  }
}