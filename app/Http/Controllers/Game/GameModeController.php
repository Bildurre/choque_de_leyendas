<?php

namespace App\Http\Controllers\Game;

use App\Models\GameMode;
use App\Services\Game\GameModeService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\GameModeRequest;
use Illuminate\Http\Request;

class GameModeController extends Controller
{
  protected $gameModeService;

  /**
   * Create a new controller instance.
   *
   * @param GameModeService $gameModeService
   */
  public function __construct(GameModeService $gameModeService)
  {
    $this->gameModeService = $gameModeService;
  }

  /**
   * Display a listing of game modes.
   */
  public function index(Request $request)
  {
    $trashed = $request->has('trashed');
    
    // Get counters for tabs
    $activeCount = GameMode::count();
    $trashedCount = GameMode::onlyTrashed()->count();
    
    $gameModes = $this->gameModeService->getAllGameModes(12, false, $trashed);
    
    return view('admin.game-modes.index', compact('gameModes', 'trashed', 'activeCount', 'trashedCount'));
  }

  /**
   * Show the form for creating a new game mode.
   */
  public function create()
  {
    return view('admin.game-modes.create');
  }

  /**
   * Store a newly created game mode in storage.
   */
  public function store(GameModeRequest $request)
  {
    $validated = $request->validated();

    try {
      $gameMode = $this->gameModeService->create($validated);
      return redirect()->route('admin.game-modes.index')
        ->with('success', __('game_modes.created_successfully', ['name' => $gameMode->name]));
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al crear el Modo de Juego: ' . $e->getMessage())
        ->withInput();
    }
  }

  /**
   * Show the form for editing the specified game mode.
   */
  public function edit(GameMode $gameMode)
  {
    return view('admin.game-modes.edit', compact('gameMode'));
  }

  /**
   * Update the specified game mode in storage.
   */
  public function update(GameModeRequest $request, GameMode $gameMode)
  {
    $validated = $request->validated();

    try {
      $this->gameModeService->update($gameMode, $validated);
      return redirect()->route('admin.game-modes.index')
        ->with('success', __('game_modes.updated_successfully', ['name' => $gameMode->name]));
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al actualizar el Modo de Juego: ' . $e->getMessage())
        ->withInput();
    }
  }

  /**
   * Remove the specified game mode from storage.
   */
  public function destroy(GameMode $gameMode)
  {
    try {
      $gameModeName = $gameMode->name;
      $this->gameModeService->delete($gameMode);
      
      return redirect()->route('admin.game-modes.index')
        ->with('success', __('game_modes.deleted_successfully', ['name' => $gameModeName]));
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al eliminar el Modo de Juego: ' . $e->getMessage());
    }
  }

  /**
   * Restore the specified game mode from trash.
   */
  public function restore($id)
  {
    try {
      $this->gameModeService->restore($id);
      $gameMode = GameMode::find($id);
      
      return redirect()->route('admin.game-modes.index', ['trashed' => 1])
        ->with('success', __('game_modes.restored_successfully', ['name' => $gameMode->name]));
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al restaurar el Modo de Juego: ' . $e->getMessage());
    }
  }

  /**
   * Force delete the specified game mode from storage.
   */
  public function forceDelete($id)
  {
    try {
      $gameMode = GameMode::onlyTrashed()->findOrFail($id);
      $name = $gameMode->name;
      
      $this->gameModeService->forceDelete($id);
      
      return redirect()->route('admin.game-modes.index', ['trashed' => 1])
        ->with('success', __('game_modes.force_deleted_successfully', ['name' => $name]));
    } catch (\Exception $e) {
      return back()->with('error', 'Ha ocurrido un error al eliminar permanentemente el Modo de Juego: ' . $e->getMessage());
    }
  }
}