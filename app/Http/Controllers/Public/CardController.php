<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\View\View;

class CardController extends Controller
{
  /**
   * Display a listing of all cards.
   */
  public function index(): View
  {
    $cards = Card::published()
      ->with(['faction', 'cardType', 'equipmentType'])
      ->orderBy('name')
      ->paginate(12); // Cambiar de get() a paginate(12)
    
    return view('public.cards.index', compact('cards'));
  }

  /**
   * Display the specified card.
   */
  public function show(Card $card): View
  {
    if (!$card->isPublished() || !$card->faction->isPublished()) {
      abort(404);
    }

    $card->load([
      'faction', 
      'cardType', 
      'equipmentType', 
      'attackRange', 
      'attackSubtype'
    ]);
    
    return view('public.cards.show', compact('card'));
  }
}