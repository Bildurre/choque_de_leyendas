<?php

namespace App\Http\Controllers\Game;

use App\Models\Card;
use App\Models\Faction;
use App\Models\CardType;
use App\Models\CardSubtype;
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

  public function __construct(CardService $cardService)
  {
    $this->cardService = $cardService;
  }

  public function index(Request $request)
  {
    $tab = $request->get('tab', 'published');
    
    $publishedCount = Card::where('is_published', true)->count();
    $unpublishedCount = Card::where('is_published', false)->count();
    $trashedCount = Card::onlyTrashed()->count();
    
    $trashed = ($tab === 'trashed');
    $onlyPublished = ($tab === 'published');
    $onlyUnpublished = ($tab === 'unpublished');
    
    $cards = $this->cardService->getAllCards(
      $request,
      12,
      false,
      $trashed,
      $onlyPublished,
      $onlyUnpublished
    );
    
    $cardModel = new Card();
    
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

  public function create(Request $request)
  {
    $factions = Faction::orderBy('id')->get();
    $cardTypes = CardType::orderBy('id')->get();
    $cardSubtypes = CardSubtype::orderBy('id')->get();
    $equipmentTypes = EquipmentType::orderBy('category')->orderBy('id')->get();
    $attackRanges = AttackRange::orderBy('id')->get();
    $attackSubtypes = AttackSubtype::orderBy('id')->get();
    $locale = app()->getLocale();
    $heroAbilities = HeroAbility::orderByRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.\"{$locale}\"'))")->get();
    
    $selectedFactionId = $request->query('faction_id');
    
    return view('admin.cards.create', compact(
      'factions',
      'cardTypes',
      'cardSubtypes',
      'equipmentTypes',
      'attackRanges',
      'attackSubtypes',
      'heroAbilities',
      'selectedFactionId'
    ));
  }

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

  public function show(Card $card)
  {
    if (!$card->relationLoaded('faction')) {
      $card->load([
        'faction', 
        'cardType',
        'cardSubtype',
        'equipmentType', 
        'attackRange', 
        'attackSubtype',
        'heroAbility'
      ]);
    }
    
    return view('admin.cards.show', compact('card'));
  }

  public function edit(Card $card)
  {
    $factions = Faction::orderBy('id')->get();
    $cardTypes = CardType::orderBy('id')->get();
    $cardSubtypes = CardSubtype::orderBy('id')->get();
    $equipmentTypes = EquipmentType::orderBy('category')->orderBy('id')->get();
    $attackRanges = AttackRange::orderBy('id')->get();
    $attackSubtypes = AttackSubtype::orderBy('id')->get();
    $locale = app()->getLocale();
    $heroAbilities = HeroAbility::orderByRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.\"{$locale}\"'))")->get();
    
    return view('admin.cards.edit', compact(
      'card',
      'factions',
      'cardTypes',
      'cardSubtypes',
      'equipmentTypes',
      'attackRanges',
      'attackSubtypes',
      'heroAbilities'
    ));
  }

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

  public function togglePublished(Card $card)
  {
    $card->togglePublished();

    $statusMessage = $card->isPublished() 
      ? __('cards.published_successfully', ['name' => $card->name])
      : __('cards.unpublished_successfully', ['name' => $card->name]);

    $tab = $card->isPublished() ? 'published' : 'unpublished';
    
    return redirect()->route('admin.cards.index', ['tab' => $tab])
      ->with('success', $statusMessage);
  }
}