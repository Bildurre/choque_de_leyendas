<x-admin-layout>
  <x-admin.page-header :title="__('entities.counters.plural')">
    <x-slot:actions>
      @if($tab !== 'trashed')
        <x-dropdown 
          :label="__('entities.counters.create')" 
          icon="plus"
          variant="primary"
        >
          <x-dropdown-item :href="route('admin.counters.create', ['type' => 'boon'])">
            {{ __('entities.counters.types.boon') }}
          </x-dropdown-item>
          <x-dropdown-item :href="route('admin.counters.create', ['type' => 'bane'])">
            {{ __('entities.counters.types.bane') }}
          </x-dropdown-item>
        </x-dropdown>
      @endif
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">
    <x-filters.card 
      :model="$counterModel" 
      :request="$request" 
      :itemsCount="$counters->count()"
      :totalCount="$totalCount"
      :filteredCount="$filteredCount"
    />

    <x-entity.list 
      :items="$counters"
      :withTabs="true"
      :activeTabId="'published'"
      :trashedTabId="'trashed'"
      :trashed="$trashed"
      :activeCount="$publishedCount"
      :trashedCount="$trashedCount"
      :currentTab="$tab"
      baseRoute="admin.counters.index"
    >
      <x-slot:extraTabs>
        <x-tab-item 
          id="unpublished"
          :active="$tab === 'unpublished'" 
          :href="route('admin.counters.index', ['tab' => 'unpublished'])"
          icon="edit"
          :count="$unpublishedCount"
        >
          {{ __('admin.draft') }}
        </x-tab-item>
      </x-slot:extraTabs>
      
      @foreach($counters as $counter)
        <x-entity.list-card 
          :title="$counter->name"
          :edit-route="!$trashed ? route('admin.counters.edit', $counter) : null"
          :delete-route="$trashed 
            ? route('admin.counters.force-delete', $counter->id) 
            : route('admin.counters.destroy', $counter)"
          :restore-route="$trashed ? route('admin.counters.restore', $counter->id) : null"
          :toggle-published-route="!$trashed ? route('admin.counters.toggle-published', $counter) : null"
          :is-published="$counter->isPublished()"
          :confirm-message="$trashed 
            ? __('entities.counters.confirm_force_delete') 
            : __('entities.counters.confirm_delete')"
        >
          <x-slot:badges>
            <x-badge variant="{{ $counter->type === 'boon' ? 'success' : 'danger' }}">
              {{ $counter->type_name }}
            </x-badge>
            
            @if(!$trashed)
              @if($counter->isPublished())
                <x-badge variant="success">
                  {{ __('admin.published') }}
                </x-badge>
              @else
                <x-badge variant="warning">
                  {{ __('admin.draft') }}
                </x-badge>
              @endif
            @endif
            
            @if($trashed)
              <x-badge variant="secondary">
                {{ __('admin.deleted_at', ['date' => $counter->deleted_at->format('d/m/Y H:i')]) }}
              </x-badge>
            @endif
          </x-slot:badges>
          
          @if($counter->icon)
            <div class="counter-icon">
              <img src="{{ $counter->getImageUrl() }}" alt="{{ $counter->name }}" class="counter-icon__image">
            </div>
          @endif
          
          @if($counter->effect)
            <div class="counter-effect">
              {{ strip_tags($counter->effect) }}
            </div>
          @endif
        </x-entity.list-card>
      @endforeach
      
      @if(method_exists($counters, 'links'))
        <x-slot:pagination>
          {{ $counters->appends(['tab' => $tab])->links() }}
        </x-slot:pagination>
      @endif
    </x-entity.list>
  </div>
</x-admin-layout>