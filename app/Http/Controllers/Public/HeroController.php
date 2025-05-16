<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Hero;
use Illuminate\View\View;

class HeroController extends Controller
{
    /**
     * Display a listing of all heroes.
     */
    public function index(): View
    {
        $heroes = Hero::with(['faction', 'heroClass', 'heroRace'])
            ->orderBy('name')
            ->get();
        
        return view('public.heroes.index', compact('heroes'));
    }

    /**
     * Display the specified hero.
     */
    public function show(Hero $hero): View
    {
        // Eager load related models to reduce database queries
        $hero->load(['faction', 'heroClass', 'heroRace', 'heroAbilities']);
        
        return view('public.heroes.show', compact('hero'));
    }
}