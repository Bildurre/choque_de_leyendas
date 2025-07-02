<?php

namespace App\Services\Public;

use App\Models\Hero;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class HeroService
{
  /**
   * Items per page for pagination
   */
  private const ITEMS_PER_PAGE = 12;

  /**
   * Get the relations that should be loaded for hero display
   */
  public function getHeroRelations(): array
  {
    return [
      'faction',
      'heroClass',
      'heroRace',
      'heroClass.heroSuperclass',
      'heroAbilities',
      'heroAbilities.attackRange',
      'heroAbilities.attackSubtype'
    ];
  }

  /**
   * Get query for published heroes with all relations
   */
  public function getPublishedHeroesQuery(): Builder
  {
    return Hero::published()->with($this->getHeroRelations());
  }

  /**
   * Get paginated heroes with filters
   */
  public function getPaginatedHeroes(Request $request): array
  {
    // Base query for published heroes
    $query = $this->getPublishedHeroesQuery();
    
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
    $heroes = $query->paginate(self::ITEMS_PER_PAGE)->withQueryString();
    
    return [
      'heroes' => $heroes,
      'totalCount' => $totalCount,
      'filteredCount' => $filteredCount
    ];
  }

  /**
   * Get a single published hero with relations
   */
  public function getPublishedHero(Hero $hero): Hero
  {
    if (!$hero->isPublished() || !$hero->faction->isPublished()) {
      abort(404);
    }

    $hero->load($this->getHeroRelations());
    
    return $hero;
  }

  /**
   * Get heroes for collection
   */
  public function getHeroesForCollection(array $heroIds): Builder
  {
    return Hero::published()
      ->whereIn('id', $heroIds)
      ->with($this->getHeroRelations());
  }

  /**
   * Get hero statistics
   */
  public function getHeroStatistics(Hero $hero): array
  {
    return [
      'totalAbilities' => $hero->heroAbilities->count(),
      'passiveAbilities' => $hero->heroAbilities->where('is_passive', true)->count(),
      'activeAbilities' => $hero->heroAbilities->where('is_passive', false)->count(),
      'ultimateAbility' => $hero->heroAbilities->where('is_ultimate', true)->first(),
      'statsTotal' => $hero->strength + $hero->agility + $hero->intelligence + 
                     $hero->resistance + $hero->vitality
    ];
  }
}