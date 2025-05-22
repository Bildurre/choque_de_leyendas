<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Hero;
use Illuminate\View\View;

class HeroController extends Controller
{
  /**
   * Display a listing of all heroes.
   */
  public function index(): View
  {
    // Get all published heroes with their relationships, paginated
    $heroes = Hero::published()
      ->with([
        'faction',
        'heroClass',
        'heroRace',
        'heroClass.heroSuperclass',
        'heroAbilities',
        'heroAbilities.attackRange',
        'heroAbilities.attackSubtype',
      ])
      ->orderBy('name')
      ->paginate(12); // 12 heroes per page
    
    return view('public.heroes.index', compact('heroes'));
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