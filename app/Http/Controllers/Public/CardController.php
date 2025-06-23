<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Services\Public\CardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CardController extends Controller
{
  /**
   * The card service instance
   */
  public function __construct(
    private CardService $cardService
  ) {}

  /**
   * Display a listing of all cards
   */
  public function index(Request $request): View
  {
    $data = $this->cardService->getPaginatedCards($request);
    
    // Create a Card instance for filter component
    $cardModel = new Card();
    
    return view('public.cards.index', [
      'cards' => $data['cards'],
      'cardModel' => $cardModel,
      'request' => $request,
      'totalCount' => $data['totalCount'],
      'filteredCount' => $data['filteredCount']
    ]);
  }

  /**
   * Display the specified card
   */
  public function show(Card $card): View
  {
    $card = $this->cardService->getPublishedCard($card);
    
    return view('public.cards.show', compact('card'));
  }
}