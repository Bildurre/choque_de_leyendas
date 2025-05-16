<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Faction;
use Illuminate\View\View;

class FactionController extends Controller
{
    /**
     * Display a listing of all factions.
     */
    public function index(): View
    {
        $factions = Faction::published()->orderBy('name')->get();
        
        return view('public.factions.index', compact('factions'));
    }

    /**
     * Display the specified faction.
     */
    public function show(Faction $faction): View
    {
        // Eager load related models to reduce database queries
        $faction->load(['heroes', 'cards']);
        
        return view('public.factions.show', compact('faction'));
    }
}