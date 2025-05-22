<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Hero;
use App\Models\Card;
use App\Models\Faction;
use App\Models\HeroSuperclass;
use Illuminate\View\View;

class HeroController extends Controller
{
  /**
   * Display a listing of all heroes.
   */
  public function index(): View
  {
    // Obtener todos los héroes publicados con sus relaciones
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
      ->get();
    
    // Obtener todas las facciones para los filtros
    $factions = Faction::published()->orderBy('name')->get();
    
    // Obtener todas las superclases para los filtros
    $superclasses = HeroSuperclass::orderBy('name')->get();
    
    // Obtener cartas destacadas (5 cartas aleatorias publicadas)
    $featuredCards = Card::published()
      ->with([
        'faction',
        'cardType',
        'cardType.heroSuperclass',
        'equipmentType',
        'attackRange',
        'attackSubtype',
        'heroAbility',
        'heroAbility.attackRange',
        'heroAbility.attackSubtype'
      ])
      ->inRandomOrder()
      ->take(5)
      ->get();
    
    // Obtener facciones destacadas (3 facciones aleatorias publicadas)
    $featuredFactions = Faction::published()
      ->inRandomOrder()
      ->take(3)
      ->get();
    
    return view('public.heroes.index', compact(
      'heroes', 
      'factions', 
      'superclasses', 
      'featuredCards', 
      'featuredFactions'
    ));
  }

  /**
   * Display the specified hero.
   */
  public function show(Hero $hero): View
  {
    // Verificar que la facción está publicada
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