<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('entities.faction_decks.create') }}</h1>
  </div>
  
  <div class="page-content">
    @include('admin.faction-decks._form', [
      'factions' => $factions,
      'gameModes' => $gameModes,
      'gameMode' => $gameMode,
      'deckConfig' => $deckConfig,
      'gameModeId' => $gameModeId,
      'allCards' => $allCards,
      'allHeroes' => $allHeroes,
      'selectedFaction' => $selectedFaction ?? null,
      'factionId' => $factionId ?? null
    ])
  </div>
</x-admin-layout>