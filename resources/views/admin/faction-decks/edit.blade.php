<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('entities.faction_decks.edit') }}: {{ $factionDeck->name }}</h1>
  </div>
  
  <div class="page-content">
    @include('admin.faction-decks._form', [
      'factionDeck' => $factionDeck,
      'factions' => $factions,
      'gameModes' => $gameModes,
      'gameMode' => $gameMode,
      'deckConfig' => $deckConfig,
      'gameModeId' => $gameModeId,
      'allCards' => $allCards,
      'allHeroes' => $allHeroes,
      'selectedCards' => $selectedCards,
      'selectedHeroes' => $selectedHeroes
    ])
  </div>
</x-admin-layout>