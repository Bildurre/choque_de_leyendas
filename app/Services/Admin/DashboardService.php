<?php

namespace App\Services\Admin;

use App\Services\Admin\Modules\FactionStatsService;
use App\Services\Admin\Modules\CardStatsService;
use App\Services\Admin\Modules\HeroStatsService;
use App\Services\Admin\Modules\DeckStatsService;
use App\Services\Admin\Modules\FactionDetailStatsService;

class DashboardService
{
  protected FactionStatsService $factionStatsService;
  protected CardStatsService $cardStatsService;
  protected HeroStatsService $heroStatsService;
  protected DeckStatsService $deckStatsService;
  protected FactionDetailStatsService $factionDetailStatsService;

  public function __construct(
    FactionStatsService $factionStatsService,
    CardStatsService $cardStatsService,
    HeroStatsService $heroStatsService,
    DeckStatsService $deckStatsService,
    FactionDetailStatsService $factionDetailStatsService
  ) {
    $this->factionStatsService = $factionStatsService;
    $this->cardStatsService = $cardStatsService;
    $this->heroStatsService = $heroStatsService;
    $this->deckStatsService = $deckStatsService;
    $this->factionDetailStatsService = $factionDetailStatsService;
  }

  /**
   * Get all dashboard statistics
   *
   * @param int|null $selectedFactionId
   * @return array
   */
  public function getAllStats(?int $selectedFactionId = null): array
  {
    $stats = [
      'factions' => $this->factionStatsService->getStats(),
      'cards' => $this->cardStatsService->getStats(),
      'heroes' => $this->heroStatsService->getStats(),
      'decks' => $this->deckStatsService->getStats(),
      'factions_list' => $this->factionDetailStatsService->getAllFactionsBasicInfo(),
    ];

    // Add faction detail stats if a faction is selected
    if ($selectedFactionId) {
      $stats['faction_details'] = $this->factionDetailStatsService->getStatsForFaction($selectedFactionId);
    }

    return $stats;
  }
}