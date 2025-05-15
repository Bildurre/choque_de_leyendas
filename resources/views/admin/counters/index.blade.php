<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('entities.counters.plural') }}</h1>
  </div>
  
  <div class="page-content">
    <x-filters.card 
      :model="$counterModel" 
      :request="$request" 
      :itemsCount="$counters->count()"
      :totalCount="$totalCount"
      :filteredCount="$filteredCount"
    />

    <x-entity.list 
      :create-route="null"
      :create-label="__('entities.counters.create')"
      :items="$counters"
      :withTabs="true"
      :trashed="$trashed"
      :activeCount="$activeCount ?? null"
      :trashedCount="$trashedCount ?? null"
      baseRoute="admin.counters.index"
    >
      <x-slot:actions>
        @if(!$trashed)
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
      
      @foreach($counters as $counter)
        <x-entity.list-card 
          :title="$counter->name"
          :edit-route="!$trashed ? route('admin.counters.edit', $counter) : null"
          :delete-route="$trashed 
            ? route('admin.counters.force-delete', $counter->id) 
            : route('admin.counters.destroy', $counter)"
          :restore-route="$trashed ? route('admin.counters.restore', $counter->id) : null"
          :confirm-message="$trashed 
            ? __('entities.counters.confirm_force_delete') 
            : __('entities.counters.confirm_delete')"
        >
          <x-slot:badges>
            <x-badge variant="{{ $counter->type === 'boon' ? 'success' : 'danger' }}">
              {{ $counter->type_name }}
            </x-badge>
            
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
          {{ $counters->appends(['trashed' => $trashed ? 1 : null])->links() }}
        </x-slot:pagination>
      @endif
    </x-entity.list>
  </div>
</x-admin-layout>