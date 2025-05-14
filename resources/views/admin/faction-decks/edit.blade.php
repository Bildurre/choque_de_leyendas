<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('entities.faction_decks.edit') }}: {{ $factionDeck->name }}</h1>
  </div>
  
  <div class="page-content">
    @include('admin.faction-decks._form', [
      'factionDeck' => $factionDeck,
      'factions' => $factions,
      'gameModes' => $gameModes,
      'availableCards' => $availableCards,
      'availableHeroes' => $availableHeroes,
      'selectedCards' => $selectedCards,
      'selectedHeroes' => $selectedHeroes
    ])
  </div>
</x-admin-layout>