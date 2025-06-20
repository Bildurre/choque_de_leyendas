<?php

namespace App\Http\Controllers\Public;

use App\Models\Card;
use App\Models\Hero;
use App\Models\Faction;
use App\Models\FactionDeck;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class PrintCollectionController extends Controller
{
    /**
     * Add an item to the print collection
     */
    public function add(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:hero,card,faction,deck',
            'id' => 'required|integer'
        ]);

        $collection = session('print_collection', [
            'heroes' => [],
            'cards' => [],
            'updated_at' => now()->toDateTimeString()
        ]);

        switch ($validated['type']) {
            case 'hero':
                $collection = $this->addHero($validated['id'], $collection);
                break;
            case 'card':
                $collection = $this->addCard($validated['id'], $collection);
                break;
            case 'faction':
                $collection = $this->addFaction($validated['id'], $collection);
                break;
            case 'deck':
                $collection = $this->addDeck($validated['id'], $collection);
                break;
        }

        $collection['updated_at'] = now()->toDateTimeString();
        session(['print_collection' => $collection]);

        return response()->json([
            'success' => true,
            'message' => $this->getSuccessMessage($validated['type']),
            'count' => $this->getTotalCount($collection)
        ]);
    }

    /**
     * Update the quantity of an item
     */
    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:hero,card',
            'id' => 'required|integer',
            'copies' => 'required|integer|min:1|max:99'
        ]);

        $collection = session('print_collection', [
            'heroes' => [],
            'cards' => [],
            'updated_at' => now()->toDateTimeString()
        ]);

        $key = $validated['type'] . '_' . $validated['id'];
        
        if ($validated['type'] === 'hero' && isset($collection['heroes'][$key])) {
            $collection['heroes'][$key]['copies'] = $validated['copies'];
        } elseif ($validated['type'] === 'card' && isset($collection['cards'][$key])) {
            $collection['cards'][$key]['copies'] = $validated['copies'];
        }

        $collection['updated_at'] = now()->toDateTimeString();
        session(['print_collection' => $collection]);

        return response()->json([
            'success' => true,
            'count' => $this->getTotalCount($collection)
        ]);
    }

    /**
     * Remove an item from the collection
     */
    public function remove(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:hero,card',
            'id' => 'required|integer'
        ]);

        $collection = session('print_collection', [
            'heroes' => [],
            'cards' => [],
            'updated_at' => now()->toDateTimeString()
        ]);

        $key = $validated['type'] . '_' . $validated['id'];
        
        if ($validated['type'] === 'hero') {
            unset($collection['heroes'][$key]);
        } else {
            unset($collection['cards'][$key]);
        }

        $collection['updated_at'] = now()->toDateTimeString();
        session(['print_collection' => $collection]);

        return response()->json([
            'success' => true,
            'count' => $this->getTotalCount($collection)
        ]);
    }

    /**
     * Clear the entire collection
     */
    public function clear(): JsonResponse
    {
        session()->forget('print_collection');

        return response()->json([
            'success' => true,
            'message' => __('public.print_collection_cleared')
        ]);
    }

    /**
     * Show the print collection page
     */
    public function index()
    {
        $collection = session('print_collection', [
            'heroes' => [],
            'cards' => [],
            'updated_at' => now()->toDateTimeString()
        ]);

        // Load the actual models
        $heroIds = array_map(fn($item) => $item['id'], $collection['heroes']);
        $cardIds = array_map(fn($item) => $item['id'], $collection['cards']);

        $heroes = Hero::with(['faction', 'heroClass.heroSuperclass', 'heroRace', 'heroAbilities.attackRange', 'heroAbilities.attackSubtype'])
            ->whereIn('id', $heroIds)
            ->get()
            ->keyBy('id');

        $cards = Card::with(['faction', 'cardType.heroSuperclass', 'equipmentType', 'attackRange', 'attackSubtype', 'heroAbility.attackRange', 'heroAbility.attackSubtype'])
            ->whereIn('id', $cardIds)
            ->get()
            ->keyBy('id');

        return view('public.print-collection.index', compact('collection', 'heroes', 'cards'));
    }

    /**
     * Generate the PDF
     */
    public function generatePdf(Request $request)
    {
        $collection = session('print_collection', [
            'heroes' => [],
            'cards' => [],
            'updated_at' => now()->toDateTimeString()
        ]);

        if (empty($collection['heroes']) && empty($collection['cards'])) {
            return redirect()->route('public.print-collection.index')
                ->with('error', __('public.print_collection_empty'));
        }

        // Load models with all necessary relationships
        $heroIds = array_map(fn($item) => $item['id'], $collection['heroes']);
        $cardIds = array_map(fn($item) => $item['id'], $collection['cards']);

        $heroes = Hero::with(['faction', 'heroClass.heroSuperclass', 'heroRace', 'heroAbilities.attackRange', 'heroAbilities.attackSubtype'])
            ->whereIn('id', $heroIds)
            ->get()
            ->keyBy('id');

        $cards = Card::with(['faction', 'cardType.heroSuperclass', 'equipmentType', 'attackRange', 'attackSubtype', 'heroAbility.attackRange', 'heroAbility.attackSubtype'])
            ->whereIn('id', $cardIds)
            ->get()
            ->keyBy('id');

        // Prepare items for PDF (respecting copies)
        $items = [];
        
        foreach ($collection['heroes'] as $key => $heroData) {
            if (isset($heroes[$heroData['id']])) {
                for ($i = 0; $i < $heroData['copies']; $i++) {
                    $items[] = [
                        'type' => 'hero',
                        'entity' => $heroes[$heroData['id']]
                    ];
                }
            }
        }

        foreach ($collection['cards'] as $key => $cardData) {
            if (isset($cards[$cardData['id']])) {
                for ($i = 0; $i < $cardData['copies']; $i++) {
                    $items[] = [
                        'type' => 'card',
                        'entity' => $cards[$cardData['id']]
                    ];
                }
            }
        }

        // Obtener el parámetro reduce_heroes
        $reduceHeroes = $request->get('reduce_heroes', false);
        $withGap = $request->get('with_gap', true);

        // Configurar opciones de DomPDF
        $pdf = PDF::loadView('public.print-collection.pdf', compact('items', 'reduceHeroes', 'withGap'));
        $pdf->setPaper('a4', 'portrait');
        
        // Configuraciones adicionales para mejorar el renderizado
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isPhpEnabled' => false,
            'defaultFont' => 'sans-serif',
            'dpi' => 150,
            'enable_font_subsetting' => false,
        ]);
        
        // return $pdf->download('alanda-cards-' . date('Y-m-d') . '.pdf');
        return $pdf->stream('alanda-cards-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Helper methods
     */
    private function addHero($id, $collection)
    {
        $hero = Hero::published()->findOrFail($id);
        $key = 'hero_' . $id;

        if (isset($collection['heroes'][$key])) {
            $collection['heroes'][$key]['copies']++;
        } else {
            $collection['heroes'][$key] = [
                'id' => $id,
                'copies' => 1,
                'name' => $hero->name
            ];
        }

        return $collection;
    }

    private function addCard($id, $collection)
    {
        $card = Card::published()->findOrFail($id);
        $key = 'card_' . $id;

        if (isset($collection['cards'][$key])) {
            $collection['cards'][$key]['copies']++;
        } else {
            $collection['cards'][$key] = [
                'id' => $id,
                'copies' => 1,
                'name' => $card->name
            ];
        }

        return $collection;
    }

    private function addFaction($id, $collection)
    {
        $faction = Faction::published()->findOrFail($id);
        
        // Add all heroes from faction
        foreach ($faction->heroes()->published()->get() as $hero) {
            $key = 'hero_' . $hero->id;
            if (isset($collection['heroes'][$key])) {
                $collection['heroes'][$key]['copies']++;
            } else {
                $collection['heroes'][$key] = [
                    'id' => $hero->id,
                    'copies' => 1,
                    'name' => $hero->name
                ];
            }
        }

        // Add all cards from faction
        foreach ($faction->cards()->published()->get() as $card) {
            $key = 'card_' . $card->id;
            if (isset($collection['cards'][$key])) {
                $collection['cards'][$key]['copies']++;
            } else {
                $collection['cards'][$key] = [
                    'id' => $card->id,
                    'copies' => 1,
                    'name' => $card->name
                ];
            }
        }

        return $collection;
    }

    private function addDeck($id, $collection)
    {
        $deck = FactionDeck::published()->findOrFail($id);
        
        // Add heroes with their copies
        foreach ($deck->heroes as $hero) {
            $key = 'hero_' . $hero->id;
            $copiesToAdd = $hero->pivot->copies;
            
            if (isset($collection['heroes'][$key])) {
                $collection['heroes'][$key]['copies'] += $copiesToAdd;
            } else {
                $collection['heroes'][$key] = [
                    'id' => $hero->id,
                    'copies' => $copiesToAdd,
                    'name' => $hero->name
                ];
            }
        }

        // Add cards with their copies
        foreach ($deck->cards as $card) {
            $key = 'card_' . $card->id;
            $copiesToAdd = $card->pivot->copies;
            
            if (isset($collection['cards'][$key])) {
                $collection['cards'][$key]['copies'] += $copiesToAdd;
            } else {
                $collection['cards'][$key] = [
                    'id' => $card->id,
                    'copies' => $copiesToAdd,
                    'name' => $card->name
                ];
            }
        }

        return $collection;
    }

    private function getTotalCount($collection): int
    {
        return count($collection['heroes']) + count($collection['cards']);
    }

    private function getSuccessMessage($type): string
    {
        return match($type) {
            'hero' => __('public.hero_added_to_collection'),
            'card' => __('public.card_added_to_collection'),
            'faction' => __('public.faction_added_to_collection'),
            'deck' => __('public.deck_added_to_collection'),
            default => __('public.added_to_collection')
        };
    }

   /**
 * Generate PDF directly for a faction
 */
public function generateFactionPdf(Request $request, Faction $faction)
{
    // Ya no necesitas buscar la facción, Laravel te la pasa directamente
    // Solo verificar que esté publicada
    if (!$faction->is_published) {
        abort(404);
    }
    
    // Get all heroes from faction (1 copy each)
    $heroes = $faction->heroes()->published()
        ->with(['faction', 'heroClass.heroSuperclass', 'heroRace', 'heroAbilities.attackRange', 'heroAbilities.attackSubtype'])
        ->get();
    
    // Get all cards from faction (2 copies each)
    $cards = $faction->cards()->published()
        ->with(['faction', 'cardType.heroSuperclass', 'equipmentType', 'attackRange', 'attackSubtype', 'heroAbility.attackRange', 'heroAbility.attackSubtype'])
        ->get();
    
    // Prepare items for PDF
    $items = [];
    
    // Add heroes (1 copy each)
    foreach ($heroes as $hero) {
        $items[] = [
            'type' => 'hero',
            'entity' => $hero
        ];
    }
    
    // Add cards (2 copies each)
    foreach ($cards as $card) {
        for ($i = 0; $i < 2; $i++) {
            $items[] = [
                'type' => 'card',
                'entity' => $card
            ];
        }
    }
    
    // Get parameters
    $reduceHeroes = $request->get('reduce_heroes', false);
    $withGap = $request->get('with_gap', true);
    
    // Configure DomPDF options
    $pdf = PDF::loadView('public.print-collection.pdf', compact('items', 'reduceHeroes', 'withGap'));
    $pdf->setPaper('a4', 'portrait');
    
    $pdf->setOptions([
        'isHtml5ParserEnabled' => true,
        'isRemoteEnabled' => true,
        'isPhpEnabled' => false,
        'defaultFont' => 'sans-serif',
        'dpi' => 150,
        'enable_font_subsetting' => false,
    ]);
    
    // Return as stream to open in browser
    return $pdf->stream('alanda-' . Str::slug($faction->name) . '-' . date('Y-m-d') . '.pdf');
}

/**
 * Generate PDF directly for a deck
 */
public function generateDeckPdf(Request $request, FactionDeck $deck)
{
    // Ya no necesitas buscar el deck, Laravel te lo pasa directamente
    // Solo verificar que esté publicado
    if (!$deck->is_published) {
        abort(404);
    }
    
    // Get heroes with their copies
    $heroes = $deck->heroes()->with(['faction', 'heroClass.heroSuperclass', 'heroRace', 'heroAbilities.attackRange', 'heroAbilities.attackSubtype'])->get();
    
    // Get cards with their copies
    $cards = $deck->cards()->with(['faction', 'cardType.heroSuperclass', 'equipmentType', 'attackRange', 'attackSubtype', 'heroAbility.attackRange', 'heroAbility.attackSubtype'])->get();
    
    // Prepare items for PDF (respecting deck copies)
    $items = [];
    
    // Add heroes with their copies
    foreach ($heroes as $hero) {
        $copies = $hero->pivot->copies;
        for ($i = 0; $i < $copies; $i++) {
            $items[] = [
                'type' => 'hero',
                'entity' => $hero
            ];
        }
    }
    
    // Add cards with their copies
    foreach ($cards as $card) {
        $copies = $card->pivot->copies;
        for ($i = 0; $i < $copies; $i++) {
            $items[] = [
                'type' => 'card',
                'entity' => $card
            ];
        }
    }
    
    // Get parameters
    $reduceHeroes = $request->get('reduce_heroes', false);
    $withGap = $request->get('with_gap', true);
    
    // Configure DomPDF options
    $pdf = PDF::loadView('public.print-collection.pdf', compact('items', 'reduceHeroes', 'withGap'));
    $pdf->setPaper('a4', 'portrait');
    
    $pdf->setOptions([
        'isHtml5ParserEnabled' => true,
        'isRemoteEnabled' => true,
        'isPhpEnabled' => false,
        'defaultFont' => 'sans-serif',
        'dpi' => 150,
        'enable_font_subsetting' => false,
    ]);
    
    // Return as stream to open in browser
    return $pdf->stream('alanda-' . Str::slug($deck->name) . '-' . date('Y-m-d') . '.pdf');
}
}