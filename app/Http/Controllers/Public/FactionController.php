<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Faction;
use App\Services\Public\FactionService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FactionController extends Controller
{
  /**
   * The faction service instance
   */
  public function __construct(
    private FactionService $factionService
  ) {}

  /**
   * Display a listing of all factions
   */
  public function index(Request $request): View
  {
    $data = $this->factionService->getPaginatedFactions($request);
    
    // Create a Faction instance for filter component
    $factionModel = new Faction();
    
    return view('public.factions.index', [
      'factions' => $data['factions'],
      'factionModel' => $factionModel,
      'request' => $request,
      'totalCount' => $data['totalCount'],
      'filteredCount' => $data['filteredCount']
    ]);
  }

  /**
   * Display the specified faction
   */
  public function show(Faction $faction): View
  {
    $faction = $this->factionService->getPublishedFaction($faction);
    
    return view('public.factions.show', compact('faction'));
  }
}