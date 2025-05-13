<x-admin-layout>
  <div class="page-header">
    <div class="page-header__content">
      <h1 class="page-title">{{ $faction->name }}</h1>
      
      <div class="page-header__actions">
        <x-button-link
          :href="route('admin.factions.index')"
          variant="secondary"
          icon="arrow-left"
        >
          {{ __('admin.back_to_list') }}
        </x-button-link>
        
        <x-button-link
          :href="route('admin.factions.edit', $faction)"
          variant="primary"
          icon="edit"
        >
          {{ __('admin.edit') }}
        </x-button-link>

        @if($tab === 'heroes')
          <x-button-link
            :href="route('admin.heroes.create', ['faction_id' => $faction->id])"
            variant="primary"
            icon="plus"
          >
            {{ __('heroes.create') }}
          </x-button-link>
        @elseif($tab === 'cards')
          <x-button-link
            :href="route('admin.cards.create', ['faction_id' => $faction->id])"
            variant="primary"
            icon="plus"
          >
            {{ __('cards.create') }}
          </x-button-link>
        @elseif($tab === 'decks' && isset($gameModes) && $gameModes->count() > 0)
          <x-dropdown 
            :label="__('faction_decks.create')" 
            icon="plus"
            variant="primary"
          >
            @foreach($gameModes as $gameMode)
              <x-dropdown-item :href="route('admin.faction-decks.create', ['faction_id' => $faction->id, 'game_mode_id' => $gameMode->id])">
                {{ $gameMode->name }}
              </x-dropdown-item>
            @endforeach
          </x-dropdown>
        @endif
      </div>
    </div>
  </div>
  
  <div class="page-content">
    <div class="faction-view">
      <x-tabs>
        <x-slot:header>
          <x-tab-item 
            id="details" 
            :active="$tab === 'details'" 
            :href="route('admin.factions.show', ['faction' => $faction, 'tab' => 'details'])"
            icon="info"
          >
            {{ __('factions.tabs.details') }}
          </x-tab-item>
          
          <x-tab-item 
            id="heroes" 
            :active="$tab === 'heroes'" 
            :href="route('admin.factions.show', ['faction' => $faction, 'tab' => 'heroes'])"
            icon="users"
            :count="$faction->heroes_count"
          >
            {{ __('heroes.plural') }}
          </x-tab-item>
          
          <x-tab-item 
            id="cards" 
            :active="$tab === 'cards'" 
            :href="route('admin.factions.show', ['faction' => $faction, 'tab' => 'cards'])"
            icon="layers"
            :count="$faction->cards_count"
          >
            {{ __('cards.plural') }}
          </x-tab-item>
          
          <x-tab-item 
            id="decks" 
            :active="$tab === 'decks'" 
            :href="route('admin.factions.show', ['faction' => $faction, 'tab' => 'decks'])"
            icon="box"
            :count="$faction->faction_decks_count"
          >
            {{ __('faction_decks.plural') }}
          </x-tab-item>
        </x-slot:header>
        
        <x-slot:content>
          @if($tab === 'details')
            @include('admin.factions._details', ['faction' => $faction])
          @elseif($tab === 'heroes')
            @include('admin.factions._heroes', ['heroes' => $heroes, 'faction' => $faction])
          @elseif($tab === 'cards')
            @include('admin.factions._cards', ['cards' => $cards, 'faction' => $faction])
          @elseif($tab === 'decks')
            @include('admin.factions._decks', ['decks' => $decks, 'faction' => $faction])
          @endif
        </x-slot:content>
      </x-tabs>
    </div>
  </div>
</x-admin-layout>