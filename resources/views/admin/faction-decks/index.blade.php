<!-- resources/views/admin/faction-decks/index.blade.php -->
<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('faction_decks.plural') }}</h1>
  </div>
  
  <div class="page-content">
    <x-entity.list 
      :create-route="false"
      :items="$factionDecks"
      :withTabs="true"
      :trashed="$trashed"
      :activeCount="$activeCount ?? null"
      :trashedCount="$trashedCount ?? null"
      baseRoute="admin.faction-decks.index"
    >
      <x-slot:actions>
        @if(!$trashed)
          <div class="dropdown-create-button">
            <x-dropdown
              label="{{ __('faction_decks.create') }}"
              icon="plus"
              variant="primary"
            >
              @foreach($gameModes as $gameMode)
                <x-dropdown-item :href="route('admin.faction-decks.create', ['game_mode_id' => $gameMode->id])">
                  {{ $gameMode->name }}
                </x-dropdown-item>
              @endforeach
            </x-dropdown>
          </div>
        @endif
      </x-slot:actions>

      @foreach($factionDecks as $factionDeck)
        <x-entity.list-card 
          :title="$factionDeck->name"
          :view-route="!$trashed ? route('admin.faction-decks.show', $factionDeck) : null"
          :edit-route="!$trashed ? route('admin.faction-decks.edit', $factionDeck) : null"
          :delete-route="$trashed 
            ? route('admin.faction-decks.force-delete', $factionDeck->id) 
            : route('admin.faction-decks.destroy', $factionDeck)"
          :restore-route="$trashed ? route('admin.faction-decks.restore', $factionDeck->id) : null"
          :confirm-message="$trashed 
            ? __('faction_decks.confirm_force_delete') 
            : __('faction_decks.confirm_delete')"
        >
          <x-slot:badges>
            <x-badge 
              :variant="$factionDeck->faction->text_is_dark ? 'light' : 'dark'" 
              style="background-color: {{ $factionDeck->faction->color }};"
            >
              {{ $factionDeck->faction->name }}
            </x-badge>
            
            <x-badge variant="info">
              {{ $factionDeck->gameMode->name }}
            </x-badge>
            
            <x-badge variant="primary">
              {{ __('faction_decks.cards_count', ['count' => $factionDeck->cards_count]) }}
            </x-badge>
            
            <x-badge variant="success">
              {{ __('faction_decks.heroes_count', ['count' => $factionDeck->heroes_count]) }}
            </x-badge>
            
            @if($trashed)
              <x-badge variant="danger">
                {{ __('admin.deleted_at', ['date' => $factionDeck->deleted_at->format('d/m/Y H:i')]) }}
              </x-badge>
            @endif
          </x-slot:badges>
          
          <div class="faction-deck-details">
            <div class="faction-deck-details__icon">
              @if($factionDeck->icon)
                <img src="{{ $factionDeck->getIconUrl() }}" alt="{{ $factionDeck->name }}" class="faction-deck-details__image">
              @else
                <div class="faction-deck-details__placeholder">
                  <x-icon name="box" size="lg" />
                </div>
              @endif
            </div>
          </div>
        </x-entity.list-card>
      @endforeach
      
      @if(method_exists($factionDecks, 'links'))
        <x-slot:pagination>
          {{ $factionDecks->appends(['trashed' => $trashed ? 1 : null, 'faction_id' => $factionId, 'game_mode_id' => $gameModeId])->links() }}
        </x-slot:pagination>
      @endif
    </x-entity.list>
  </div>
</x-admin-layout>