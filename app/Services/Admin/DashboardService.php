<?php

namespace App\Services\Admin;

use App\Services\Admin\Modules\FactionStatsService;
use App\Services\Admin\Modules\CardStatsService;
use App\Services\Admin\Modules\HeroStatsService;
use App\Services\Admin\Modules\DeckStatsService;

class DashboardService
{
  protected FactionStatsService $factionStatsService;
  protected CardStatsService $cardStatsService;
  protected HeroStatsService $heroStatsService;
  protected DeckStatsService $deckStatsService;

  public function __construct(
    FactionStatsService $factionStatsService,
    CardStatsService $cardStatsService,
    HeroStatsService $heroStatsService,
    DeckStatsService $deckStatsService
  ) {
    $this->factionStatsService = $factionStatsService;
    $this->cardStatsService = $cardStatsService;
    $this->heroStatsService = $heroStatsService;
    $this->deckStatsService = $deckStatsService;
  }

  /**
   * Get all dashboard statistics
   *
   * @return array
   */
  public function getAllStats(): array
  {
    return [
      'factions' => $this->factionStatsService->getStats(),
      'cards' => $this->cardStatsService->getStats(),
      'heroes' => $this->heroStatsService->getStats(),
      'decks' => $this->deckStatsService->getStats(),
    ];
  }
}