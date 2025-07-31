<?php

namespace App\Http\Controllers\Game;

use App\Models\Card;
use App\Models\Faction;
use App\Models\CardType;
use App\Models\AttackRange;
use App\Models\AttackSubtype;
use App\Models\EquipmentType;
use App\Models\HeroAbility;
use App\Services\Game\CardService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\CardRequest;
use Illuminate\Http\Request;

class CardController extends Controller
{
  protected $cardService;

  /**
   * Create a new controller instance.
   *
   * @param CardService $cardService
   */
  public function __construct(CardService $cardService)
  {
    $this->cardService = $cardService;
  }

  /**
   * Display a listing of cards.
   */
  public function index(Request $request)
  {
    $tab = $request->get('tab', 'published'); // Default to published
    
    // Get counters for tabs
    $publishedCount = Card::where('is_published', true)->count();
    $unpublishedCount = Card::where('is_published', false)->count();
    $trashedCount = Card::onlyTrashed()->count();
    
    // Determine filters based on tab
    $trashed = ($tab === 'trashed');
    $onlyPublished = ($tab === 'published');
    $onlyUnpublished = ($tab === 'unpublished');
    
    // Get cards with filtering and pagination
    $cards = $this->cardService->getAllCards(
      $request,         // request for filters
      12,               // perPage
      false,            // withTrashed
      $trashed,         // onlyTrashed
      $onlyPublished,   // onlyPublished
      $onlyUnpublished  // onlyUnpublished
    );
    
    // Create a Card instance for filter component
    $cardModel = new Card();
    
    // Get counts from the paginated result
    $totalCount = $cards->totalCount ?? 0;
    $filteredCount = $cards->filteredCount ?? 0;
    
    return view('admin.cards.index', compact(
      'cards', 
      'tab',
      'trashed', 
      'publishedCount',
      'unpublishedCount',
      'trashedCount',
      'cardModel',
      'request',
      'totalCount',
      'filteredCount'
    ));
  }

  /**
   * Show the form for creating a new card.
   */
  public function create(Request $request)
  {
    $factions = Faction::orderBy('id')->get();
    $cardTypes = CardType::orderBy('id')->get();
    $equipmentTypes = EquipmentType::orderBy('category')->orderBy('id')->get();
    $attackRanges = AttackRange::orderBy('id')->get();
    $attackSubtypes = AttackSubtype::orderBy('type')->orderBy('id')->get();
    $heroAbilities = HeroAbility::orderBy('id')->get();
    
    $selectedFactionId = $request->query('faction_id');
    
    return view('admin.cards.create', compact(
      'factions',
      'cardTypes',
      'equipmentTypes',
      'attackRanges',
      'attackSubtypes',
      'heroAbilities',
      'selectedFactionId'
    ));
  }

  /**
   * Store a newly created card in storage.
   */
  public function store(CardRequest $request)
  {
    $validated = $request->validated();

    try {
      $card = $this->cardService->create($validated);
      return redirect()->route('admin.cards.index')
        ->with('success', __('cards.created_successfully', ['name' => $card->name]));
    } catch (\Exception $e) {
      return back()
        ->with('error', __('common.errors.create', ['entity' => __('entities.cards.singular')]))
        ->withInput();
    }
  }

  /**
   * Display the specified card.
   */
  public function show(Card $card)
  {
    // Carga las relaciones necesarias en caso de que no estén cargadas
    if (!$card->relationLoaded('faction')) {
      $card->load([
        'faction', 
        'cardType', 
        'equipmentType', 
        'attackRange', 
        'attackSubtype',
        'heroAbility'
      ]);
    }
    
    return view('admin.cards.show', compact('card'));
  }

  /**
   * Show the form for editing the specified card.
   */
  public function edit(Card $card)
  {
    $factions = Faction::orderBy('id')->get();
    $cardTypes = CardType::orderBy('id')->get();
    $equipmentTypes = EquipmentType::orderBy('category')->orderBy('id')->get();
    $attackRanges = AttackRange::orderBy('id')->get();
    $attackSubtypes = AttackSubtype::orderBy('type')->orderBy('id')->get();
    $heroAbilities = HeroAbility::orderBy('id')->get();
    
    return view('admin.cards.edit', compact(
      'card',
      'factions',
      'cardTypes',
      'equipmentTypes',
      'attackRanges',
      'attackSubtypes',
      'heroAbilities'
    ));
  }

  /**
   * Update the specified card in storage.
   */
  public function update(CardRequest $request, Card $card)
  {
    $validated = $request->validated();

    try {
      $this->cardService->update($card, $validated);
      return redirect()->route('admin.cards.index')
        ->with('success', __('cards.updated_successfully', ['name' => $card->name]));
    } catch (\Exception $e) {
      return back()
        ->with('error', __('common.errors.update', ['entity' => __('entities.cards.singular')]))
        ->withInput();
    }
  }

  /**
   * Remove the specified card from storage.
   */
  public function destroy(Card $card)
  {
    try {
      $cardName = $card->name;
      $this->cardService->delete($card);
      
      return redirect()->route('admin.cards.index')
        ->with('success', __('cards.deleted_successfully', ['name' => $cardName]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.delete', ['entity' => __('entities.cards.singular')]));
    }
  }

  /**
   * Restore the specified card from trash.
   */
  public function restore($id)
  {
    try {
      $this->cardService->restore($id);
      $card = Card::find($id);
      
      return redirect()->route('admin.cards.index', ['tab' => 'trashed'])
        ->with('success', __('cards.restored_successfully', ['name' => $card->name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.restore', ['entity' => __('entities.cards.singular')]));
    }
  }

  /**
   * Force delete the specified card from storage.
   */
  public function forceDelete($id)
  {
    try {
      $card = Card::onlyTrashed()->findOrFail($id);
      $name = $card->name;
      
      $this->cardService->forceDelete($id);
      
      return redirect()->route('admin.cards.index', ['tab' => 'trashed'])
        ->with('success', __('cards.force_deleted_successfully', ['name' => $name]));
    } catch (\Exception $e) {
      return back()->with('error', __('common.errors.force_delete', ['entity' => __('entities.cards.singular')]));
    }
  }

  /**
   * Toggle the published status of the specified card.
   */
  public function togglePublished(Card $card)
  {
    $card->togglePublished();

    $statusMessage = $card->isPublished() 
      ? __('cards.published_successfully', ['name' => $card->name])
      : __('cards.unpublished_successfully', ['name' => $card->name]);

    // Redirect to appropriate tab based on new status
    $tab = $card->isPublished() ? 'published' : 'unpublished';
    
    return redirect()->route('admin.cards.index', ['tab' => $tab])
      ->with('success', $statusMessage);
  }
}