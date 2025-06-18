<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CardController extends Controller
{
  /**
   * Display a listing of all cards.
   */
  public function index(Request $request): View
  {
    // Base query for published cards
    $query = Card::published()
      ->with(['faction', 'cardType.heroSuperclass', 'equipmentType', 'attackRange', 'attackSubtype', 'heroAbility.attackRange', 'heroAbility.attackSubtype']);
    
    // Count total before filters
    $totalCount = $query->count();
    
    // Apply public filters
    $query->applyPublicFilters($request);
    
    // Count after filters
    $filteredQuery = clone $query;
    $filteredCount = $filteredQuery->count();
    
    // Apply default ordering if no sort is specified
    if (!$request->has('sort')) {
      $query->orderBy('name');
    }
    
    // Paginate results
    $cards = $query->paginate(12)->withQueryString();
    
    // Create a Card instance for filter component
    $cardModel = new Card();
    
    // Debug: Verificar que las variables existen
    // dd(compact('cards', 'cardModel', 'request', 'totalCount', 'filteredCount'));
    
    return view('public.cards.index', [
      'cards' => $cards,
      'cardModel' => $cardModel,
      'request' => $request,
      'totalCount' => $totalCount,
      'filteredCount' => $filteredCount
    ]);
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
      'cardType.heroSuperclass', 
      'equipmentType', 
      'attackRange', 
      'attackSubtype',
      'heroAbility.attackRange',
      'heroAbility.attackSubtype'
    ]);
    
    return view('public.cards.show', compact('card'));
  }
}