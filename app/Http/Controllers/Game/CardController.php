<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Http\Requests\Game\CardRequest;
use App\Models\AttackRange;
use App\Models\AttackSubtype;
use App\Models\Card;
use App\Models\CardType;
use App\Models\EquipmentType;
use App\Models\Faction;
use App\Models\HeroAbility;
use App\Services\Game\CardService;

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
  public function index()
  {
    $cards = $this->cardService->getPaginatedCards(15);
    return view('admin.cards.index', compact('cards'));
  }

  /**
   * Show the form for creating a new card.
   */
  public function create()
  {
    $factions = Faction::all();
    $cardTypes = CardType::all();
    $equipmentTypes = EquipmentType::all();
    $attackRanges = AttackRange::all();
    $attackSubtypes = AttackSubtype::all();
    $heroAbilities = HeroAbility::all();
    
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
        ->with('success', "La carta {$card->name} ha sido creada correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al crear la carta: ' . $e->getMessage())
        ->withInput();
    }
  }

  /**
   * Display the specified card.
   */
  public function show(Card $card)
  {
    $card->load(['faction', 'cardType', 'equipmentType', 'attackRange', 'attackSubtype', 'heroAbility']);
    return view('admin.cards.show', compact('card'));
  }

  /**
   * Show the form for editing the specified card.
   */
  public function edit(Card $card)
  {
    $factions = Faction::all();
    $cardTypes = CardType::all();
    $equipmentTypes = EquipmentType::all();
    $attackRanges = AttackRange::all();
    $attackSubtypes = AttackSubtype::all();
    $heroAbilities = HeroAbility::all();
    
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
        ->with('success', "La carta {$card->name} ha sido actualizada correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al actualizar la carta: ' . $e->getMessage())
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
        ->with('success', "La carta {$cardName} ha sido eliminada correctamente.");
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al eliminar la carta: ' . $e->getMessage());
    }
  }
}