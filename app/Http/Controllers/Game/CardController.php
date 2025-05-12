<?php

namespace App\Http\Controllers\Game;

use App\Models\Card;
use App\Models\Faction;
use App\Models\CardType;
use App\Models\AttackRange;
use App\Models\AttackSubtype;
use App\Models\EquipmentType;
use App\Models\HeroAbility;
use App\Services\Game\CardService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\CardRequest;
use Illuminate\Http\Request;

class CardController extends Controller
{
  protected $cardService;

  /**
   * Create a new controller instance.
   *
   * @param CardService $cardService
   */
  public function __construct(CardService $cardService)
  {
    $this->cardService = $cardService;
  }

  /**
   * Display a listing of cards.
   */
  public function index(Request $request)
  {
    $trashed = $request->has('trashed');
    
    // Get counters for tabs directly using Eloquent
    $activeCount = Card::count();
    $trashedCount = Card::onlyTrashed()->count();
    
    // Get cards with pagination
    $cards = $this->cardService->getAllCards(12, false, $trashed);
    
    return view('admin.cards.index', compact(
      'cards', 
      'trashed', 
      'activeCount', 
      'trashedCount'
    ));
  }

  /**
   * Show the form for creating a new card.
   */
  public function create()
  {
    $factions = Faction::orderBy('id')->get();
    $cardTypes = CardType::orderBy('id')->get();
    $equipmentTypes = EquipmentType::orderBy('category')->orderBy('id')->get();
    $attackRanges = AttackRange::orderBy('id')->get();
    $attackSubtypes = AttackSubtype::orderBy('type')->orderBy('id')->get();
    $heroAbilities = HeroAbility::orderBy('id')->get();
    
    return view('admin.cards.create', compact(
      'factions',
      'cardTypes',
      'equipmentTypes',
      'attackRanges',
      'attackSubtypes',
      'heroAbilities'
    ));
  }

  /**
   * Store a newly created card in storage.
   */
  public function store(CardRequest $request)
  {
    $validated = $request->validated();

    try {
      $card = $this->cardService->create($validated);
      return redirect()->route('admin.cards.index')
        ->with('success', __('cards.created_successfully', ['name' => $card->name]));
    } catch (\Exception $e) {
      return back()
        ->with('error', 'Ha ocurrido un error al crear la Carta: ' . $e->getMessage())
        ->withInput();
    }
  }

  /**
   * Show the form for editing the specified card.
   */
  public function edit(Card $card)
  {
    $factions = Faction::orderBy('id')->get();
    $cardTypes = CardType::orderBy('id')->get();
    $equipmentTypes = EquipmentType::orderBy('category')->orderBy('id')->get();
    $attackRanges = AttackRange::orderBy('id')->get();
    $attackSubtypes = AttackSubtype::orderBy('type')->orderBy('id')->get();
    $heroAbilities = HeroAbility::orderBy('id')->get();
    
    return view('admin.cards.edit', compact(
      'card',
      'factions',
      'cardTypes',
      'equipmentTypes',
      'attackRanges',
      'attackSubtypes',
      'heroAbilities'
    ));
  }

  /**
   * Update the specified card in storage.
   */
  public function update(CardRequest $request, Card $card)
  {
    $validated = $request->validated();

    try {
      $this->cardService->update($card, $validated);
      return redirect()->route('admin.cards.index')
        ->with('success', __('cards.updated_successfully', ['name' => $card->name]));
    } catch (\Exception $e) {
      return back()
        ->with('error', 'Ha ocurrido un error al actualizar la Carta: ' . $e->getMessage())
        ->withInput();
    }
  }

  /**
   * Remove the specified card from storage.
   */
  public function destroy(Card $card)
  {
    try {
      $cardName = $card->name;
      $this->cardService->delete($card);
      
      return redirect()->route('admin.cards.index')
        ->with('success', __('cards.deleted_successfully', ['name' => $cardName]));
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al eliminar la Carta: ' . $e->getMessage());
    }
  }

  /**
   * Restore the specified card from trash.
   */
  public function restore($id)
  {
    try {
      $this->cardService->restore($id);
      $card = Card::find($id);
      
      return redirect()->route('admin.cards.index', ['trashed' => 1])
        ->with('success', __('cards.restored_successfully', ['name' => $card->name]));
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al restaurar la Carta: ' . $e->getMessage());
    }
  }

  /**
   * Force delete the specified card from storage.
   */
  public function forceDelete($id)
  {
    try {
      $card = Card::onlyTrashed()->findOrFail($id);
      $name = $card->name;
      
      $this->cardService->forceDelete($id);
      
      return redirect()->route('admin.cards.index', ['trashed' => 1])
        ->with('success', __('cards.force_deleted_successfully', ['name' => $name]));
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al eliminar permanentemente la Carta: ' . $e->getMessage());
    }
  }
}