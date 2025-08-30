<?php

namespace App\Services\Admin;

use App\Models\Card;
use App\Models\Hero;
use App\Models\Faction;
use Illuminate\Support\Facades\Artisan;

class PreviewManagementService
{
  public function getStatusData()
  {
    Artisan::call('preview:manage', ['action' => 'status']);
    $statusOutput = Artisan::output();
    
    return $this->parseStatusOutput($statusOutput);
  }

  public function getEntitiesForSelectors()
  {
    return [
      'heroes' => Hero::orderBy('name')->pluck('name', 'id')->toArray(),
      'cards' => Card::orderBy('name')->pluck('name', 'id')->toArray(),
      'factions' => Faction::orderBy('name')->pluck('name', 'id')->toArray(),
    ];
  }

  public function generateAll()
  {
    Artisan::queue('preview:manage', [
      'action' => 'generate-all'
    ]);
  }

  public function regenerateAll()
  {
    Artisan::queue('preview:manage', [
      'action' => 'regenerate'
    ]);
  }

  public function deleteAllHeroes()
  {
    return Artisan::call('preview:manage', [
      'action' => 'delete-heroes',
      '--no-interaction' => true
    ]);
  }

  public function deleteAllCards()
  {
    return Artisan::call('preview:manage', [
      'action' => 'delete-cards',
      '--no-interaction' => true
    ]);
  }

  public function deleteAll()
  {
    return Artisan::call('preview:manage', [
      'action' => 'delete-all',
      '--no-interaction' => true
    ]);
  }

  public function cleanOrphaned()
  {
    return Artisan::call('preview:manage', [
      'action' => 'clean',
      '--no-interaction' => true
    ]);
  }

  public function handleIndividualHero($hero, $action)
  {
    if ($action === 'regenerate') {
      Artisan::queue('preview:manage', [
        'action' => 'generate',
        '--model' => 'hero',
        '--id' => $hero->id,
        '--force' => true
      ]);
    } else {
      Artisan::call('preview:manage', [
        'action' => 'delete',
        '--model' => 'hero',
        '--id' => $hero->id
      ]);
    }
  }

  public function handleIndividualCard($card, $action)
  {
    if ($action === 'regenerate') {
      Artisan::queue('preview:manage', [
        'action' => 'generate',
        '--model' => 'card',
        '--id' => $card->id,
        '--force' => true
      ]);
    } else {
      Artisan::call('preview:manage', [
        'action' => 'delete',
        '--model' => 'card',
        '--id' => $card->id
      ]);
    }
  }

  public function handleFactionAction($faction, $type, $action)
  {
    if ($action === 'regenerate') {
      Artisan::queue('preview:manage', [
        'action' => 'regenerate-faction',
        '--faction' => $faction->id,
        '--type' => $type
      ]);
    } else {
      Artisan::call('preview:manage', [
        'action' => 'delete-faction',
        '--faction' => $faction->id,
        '--type' => $type,
        '--no-interaction' => true
      ]);
    }
  }

  private function parseStatusOutput($output)
  {
    $data = [
      'heroes' => [
        'total' => 0,
        'complete' => 0,
        'partial' => 0,
        'missing' => 0
      ],
      'cards' => [
        'total' => 0,
        'complete' => 0,
        'partial' => 0,
        'missing' => 0
      ],
      'disk' => [
        'heroes' => '0 B',
        'cards' => '0 B',
        'total' => '0 B'
      ]
    ];
    
    $lines = explode("\n", $output);
    $section = '';
    
    foreach ($lines as $line) {
      if (str_contains($line, 'HEROES STATUS')) {
        $section = 'heroes';
      } elseif (str_contains($line, 'CARDS STATUS')) {
        $section = 'cards';
      } elseif (str_contains($line, 'DISK USAGE')) {
        $section = 'disk';
      }
      
      if ($section && str_contains($line, '|')) {
        $parts = explode('|', $line);
        if (count($parts) >= 3) {
          $label = trim($parts[1] ?? '');
          $value = trim($parts[2] ?? '');
          
          if ($section === 'heroes' || $section === 'cards') {
            switch($label) {
              case 'Complete':
                $data[$section]['complete'] = (int)$value;
                break;
              case 'Partial':
                $data[$section]['partial'] = (int)$value;
                break;
              case 'Missing':
                $data[$section]['missing'] = (int)$value;
                break;
              case 'Total':
                $data[$section]['total'] = (int)$value;
                break;
            }
          } elseif ($section === 'disk') {
            switch($label) {
              case 'Heroes':
                $data['disk']['heroes'] = $value;
                break;
              case 'Cards':
                $data['disk']['cards'] = $value;
                break;
              case 'Total':
                $data['disk']['total'] = $value;
                break;
            }
          }
        }
      }
    }
    
    return $data;
  }
}