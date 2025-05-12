<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Game\FactionDeckService;

class FactionController extends Controller
{
  protected $factionDeckService;

  public function __construct(FactionDeckService $factionDeckService)
  {
    $this->factionDeckService = $factionDeckService;
  }

  /**
   * Get cards for a faction
   *
   * @param int $faction
   * @return \Illuminate\Http\JsonResponse
   */
  public function getCards($faction)
  {
    try {
      $cards = $this->factionDeckService->getAvailableCards($faction);
      
      return response()->json($cards);
    } catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }

  /**
   * Get heroes for a faction
   *
   * @param int $faction
   * @return \Illuminate\Http\JsonResponse
   */
  public function getHeroes($faction)
  {
    try {
      $heroes = $this->factionDeckService->getAvailableHeroes($faction);
      
      return response()->json($heroes);
    } catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }
}