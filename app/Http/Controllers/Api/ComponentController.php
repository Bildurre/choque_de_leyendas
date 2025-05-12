<?php
// app/Http/Controllers/Api/ComponentController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Hero;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ComponentController extends Controller
{
    /**
     * Render cards selector component
     */
    public function renderCardsSelector(Request $request)
    {
        try {
            // Obtener parámetros
            $factionId = $request->query('faction_id');
            $maxCopies = $request->query('max_copies', 2);
            
            // Si no tenemos datos de cards en el request, cargar desde la base de datos
            if (!$request->has('cards')) {
                $cards = Card::where('faction_id', $factionId)
                    ->with(['cardType']) // Cargar relaciones necesarias
                    ->get();
            } else {
                // Usar los datos proporcionados en el request
                $cards = collect($request->input('cards'));
            }
            
            $selected = $request->input('selected', []);
            
            // Renderizar el componente como HTML
            $html = view('components.form.cards-selector', [
                'cards' => $cards,
                'selected' => $selected,
                'maxCopies' => $maxCopies,
                'label' => __('faction_decks.add_cards')
            ])->render();
            
            return response($html);
            
        } catch (\Exception $e) {
            
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Render heroes selector component
     */
    public function renderHeroesSelector(Request $request)
    {
        try {
            // Obtener parámetros
            $factionId = $request->query('faction_id');
            $maxCopies = $request->query('max_copies', 1);
            
            // Si no tenemos datos de heroes en el request, cargar desde la base de datos
            if (!$request->has('heroes')) {
                $heroes = Hero::where('faction_id', $factionId)
                    ->with(['heroClass']) // Cargar relaciones necesarias
                    ->get();
            } else {
                // Usar los datos proporcionados en el request
                $heroes = collect($request->input('heroes'));
            }
            
            $selected = $request->input('selected', []);
            
            
            // Renderizar el componente como HTML
            $html = view('components.form.heroes-selector', [
                'heroes' => $heroes,
                'selected' => $selected,
                'maxCopies' => $maxCopies,
                'label' => __('faction_decks.add_heroes')
            ])->render();
            
            return response($html);
            
        } catch (\Exception $e) {
            
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}