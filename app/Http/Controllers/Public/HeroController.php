<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Hero;
use App\Services\Public\HeroService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HeroController extends Controller
{
  /**
   * The hero service instance
   */
  public function __construct(
    private HeroService $heroService
  ) {}

  /**
   * Display a listing of all heroes
   */
  public function index(Request $request): View
  {
    $data = $this->heroService->getPaginatedHeroes($request);
    
    // Create a Hero instance for filter component if needed
    $heroModel = new Hero();
    
    return view('public.heroes.index', [
      'heroes' => $data['heroes'],
      'heroModel' => $heroModel,
      'request' => $request,
      'totalCount' => $data['totalCount'],
      'filteredCount' => $data['filteredCount']
    ]);
  }

  /**
   * Display the specified hero
   */
  public function show(Hero $hero): View
  {
    $hero = $this->heroService->getPublishedHero($hero);
    $statistics = $this->heroService->getHeroStatistics($hero);
    
    return view('public.heroes.show', compact('hero', 'statistics'));
  }
}