<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Game\DeckAttributesConfigurationService;
use Illuminate\Http\Request;

class DeckConfigurationController extends Controller
{
    protected $deckAttributesConfigurationService;

    public function __construct(DeckAttributesConfigurationService $deckAttributesConfigurationService)
    {
        $this->deckAttributesConfigurationService = $deckAttributesConfigurationService;
    }

    /**
     * Get deck configuration for a game mode
     */
    public function getForGameMode($gameMode)
    {
        try {
            $config = $this->deckAttributesConfigurationService->getConfiguration($gameMode);
            
            return response()->json([
                'min_cards' => $config->min_cards,
                'max_cards' => $config->max_cards,
                'max_copies_per_card' => $config->max_copies_per_card,
                'max_copies_per_hero' => $config->max_copies_per_hero
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}