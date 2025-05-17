<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Faction;
use Illuminate\View\View;

class FactionController extends Controller
{
  /**
   * Display a listing of all factions.
   */
  public function index(): View
  {
    $factions = Faction::published()->orderBy('name')->get();
    
    return view('public.factions.index', compact('factions'));
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