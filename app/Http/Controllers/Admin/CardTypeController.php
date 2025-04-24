<?php

namespace App\Http\Controllers\Admin;

use App\Models\CardType;
use App\Http\Controllers\Controller;
use App\Services\CardTypeService;
use App\Http\Requests\Admin\CardTypeRequest;

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
  public function index()
  {
    $cardTypes = $this->cardTypeService->getAllCardTypes();
    return view('admin.card-types.index', compact('cardTypes'));
  }

  /**
   * Show the form for creating a new card type.
   */
  public function create()
  {
    $availableSuperclasses = $this->cardTypeService->getAvailableSuperclasses();
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
        ->with('success', "El tipo de carta {$cardType->name} ha sido creado correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al crear el tipo de carta: ' . $e->getMessage())
        ->withInput();
    }
  }

  /**
   * Show the form for editing the specified card type.
   */
  public function edit(CardType $cardType)
  {
    $availableSuperclasses = $this->cardTypeService->getAvailableSuperclasses($cardType);
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
        ->with('success', "El tipo de carta {$cardType->name} ha sido actualizado correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al actualizar el tipo de carta: ' . $e->getMessage())
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
        ->with('success', "El tipo de carta {$cardTypeName} ha sido eliminado correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al eliminar el tipo de carta: ' . $e->getMessage());
    }
  }
}