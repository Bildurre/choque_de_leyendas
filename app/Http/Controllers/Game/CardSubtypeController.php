<?php

namespace App\Http\Controllers\Game;

use App\Models\CardSubtype;
use App\Services\Game\CardSubtypeService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\CardSubtypeRequest;
use Illuminate\Http\Request;

class CardSubtypeController extends Controller
{
  protected $cardSubtypeService;

  public function __construct(CardSubtypeService $cardSubtypeService)
  {
    $this->cardSubtypeService = $cardSubtypeService;
  }

  public function index(Request $request)
  {
    $tab = $request->get('tab', 'published');
    $trashed = ($tab === 'trashed');
    
    $activeCount = CardSubtype::count();
    $trashedCount = CardSubtype::onlyTrashed()->count();
    
    $cardSubtypes = $this->cardSubtypeService->getAllCardSubtypes(
      $request,
      12,
      false,
      $trashed
    );
    
    $cardSubtypeModel = new CardSubtype();
    
    $totalCount = $cardSubtypes->totalCount ?? 0;
    $filteredCount = $cardSubtypes->filteredCount ?? 0;
    
    return view('admin.card-subtypes.index', compact(
      'cardSubtypes', 
      'trashed', 
      'activeCount', 
      'trashedCount',
      'cardSubtypeModel',
      'request',
      'totalCount',
      'filteredCount'
    ));
  }

  public function create()
  {
    return view('admin.card-subtypes.create');
  }

  public function store(CardSubtypeRequest $request)
  {
    $validated = $request->validated();

    try {
      $cardSubtype = $this->cardSubtypeService->create($validated);
      return redirect()->route('admin.card-subtypes.index')
        ->with('success', __('entities.card_subtypes.created_successfully', ['name' => $cardSubtype->name]));
    } catch (\Exception $e) {
      return back()
        ->with('error', __('common.errors.create', ['entity' => __('entities.card_subtypes.singular')]))
        ->withInput();
    }
  }

  public function edit(CardSubtype $cardSubtype)
  {
    return view('admin.card-subtypes.edit', compact('cardSubtype'));
  }

  public function update(CardSubtypeRequest $request, CardSubtype $cardSubtype)
  {
    $validated = $request->validated();

    try {
      $this->cardSubtypeService->update($cardSubtype, $validated);
      return redirect()->route('admin.card-subtypes.index')
        ->with('success', __('entities.card_subtypes.updated_successfully', ['name' => $cardSubtype->name]));
    } catch (\Exception $e) {
      return back()
        ->with('error', __('common.errors.update', ['entity' => __('entities.card_subtypes.singular')]))
        ->withInput();
    }
  }

  public function destroy(CardSubtype $cardSubtype)
  {
    try {
      $cardSubtypeName = $cardSubtype->name;
      $this->cardSubtypeService->delete($cardSubtype);
      
      return redirect()->route('admin.card-subtypes.index')
        ->with('success', __('entities.card_subtypes.deleted_successfully', ['name' => $cardSubtypeName]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.delete', ['entity' => __('entities.card_subtypes.singular')]));
    }
  }

  public function restore($id)
  {
    try {
      $this->cardSubtypeService->restore($id);
      $cardSubtype = CardSubtype::find($id);
      
      return redirect()->route('admin.card-subtypes.index', ['trashed' => 1])
        ->with('success', __('entities.card_subtypes.restored_successfully', ['name' => $cardSubtype->name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.restore', ['entity' => __('entities.card_subtypes.singular')]));
    }
  }

  public function forceDelete($id)
  {
    try {
      $cardSubtype = CardSubtype::onlyTrashed()->findOrFail($id);
      $name = $cardSubtype->name;
      
      $this->cardSubtypeService->forceDelete($id);
      
      return redirect()->route('admin.card-subtypes.index', ['trashed' => 1])
        ->with('success', __('entities.card_subtypes.force_deleted_successfully', ['name' => $name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.force_delete', ['entity' => __('entities.card_subtypes.singular')]));
    }
  }
}