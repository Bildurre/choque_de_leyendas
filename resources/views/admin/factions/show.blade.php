<x-admin-layout>
  <x-admin.page-header :title="$faction->name">
    <x-slot:actionButtons>
      <x-action-button
        :href="route('admin.factions.edit', $faction)"
        icon="edit"
        variant="edit"
        size="lg"
        :title="__('admin.edit')"
      />
    
      @if (!$faction->trashed())
        <x-action-button
          :route="route('admin.factions.toggle-published', $faction)"
          :icon="$faction->is_published ? 'globe-slash' : 'globe'"
          :variant="$faction->is_published ? 'unpublish' : 'publish'"
          size="lg"
          method="POST"
          :title="$faction->is_published ? __('admin.unpublish') : __('admin.publish')"
        />
      @else
        <x-action-button
          :route="route('admin.factions.restore', $faction->id)"
          icon="refresh"
          variant="restore"
          size="lg"
          method="POST"
          :title="__('admin.restore')"
        />
      @endif
    
      <x-action-button
        :route="$faction->trashed()
            ? route('admin.factions.force-delete', $faction->id) 
            : route('admin.factions.destroy', $faction)"
        icon="trash"
        variant="delete"
        size="lg"
        method="DELETE"
        :confirm-message="$faction->trashed()
            ? __('entities.factions.confirm_force_delete') 
            : __('entities.factions.confirm_delete')"
        :title="__('admin.delete')"
      />
    </x-slot:actionButtons>

    <x-slot:actions>
      <x-button-link
        :href="route('admin.factions.index')"
        variant="secondary"
        icon="arrow-left"
      >
        {{ __('admin.back_to_list') }}
      </x-button-link>

      {{-- Tab-specific actions --}}
      @if($tab === 'heroes')
        <x-button-link
          :href="route('admin.heroes.create', ['faction_id' => $faction->id])"
          variant="primary"
          icon="plus"
        >
          {{ __('entities.heroes.create') }}
        </x-button-link>
      @elseif($tab === 'cards')
        <x-button-link
          :href="route('admin.cards.create', ['faction_id' => $faction->id])"
          variant="primary"
          icon="plus"
        >
          {{ __('entities.cards.create') }}
        </x-button-link>
      @elseif($tab === 'decks' && isset($gameModes) && $gameModes->count() > 0)
        <x-dropdown 
          :label="__('entities.faction_decks.create')" 
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
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">
    <x-tabs>
      <x-slot:header>
        <x-tab-item 
          id="details" 
          :active="$tab === 'details'" 
          :href="route('admin.factions.show', [$faction, 'tab' => 'details'])"
          icon="info"
        >
          {{ __('entities.factions.tabs.details') }}
        </x-tab-item>
        
        <x-tab-item 
          id="heroes" 
          :active="$tab === 'heroes'" 
          :href="route('admin.factions.show', [$faction, 'tab' => 'heroes'])"
          icon="heroes"
          :count="$faction->heroes_count"
        >
          {{ __('entities.heroes.plural') }}
        </x-tab-item>
        
        <x-tab-item 
          id="cards" 
          :active="$tab === 'cards'" 
          :href="route('admin.factions.show', [$faction, 'tab' => 'cards'])"
          icon="cards"
          :count="$faction->cards_count"
        >
          {{ __('entities.cards.plural') }}
        </x-tab-item>
        
        <x-tab-item 
          id="decks" 
          :active="$tab === 'decks'" 
          :href="route('admin.factions.show', [$faction, 'tab' => 'decks'])"
          icon="decks"
          :count="$faction->faction_decks_count"
        >
          {{ __('entities.faction_decks.plural') }}
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
          @include('admin.factions._decks', ['decks' => $decks, 'faction' => $faction, 'gameModes' => $gameModes])
        @endif
      </x-slot:content>
    </x-tabs>
  </div>
</x-admin-layout>