<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('faction_decks.create') }}</h1>
  </div>
  
  <div class="page-content">
    @include('admin.faction-decks._form', [
      'factions' => $factions,
      'gameModes' => $gameModes,
      'selectedFactionId' => $selectedFactionId,
      'availableCards' => $availableCards ?? [],
      'availableHeroes' => $availableHeroes ?? []
    ])
  </div>
</x-admin-layout>