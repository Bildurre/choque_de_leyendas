<x-admin-layout>
  <x-admin.page-header :title="__('entities.cards.plural')">
    <x-slot:actions>
      @if($tab !== 'trashed')
        <x-button-link
          :href="route('admin.cards.create')"
          variant="primary"
          icon="plus"
        >
          {{ __('entities.cards.create') }}
        </x-button-link>
      @endif
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">
    <x-filters.card 
      :model="$cardModel" 
      :request="$request" 
      :itemsCount="$cards->count()"
      :totalCount="$totalCount"
      :filteredCount="$filteredCount"
    />

    <x-entity.list 
      :items="$cards"
      :withTabs="true"
      :activeTabId="'published'"
      :trashedTabId="'trashed'"
      :trashed="$trashed"
      :activeCount="$publishedCount"
      :trashedCount="$trashedCount"
      :currentTab="$tab"
      baseRoute="admin.cards.index"
    >
      <x-slot:extraTabs>
        <x-tab-item 
          id="unpublished"
          :active="$tab === 'unpublished'" 
          :href="route('admin.cards.index', ['tab' => 'unpublished'])"
          icon="edit"
          :count="$unpublishedCount"
        >
          {{ __('admin.draft') }}
        </x-tab-item>
      </x-slot:extraTabs>

      @foreach($cards as $card)
        <x-entity.list-card
          :entity="$card"
          :title="$card->name"
          :view-route="!$trashed ? route('admin.cards.show', $card) : null"
          :edit-route="!$trashed ? route('admin.cards.edit', $card) : null"
          :delete-route="$trashed 
            ? route('admin.cards.force-delete', $card->id) 
            : route('admin.cards.destroy', $card)"
          :restore-route="$trashed ? route('admin.cards.restore', $card->id) : null"
          :toggle-published-route="!$trashed ? route('admin.cards.toggle-published', $card) : null"
          :is-published="$card->isPublished()"
          :confirm-message="$trashed 
            ? __('entities.cards.confirm_force_delete') 
            : __('entities.cards.confirm_delete')"
        >          
          <div class="card-details">
            <x-previews.preview-image :entity="$card" type="card"/>
            
            @if($card->cardSubtype)
              <div class="card-subtype-info">
                <x-badge variant="secondary">
                  {{ $card->cardSubtype->name }}
                </x-badge>
              </div>
            @endif
          </div>
        </x-entity.list-card>
      @endforeach
      
      @if(method_exists($cards, 'links'))
        <x-slot:pagination>
          {{ $cards->appends(['tab' => $tab])->links() }}
        </x-slot:pagination>
      @endif
    </x-entity.list>
  </div>
</x-admin-layout>