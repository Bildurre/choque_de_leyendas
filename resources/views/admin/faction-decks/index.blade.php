<x-admin-layout>
  <x-admin.page-header :title="__('entities.faction_decks.plural')">
    <x-slot:actions>
      @if($tab !== 'trashed')
        <x-dropdown 
          :label="__('entities.faction_decks.create')" 
          icon="plus"
          variant="primary"
        >
          @foreach($gameModes as $gameMode)
            <x-dropdown-item :href="route('admin.faction-decks.create', ['game_mode_id' => $gameMode->id])">
              {{ $gameMode->name }}
            </x-dropdown-item>
          @endforeach
        </x-dropdown>
      @endif
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">
    <x-filters.card 
      :model="$factionDeckModel" 
      :request="$request" 
      :itemsCount="$factionDecks->count()"
      :totalCount="$totalCount"
      :filteredCount="$filteredCount"
    />

    <x-entity.list 
      :items="$factionDecks"
      :withTabs="true"
      :activeTabId="'published'"
      :trashedTabId="'trashed'"
      :trashed="$trashed"
      :activeCount="$publishedCount"
      :trashedCount="$trashedCount"
      :currentTab="$tab"
      baseRoute="admin.faction-decks.index"
    >
      <x-slot:extraTabs>
        <x-tab-item 
          id="unpublished"
          :active="$tab === 'unpublished'" 
          :href="route('admin.faction-decks.index', ['tab' => 'unpublished'])"
          icon="edit"
          :count="$unpublishedCount"
        >
          {{ __('admin.draft') }}
        </x-tab-item>
      </x-slot:extraTabs>

      @foreach($factionDecks as $factionDeck)
        <x-entity.list-card 
          :title="$factionDeck->name"
          :view-route="route('admin.faction-decks.show', $factionDeck)"
          :edit-route="route('admin.faction-decks.edit', $factionDeck)"
          :delete-route="$trashed 
            ? route('admin.faction-decks.force-delete', $factionDeck->id) 
            : route('admin.faction-decks.destroy', $factionDeck)"
          :restore-route="$trashed ? route('admin.faction-decks.restore', $factionDeck->id) : null"
          :toggle-published-route="!$trashed ? route('admin.faction-decks.toggle-published', $factionDeck) : null"
          :is-published="$factionDeck->isPublished()"
          :confirm-message="$trashed 
            ? __('entities.faction_decks.confirm_force_delete') 
            : __('entities.faction_decks.confirm_delete')"
        >
          <div class="deck-details">
            <x-previews.preview-image :entity="$factionDeck" type="deck"/>
          </div>
        </x-entity.list-card>
      @endforeach
      
      @if(method_exists($factionDecks, 'links'))
        <x-slot:pagination>
          {{ $factionDecks->appends(['tab' => $tab])->links() }}
        </x-slot:pagination>
      @endif
    </x-entity.list>
  </div>
</x-admin-layout>