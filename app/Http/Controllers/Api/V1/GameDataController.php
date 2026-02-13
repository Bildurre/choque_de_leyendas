<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CardResource;
use App\Http\Resources\Api\V1\CardTypeResource;
use App\Http\Resources\Api\V1\CounterResource;
use App\Http\Resources\Api\V1\EquipmentTypeResource;
use App\Http\Resources\Api\V1\FactionDeckResource;
use App\Http\Resources\Api\V1\FactionResource;
use App\Http\Resources\Api\V1\GameModeResource;
use App\Http\Resources\Api\V1\HeroAbilityResource;
use App\Http\Resources\Api\V1\HeroClassResource;
use App\Http\Resources\Api\V1\HeroResource;
use App\Http\Resources\Api\V1\SimpleResource;
use App\Models\AttackRange;
use App\Models\AttackSubtype;
use App\Models\Card;
use App\Models\CardSubtype;
use App\Models\CardType;
use App\Models\Counter;
use App\Models\DeckAttributesConfiguration;
use App\Models\EquipmentType;
use App\Models\Faction;
use App\Models\FactionDeck;
use App\Models\GameMode;
use App\Models\Hero;
use App\Models\HeroAbility;
use App\Models\HeroAttributesConfiguration;
use App\Models\HeroClass;
use App\Models\HeroRace;
use App\Models\HeroSuperclass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GameDataController extends Controller
{
  /**
   * Return all game data in a single request.
   */
  public function index(Request $request): JsonResponse
  {
    $this->applyLocale($request);

    return response()->json([
      'factions' => FactionResource::collection(
        Faction::where('is_published', true)->orderBy('name')->get()
      ),
      'heroes' => HeroResource::collection(
        Hero::where('is_published', true)
          ->with('heroAbilities')
          ->orderBy('name')
          ->get()
      ),
      'cards' => CardResource::collection(
        Card::where('is_published', true)->orderBy('name')->get()
      ),
      'hero_abilities' => HeroAbilityResource::collection(
        HeroAbility::orderBy('name')->get()
      ),
      'card_types' => CardTypeResource::collection(
        CardType::orderBy('name')->get()
      ),
      'card_subtypes' => SimpleResource::collection(
        CardSubtype::orderBy('name')->get()
      ),
      'hero_superclasses' => SimpleResource::collection(
        HeroSuperclass::orderBy('name')->get()
      ),
      'hero_classes' => HeroClassResource::collection(
        HeroClass::orderBy('name')->get()
      ),
      'hero_races' => SimpleResource::collection(
        HeroRace::orderBy('name')->get()
      ),
      'equipment_types' => EquipmentTypeResource::collection(
        EquipmentType::orderBy('name')->get()
      ),
      'attack_ranges' => SimpleResource::collection(
        AttackRange::orderBy('name')->get()
      ),
      'attack_subtypes' => SimpleResource::collection(
        AttackSubtype::orderBy('name')->get()
      ),
      'counters' => CounterResource::collection(
        Counter::where('is_published', true)->orderBy('name')->get()
      ),
      'game_modes' => GameModeResource::collection(
        GameMode::with('deckConfiguration')->orderBy('name')->get()
      ),
      'faction_decks' => FactionDeckResource::collection(
        FactionDeck::where('is_published', true)
          ->with(['factions', 'heroes', 'cards'])
          ->orderBy('name')
          ->get()
      ),
      'config' => [
        'hero_attributes' => $this->getHeroAttributesConfig(),
      ],
    ]);
  }

  /**
   * List all published factions.
   */
  public function factions(Request $request): JsonResponse
  {
    $this->applyLocale($request);

    return response()->json([
      'data' => FactionResource::collection(
        Faction::where('is_published', true)->orderBy('name')->get()
      ),
    ]);
  }

  /**
   * List all published heroes with their abilities.
   */
  public function heroes(Request $request): JsonResponse
  {
    $this->applyLocale($request);

    return response()->json([
      'data' => HeroResource::collection(
        Hero::where('is_published', true)
          ->with('heroAbilities')
          ->orderBy('name')
          ->get()
      ),
    ]);
  }

  /**
   * List all published cards.
   */
  public function cards(Request $request): JsonResponse
  {
    $this->applyLocale($request);

    return response()->json([
      'data' => CardResource::collection(
        Card::where('is_published', true)->orderBy('name')->get()
      ),
    ]);
  }

  /**
   * List all hero abilities.
   */
  public function heroAbilities(Request $request): JsonResponse
  {
    $this->applyLocale($request);

    return response()->json([
      'data' => HeroAbilityResource::collection(
        HeroAbility::orderBy('name')->get()
      ),
    ]);
  }

  /**
   * List all card types.
   */
  public function cardTypes(Request $request): JsonResponse
  {
    $this->applyLocale($request);

    return response()->json([
      'data' => CardTypeResource::collection(
        CardType::orderBy('name')->get()
      ),
    ]);
  }

  /**
   * List all card subtypes.
   */
  public function cardSubtypes(Request $request): JsonResponse
  {
    $this->applyLocale($request);

    return response()->json([
      'data' => SimpleResource::collection(
        CardSubtype::orderBy('name')->get()
      ),
    ]);
  }

  /**
   * List all hero superclasses.
   */
  public function heroSuperclasses(Request $request): JsonResponse
  {
    $this->applyLocale($request);

    return response()->json([
      'data' => SimpleResource::collection(
        HeroSuperclass::orderBy('name')->get()
      ),
    ]);
  }

  /**
   * List all hero classes.
   */
  public function heroClasses(Request $request): JsonResponse
  {
    $this->applyLocale($request);

    return response()->json([
      'data' => HeroClassResource::collection(
        HeroClass::orderBy('name')->get()
      ),
    ]);
  }

  /**
   * List all hero races.
   */
  public function heroRaces(Request $request): JsonResponse
  {
    $this->applyLocale($request);

    return response()->json([
      'data' => SimpleResource::collection(
        HeroRace::orderBy('name')->get()
      ),
    ]);
  }

  /**
   * List all equipment types.
   */
  public function equipmentTypes(Request $request): JsonResponse
  {
    $this->applyLocale($request);

    return response()->json([
      'data' => EquipmentTypeResource::collection(
        EquipmentType::orderBy('name')->get()
      ),
    ]);
  }

  /**
   * List all attack ranges.
   */
  public function attackRanges(Request $request): JsonResponse
  {
    $this->applyLocale($request);

    return response()->json([
      'data' => SimpleResource::collection(
        AttackRange::orderBy('name')->get()
      ),
    ]);
  }

  /**
   * List all attack subtypes.
   */
  public function attackSubtypes(Request $request): JsonResponse
  {
    $this->applyLocale($request);

    return response()->json([
      'data' => SimpleResource::collection(
        AttackSubtype::orderBy('name')->get()
      ),
    ]);
  }

  /**
   * List all published counters.
   */
  public function counters(Request $request): JsonResponse
  {
    $this->applyLocale($request);

    return response()->json([
      'data' => CounterResource::collection(
        Counter::where('is_published', true)->orderBy('name')->get()
      ),
    ]);
  }

  /**
   * List all game modes with deck configuration.
   */
  public function gameModes(Request $request): JsonResponse
  {
    $this->applyLocale($request);

    return response()->json([
      'data' => GameModeResource::collection(
        GameMode::with('deckConfiguration')->orderBy('name')->get()
      ),
    ]);
  }

  /**
   * List all published faction decks with relations.
   */
  public function factionDecks(Request $request): JsonResponse
  {
    $this->applyLocale($request);

    return response()->json([
      'data' => FactionDeckResource::collection(
        FactionDeck::where('is_published', true)
          ->with(['factions', 'heroes', 'cards'])
          ->orderBy('name')
          ->get()
      ),
    ]);
  }

  /**
   * Get hero attributes configuration.
   */
  public function heroAttributesConfig(): JsonResponse
  {
    return response()->json([
      'data' => $this->getHeroAttributesConfig(),
    ]);
  }

  /**
   * Set the app locale from the request query parameter.
   */
  private function applyLocale(Request $request): void
  {
    $locale = $request->query('locale', 'es');

    if (in_array($locale, ['es', 'en'])) {
      app()->setLocale($locale);
    }
  }

  /**
   * Get hero attributes configuration as array.
   */
  private function getHeroAttributesConfig(): array
  {
    $config = HeroAttributesConfiguration::getDefault();

    return [
      'min_attribute_value' => $config->min_attribute_value,
      'max_attribute_value' => $config->max_attribute_value,
      'min_total_attributes' => $config->min_total_attributes,
      'max_total_attributes' => $config->max_total_attributes,
      'agility_multiplier' => $config->agility_multiplier,
      'mental_multiplier' => $config->mental_multiplier,
      'will_multiplier' => $config->will_multiplier,
      'strength_multiplier' => $config->strength_multiplier,
      'armor_multiplier' => $config->armor_multiplier,
      'total_health_base' => $config->total_health_base,
    ];
  }
}
