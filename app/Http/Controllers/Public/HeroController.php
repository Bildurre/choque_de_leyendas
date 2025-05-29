<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Hero;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HeroController extends Controller
{
  /**
   * Display a listing of all heroes.
   */
  public function index(Request $request): View
  {
    // Base query for published heroes
    $query = Hero::published()
      ->with([
        'faction',
        'heroClass',
        'heroRace',
        'heroClass.heroSuperclass',
        'heroAbilities',
        'heroAbilities.attackRange',
        'heroAbilities.attackSubtype',
      ]);
    
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
    $heroes = $query->paginate(12)->withQueryString();
    
    // Create a Hero instance for filter component
    $heroModel = new Hero();
    
    return view('public.heroes.index', [
      'heroes' => $heroes,
      'heroModel' => $heroModel,
      'request' => $request,
      'totalCount' => $totalCount,
      'filteredCount' => $filteredCount
    ]);
  }

  /**
   * Display the specified hero.
   */
  public function show(Hero $hero): View
  {
    // Verify that the hero and faction are published
    if (!$hero->isPublished() || !$hero->faction->isPublished()) {
      abort(404);
    }
    
    $hero->load([
      'faction',
      'heroClass',
      'heroRace',
      'heroClass.heroSuperclass',
      'heroAbilities',
      'heroAbilities.attackRange',
      'heroAbilities.attackSubtype',
    ]);
    
    return view('public.heroes.show', compact('hero'));
  }
}