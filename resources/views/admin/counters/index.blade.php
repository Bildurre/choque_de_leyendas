<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('entities.counters.plural') }}</h1>
  </div>
  
  <div class="page-content">
    <x-tabs>
      <x-slot:header>
        <x-tab-item 
          id="boons" 
          :active="$activeTab === 'boons'" 
          :href="route('admin.counters.index', ['tab' => 'boons'])"
          icon="plus-circle"
          :count="$boonsCount"
        >
          {{ __('entities.counters.types.boon') }}
        </x-tab-item>
        
        <x-tab-item 
          id="banes" 
          :active="$activeTab === 'banes'" 
          :href="route('admin.counters.index', ['tab' => 'banes'])"
          icon="minus-circle"
          :count="$banesCount"
        >
          {{ __('entities.counters.types.bane') }}
        </x-tab-item>
        
        <x-tab-item 
          id="trashed" 
          :active="$activeTab === 'trashed'" 
          :href="route('admin.counters.index', ['tab' => 'trashed'])"
          icon="trash"
          :count="$trashedCount"
        >
          {{ __('admin.trashed') }}
        </x-tab-item>
      </x-slot:header>
      
      <x-slot:content>
        <x-entity.list 
          :create-route="null"
          :create-label="__('entities.counters.create')"
          :items="$counters"
          :withTabs="false"
          :showHeader="true"
        >
          <x-slot:actions>
            @if($activeTab !== 'trashed')
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
              :edit-route="$activeTab !== 'trashed' ? route('admin.counters.edit', $counter) : null"
              :delete-route="$activeTab === 'trashed' 
                ? route('admin.counters.force-delete', $counter->id) 
                : route('admin.counters.destroy', $counter)"
              :restore-route="$activeTab === 'trashed' ? route('admin.counters.restore', $counter->id) : null"
              :confirm-message="$activeTab === 'trashed' 
                ? __('entities.counters.confirm_force_delete') 
                : __('entities.counters.confirm_delete')"
            >
              <x-slot:badges>
                <x-badge variant="{{ $counter->type === 'boon' ? 'success' : 'danger' }}">
                  {{ $counter->type_name }}
                </x-badge>
                
                @if($activeTab === 'trashed')
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
              {{ $counters->appends(['tab' => $activeTab])->links() }}
            </x-slot:pagination>
          @endif
        </x-entity.list>
      </x-slot:content>
    </x-tabs>
  </div>
</x-admin-layout>