<?php

namespace App\Http\Controllers\Admin;

use App\Models\Card;
use App\Models\Hero;
use App\Models\Faction;
use App\Http\Controllers\Controller;
use App\Services\Admin\PreviewManagementService;
use App\Http\Requests\Admin\PreviewManagementRequest;

class PreviewManagementController extends Controller
{
  public function __construct(
    private PreviewManagementService $previewService
  ) {}

  public function index()
  {
    $statusData = $this->previewService->getStatusData();
    $entities = $this->previewService->getEntitiesForSelectors();
    
    return view('admin.preview-management.index', array_merge(
      compact('statusData'),
      $entities
    ));
  }
  
  public function generateAll()
  {
    try {
      $this->previewService->generateAll();
      
      return redirect()->route('admin.previews.index')
        ->with('success', __('previews.generate_all_queued'));
    } catch (\Exception $e) {
      return redirect()->route('admin.previews.index')
        ->with('error', __('previews.generate_all_failed', [
          'error' => $e->getMessage()
        ]));
    }
  }
  
  public function regenerateAll()
  {
    try {
      $this->previewService->regenerateAll();
      
      return redirect()->route('admin.previews.index')
        ->with('success', __('previews.regenerate_all_queued'));
    } catch (\Exception $e) {
      return redirect()->route('admin.previews.index')
        ->with('error', __('previews.regenerate_all_failed', [
          'error' => $e->getMessage()
        ]));
    }
  }
  
  public function deleteAllHeroes()
  {
    try {
      $exitCode = $this->previewService->deleteAllHeroes();
      
      if ($exitCode === 0) {
        return redirect()->route('admin.previews.index')
          ->with('success', __('previews.delete_all_heroes_success'));
      }
      
      return redirect()->route('admin.previews.index')
        ->with('error', __('previews.delete_all_heroes_failed', [
          'error' => 'Command failed with exit code: ' . $exitCode
        ]));
    } catch (\Exception $e) {
      return redirect()->route('admin.previews.index')
        ->with('error', __('previews.delete_all_heroes_failed', [
          'error' => $e->getMessage()
        ]));
    }
  }
  
  public function deleteAllCards()
  {
    try {
      $exitCode = $this->previewService->deleteAllCards();
      
      if ($exitCode === 0) {
        return redirect()->route('admin.previews.index')
          ->with('success', __('previews.delete_all_cards_success'));
      }
      
      return redirect()->route('admin.previews.index')
        ->with('error', __('previews.delete_all_cards_failed', [
          'error' => 'Command failed with exit code: ' . $exitCode
        ]));
    } catch (\Exception $e) {
      return redirect()->route('admin.previews.index')
        ->with('error', __('previews.delete_all_cards_failed', [
          'error' => $e->getMessage()
        ]));
    }
  }
  
  public function deleteAll()
  {
    try {
      $exitCode = $this->previewService->deleteAll();
      
      if ($exitCode === 0) {
        return redirect()->route('admin.previews.index')
          ->with('success', __('previews.delete_all_success'));
      }
      
      return redirect()->route('admin.previews.index')
        ->with('error', __('previews.delete_all_failed', [
          'error' => 'Command failed with exit code: ' . $exitCode
        ]));
    } catch (\Exception $e) {
      return redirect()->route('admin.previews.index')
        ->with('error', __('previews.delete_all_failed', [
          'error' => $e->getMessage()
        ]));
    }
  }
  
  public function cleanOrphaned()
  {
    try {
      $exitCode = $this->previewService->cleanOrphaned();
      
      $output = \Artisan::output();
      
      if (str_contains($output, 'No orphaned files found')) {
        return redirect()->route('admin.previews.index')
          ->with('info', __('previews.no_orphaned_files'));
      }
      
      if ($exitCode === 0) {
        return redirect()->route('admin.previews.index')
          ->with('success', __('previews.clean_success'));
      }
      
      return redirect()->route('admin.previews.index')
        ->with('error', __('previews.clean_failed', [
          'error' => 'Command failed with exit code: ' . $exitCode
        ]));
    } catch (\Exception $e) {
      return redirect()->route('admin.previews.index')
        ->with('error', __('previews.clean_failed', [
          'error' => $e->getMessage()
        ]));
    }
  }

  public function individualHero(PreviewManagementRequest $request)
  {
    $hero = Hero::findOrFail($request->hero_id);
    $action = $request->action;
      
    try {
      $this->previewService->handleIndividualHero($hero, $action);
      
      if ($action === 'regenerate') {
        return redirect()->route('admin.previews.index')
          ->with('success', __('previews.regenerate_queued', [
            'type' => __('entities.heroes.singular'),
            'name' => $hero->name
          ]));
      } else {
        return redirect()->route('admin.previews.index')
          ->with('success', __('previews.delete_success', [
            'type' => __('entities.heroes.singular'),
            'name' => $hero->name
          ]));
      }
    } catch (\Exception $e) {
      $errorKey = $action === 'regenerate' 
        ? 'admin.previews.regenerate_failed'
        : 'admin.previews.delete_failed';
          
      return redirect()->route('admin.previews.index')
        ->with('error', __($errorKey, ['error' => $e->getMessage()]));
    }
  }

  public function individualCard(PreviewManagementRequest $request)
  {
    $card = Card::findOrFail($request->card_id);
    $action = $request->action;
    
    try {
      $this->previewService->handleIndividualCard($card, $action);
      
      if ($action === 'regenerate') {
        return redirect()->route('admin.previews.index')
          ->with('success', __('previews.regenerate_queued', [
            'type' => __('entities.cards.singular'),
            'name' => $card->name
          ]));
      } else {
        return redirect()->route('admin.previews.index')
          ->with('success', __('previews.delete_success', [
            'type' => __('entities.cards.singular'),
            'name' => $card->name
          ]));
      }
    } catch (\Exception $e) {
      $errorKey = $action === 'regenerate' 
        ? 'admin.previews.regenerate_failed'
        : 'admin.previews.delete_failed';
          
      return redirect()->route('admin.previews.index')
        ->with('error', __($errorKey, ['error' => $e->getMessage()]));
    }
  }

  public function factionAction(PreviewManagementRequest $request)
  {
    $faction = Faction::findOrFail($request->faction_id);
    $type = $request->type;
    $action = $request->action;
    
    try {
      $this->previewService->handleFactionAction($faction, $type, $action);
      
      if ($action === 'regenerate') {
        return redirect()->route('admin.previews.index')
          ->with('success', __('previews.regenerate_faction_queued', [
            'name' => $faction->name
          ]));
      } else {
        return redirect()->route('admin.previews.index')
          ->with('success', __('previews.delete_faction_success', [
            'name' => $faction->name
          ]));
      }
    } catch (\Exception $e) {
      $errorKey = $action === 'regenerate' 
        ? 'admin.previews.regenerate_faction_failed'
        : 'admin.previews.delete_faction_failed';
          
      return redirect()->route('admin.previews.index')
        ->with('error', __($errorKey, ['error' => $e->getMessage()]));
    }
  }

  public function regenerate(string $model, int $id)
  {
    if ($model === 'hero') {
      $hero = Hero::findOrFail($id);
      try {
        $this->previewService->handleIndividualHero($hero, 'regenerate');
        
        return redirect()->back()
          ->with('success', __('previews.regenerate_queued', [
            'type' => __('entities.heroes.singular'),
            'name' => $hero->name
          ]));
      } catch (\Exception $e) {
        return redirect()->back()
          ->with('error', __('previews.regenerate_failed', ['error' => $e->getMessage()]));
      }
    } elseif ($model === 'card') {
      $card = Card::findOrFail($id);
      try {
        $this->previewService->handleIndividualCard($card, 'regenerate');
        
        return redirect()->back()
          ->with('success', __('previews.regenerate_queued', [
            'type' => __('entities.cards.singular'),
            'name' => $card->name
          ]));
      } catch (\Exception $e) {
        return redirect()->back()
          ->with('error', __('previews.regenerate_failed', ['error' => $e->getMessage()]));
      }
    }
    
    abort(404, __('previews.invalid_model_type'));
  }

  public function delete(string $model, int $id)
  {
    if ($model === 'hero') {
      $hero = Hero::findOrFail($id);
      try {
        $this->previewService->handleIndividualHero($hero, 'delete');
        
        return redirect()->back()
          ->with('success', __('previews.delete_success', [
            'type' => __('entities.heroes.singular'),
            'name' => $hero->name
          ]));
      } catch (\Exception $e) {
        return redirect()->back()
          ->with('error', __('previews.delete_failed', ['error' => $e->getMessage()]));
      }
    } elseif ($model === 'card') {
      $card = Card::findOrFail($id);
      try {
        $this->previewService->handleIndividualCard($card, 'delete');
        
        return redirect()->back()
          ->with('success', __('previews.delete_success', [
            'type' => __('entities.cards.singular'),
            'name' => $card->name
          ]));
      } catch (\Exception $e) {
        return redirect()->back()
          ->with('error', __('previews.delete_failed', ['error' => $e->getMessage()]));
      }
    }
    
    abort(404, __('previews.invalid_model_type'));
  }

  public function regenerateFaction(Faction $faction)
  {
    $type = request()->input('type', 'all');
    
    try {
      $this->previewService->handleFactionAction($faction, $type, 'regenerate');
      
      return redirect()->back()
        ->with('success', __('previews.regenerate_faction_queued', [
          'name' => $faction->name
        ]));
    } catch (\Exception $e) {
      return redirect()->back()
        ->with('error', __('previews.regenerate_faction_failed', ['error' => $e->getMessage()]));
    }
  }

  public function deleteFaction(Faction $faction)
  {
    $type = request()->input('type', 'all');
    
    try {
      $this->previewService->handleFactionAction($faction, $type, 'delete');
      
      return redirect()->back()
        ->with('success', __('previews.delete_faction_success', [
          'name' => $faction->name
        ]));
    } catch (\Exception $e) {
      return redirect()->back()
        ->with('error', __('previews.delete_faction_failed', ['error' => $e->getMessage()]));
    }
  }
}