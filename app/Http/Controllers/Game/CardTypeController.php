<?php

namespace App\Http\Controllers\Game;

use App\Models\CardType;
use App\Services\Game\CardTypeService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\CardTypeRequest;
use Illuminate\Http\Request;

class CardTypeController extends Controller
{
  protected $cardTypeService;

  /**
   * Create a new controller instance.
   *
   * @param CardTypeService $cardTypeService
   */
  public function __construct(CardTypeService $cardTypeService)
  {
    $this->cardTypeService = $cardTypeService;
  }

  /**
   * Display a listing of card types.
   */
  public function index(Request $request)
  {
    $trashed = $request->has('trashed');
    
    // Obtener contadores para las pestañas
    $activeCount = CardType::count();
    $trashedCount = CardType::onlyTrashed()->count();
    
    // Obtener card types con filtrado y paginación
    $cardTypes = $this->cardTypeService->getAllCardTypes(
      $request, // request para filtros
      12,       // perPage
      false,    // withTrashed
      $trashed  // onlyTrashed
    );
    
    // Crear instancia de modelo para componente de filtros
    $cardTypeModel = new CardType();
    
    // Obtener conteos de la respuesta paginada
    $totalCount = $cardTypes->totalCount ?? 0;
    $filteredCount = $cardTypes->filteredCount ?? 0;
    
    return view('admin.card-types.index', compact(
      'cardTypes', 
      'trashed', 
      'activeCount', 
      'trashedCount',
      'cardTypeModel',
      'request',
      'totalCount',
      'filteredCount'
    ));
  }

  /**
   * Show the form for creating a new card type.
   */
  public function create()
  {
    $availableSuperclasses = $this->cardTypeService->getAvailableHeroSuperclasses();
    return view('admin.card-types.create', compact('availableSuperclasses'));
  }

  /**
   * Store a newly created card type in storage.
   */
  public function store(CardTypeRequest $request)
  {
    $validated = $request->validated();

    try {
      $cardType = $this->cardTypeService->create($validated);
      return redirect()->route('admin.card-types.index')
        ->with('success', __('card_types.created_successfully', ['name' => $cardType->name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.create', ['entity' => __('entities.card_types.singular')]))
        ->withInput();
    }
  }

  /**
   * Show the form for editing the specified card type.
   */
  public function edit(CardType $cardType)
  {
    $availableSuperclasses = $this->cardTypeService->getAvailableHeroSuperclasses($cardType->id);
    
    // Si este tipo de carta ya tiene una superclase asignada, incluirla en las opciones
    if ($cardType->hero_superclass_id) {
      $currentSuperclass = \App\Models\HeroSuperclass::find($cardType->hero_superclass_id);
      if ($currentSuperclass) {
        $availableSuperclasses->push($currentSuperclass);
      }
    }
    
    return view('admin.card-types.edit', compact('cardType', 'availableSuperclasses'));
  }

  /**
   * Update the specified card type in storage.
   */
  public function update(CardTypeRequest $request, CardType $cardType)
  {
    $validated = $request->validated();

    try {
      $this->cardTypeService->update($cardType, $validated);
      return redirect()->route('admin.card-types.index')
        ->with('success', __('card_types.updated_successfully', ['name' => $cardType->name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.update', ['entity' => __('entities.card_types.singular')]))
        ->withInput();
    }
  }

  /**
   * Remove the specified card type from storage.
   */
  public function destroy(CardType $cardType)
  {
    try {
      $cardTypeName = $cardType->name;
      $this->cardTypeService->delete($cardType);
      
      return redirect()->route('admin.card-types.index')
        ->with('success', __('card_types.deleted_successfully', ['name' => $cardTypeName]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.delete', ['entity' => __('entities.card_types.singular')]));
    }
  }

  /**
   * Restore the specified card type from trash.
   */
  public function restore($id)
  {
    try {
      $this->cardTypeService->restore($id);
      $cardType = CardType::find($id);
      
      return redirect()->route('admin.card-types.index', ['trashed' => 1])
        ->with('success', __('card_types.restored_successfully', ['name' => $cardType->name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.restore', ['entity' => __('entities.card_types.singular')]));
    }
  }

  /**
   * Force delete the specified card type from storage.
   */
  public function forceDelete($id)
  {
    try {
      $cardType = CardType::onlyTrashed()->findOrFail($id);
      $name = $cardType->name;
      
      $this->cardTypeService->forceDelete($id);
      
      return redirect()->route('admin.card-types.index', ['trashed' => 1])
        ->with('success', __('card_types.force_deleted_successfully', ['name' => $name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.force_delete', ['entity' => __('entities.card_types.singular')]));
    }
  }
}