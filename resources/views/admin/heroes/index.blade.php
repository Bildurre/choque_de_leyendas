<x-admin-layout>
  <x-admin.page-header :title="__('entities.heroes.plural')">
    <x-slot:actions>
      @if($tab !== 'trashed')
        <x-button-link
          :href="route('admin.heroes.create')"
          variant="primary"
          icon="plus"
        >
          {{ __('entities.heroes.create') }}
        </x-button-link>
      @endif
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">
    <x-filters.card 
      :model="$heroModel" 
      :request="$request" 
      :itemsCount="$heroes->count()"
      :totalCount="$totalCount"
      :filteredCount="$filteredCount"
    />
    
    <x-entity.list 
      :items="$heroes"
      :withTabs="true"
      :activeTabId="'published'"
      :trashedTabId="'trashed'"
      :trashed="$trashed"
      :activeCount="$publishedCount"
      :trashedCount="$trashedCount"
      :currentTab="$tab"
      baseRoute="admin.heroes.index"
    >
      <x-slot:extraTabs>
        <x-tab-item 
          id="unpublished"
          :active="$tab === 'unpublished'" 
          :href="route('admin.heroes.index', ['tab' => 'unpublished'])"
          icon="edit"
          :count="$unpublishedCount"
        >
          {{ __('admin.draft') }}
        </x-tab-item>
      </x-slot:extraTabs>
      
      @foreach($heroes as $hero)
        <x-entity.list-card
          :entity="$hero"
          :title="$hero->name"
          :view-route="!$trashed ? route('admin.heroes.show', $hero) : null"
          :edit-route="!$trashed ? route('admin.heroes.edit', $hero) : null"
          :delete-route="$trashed 
            ? route('admin.heroes.force-delete', $hero->id) 
            : route('admin.heroes.destroy', $hero)"
          :restore-route="$trashed ? route('admin.heroes.restore', $hero->id) : null"
          :toggle-published-route="!$trashed ? route('admin.heroes.toggle-published', $hero) : null"
          :is-published="$hero->isPublished()"
          :confirm-message="$trashed 
            ? __('entities.heroes.confirm_force_delete') 
            : __('entities.heroes.confirm_delete')"
        >          
          <div class="hero-details">
            <x-previews.preview-image :entity="$hero" type="hero"/>
          </div>
        </x-entity.list-card>
      @endforeach
      
      @if(method_exists($heroes, 'links'))
        <x-slot:pagination>
          {{ $heroes->appends(['tab' => $tab])->links() }}
        </x-slot:pagination>
      @endif
    </x-entity.list>
  </div>
</x-admin-layout>