<x-admin-layout>
  <x-admin.page-header :title="__('entities.factions.plural')">
    <x-slot:actions>
      @if($tab !== 'trashed')
        <x-button-link
          :href="route('admin.factions.create')"
          variant="primary"
          icon="plus"
        >
          {{ __('entities.factions.create') }}
        </x-button-link>
      @endif
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">
    <x-filters.card 
      :model="$factionModel" 
      :request="$request" 
      :itemsCount="$factions->count()"
      :totalCount="$totalCount"
      :filteredCount="$filteredCount"
    />

    <x-entity.list 
      :items="$factions"
      :withTabs="true"
      :activeTabId="'published'"
      :trashedTabId="'trashed'"
      :trashed="$trashed"
      :activeCount="$publishedCount"
      :trashedCount="$trashedCount"
      :currentTab="$tab"
      baseRoute="admin.factions.index"
    >
      <x-slot:extraTabs>
        <x-tab-item 
          id="unpublished"
          :active="$tab === 'unpublished'" 
          :href="route('admin.factions.index', ['tab' => 'unpublished'])"
          icon="edit"
          :count="$unpublishedCount"
        >
          {{ __('admin.draft') }}
        </x-tab-item>
      </x-slot:extraTabs>
      
      @foreach($factions as $faction)
        <x-entity.list-card
          :entity="$faction"
          :title="$faction->name"
          :view-route="!$trashed ? route('admin.factions.show', $faction) : null"
          :edit-route="!$trashed ? route('admin.factions.edit', $faction) : null"
          :delete-route="$trashed 
            ? route('admin.factions.force-delete', $faction->id) 
            : route('admin.factions.destroy', $faction)"
          :restore-route="$trashed ? route('admin.factions.restore', $faction->id) : null"
          :toggle-published-route="!$trashed ? route('admin.factions.toggle-published', $faction) : null"
          :is-published="$faction->isPublished()"
          :confirm-message="$trashed 
            ? __('entities.factions.confirm_force_delete') 
            : __('entities.factions.confirm_delete')"
        >
          <div class="faction-details">
            <x-previews.preview-image :entity="$faction" type="faction"/>
          </div>
        </x-entity.list-card>
      @endforeach
      
      @if(method_exists($factions, 'links'))
        <x-slot:pagination>
          {{ $factions->appends(['tab' => $tab])->links() }}
        </x-slot:pagination>
      @endif
    </x-entity.list>
  </div>
</x-admin-layout>