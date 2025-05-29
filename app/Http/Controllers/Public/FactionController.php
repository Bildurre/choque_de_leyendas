<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Faction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FactionController extends Controller
{
  /**
   * Display a listing of all factions.
   */
  public function index(Request $request): View
  {
    // Base query for published factions
    $query = Faction::published();
    
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
    $factions = $query->paginate(12)->withQueryString();
    
    // Create a Faction instance for filter component
    $factionModel = new Faction();
    
    return view('public.factions.index', [
      'factions' => $factions,
      'factionModel' => $factionModel,
      'request' => $request,
      'totalCount' => $totalCount,
      'filteredCount' => $filteredCount
    ]);
  }

  /**
   * Display the specified faction.
   */
  public function show(Faction $faction): View
  {
    if (!$faction->isPublished()) {
        abort(404);
    }
    
    $faction->load([
      'heroes' => function($query) {
        $query->published();
      },
      'cards' => function($query) {
        $query->published();
      },
      'factionDecks' => function($query) {
        $query->published();
      }
    ]);
    
    return view('public.factions.show', compact('faction'));
  }
}