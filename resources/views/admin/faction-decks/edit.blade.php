<x-admin-layout>
  <x-admin.page-header :title="__('entities.faction_decks.edit')">
    <x-slot:actions>
      <x-button-link
        :href="route('admin.faction-decks.index')"
        variant="primary"
        icon="arrow-left"
      >
        {{ __('admin.back_to_list') }}
      </x-button-link>
    </x-slot:actions>
  </x-admin.page-header>
  
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