<x-admin-layout>
  <x-admin.page-header :title="__('entities.attack_ranges.plural')">
    <x-slot:actions>
      @if(!$trashed)
        <x-button-link
          :href="route('admin.attack-ranges.create')"
          variant="primary"
          icon="plus"
        >
          {{ __('entities.attack_ranges.create') }}
        </x-button-link>
      @endif
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">

    <x-filters.card 
      :model="$attackRangeModel" 
      :request="$request" 
      :itemsCount="$attackRanges->count()"
      :totalCount="$totalCount"
      :filteredCount="$filteredCount"
    />

    <x-entity.list
      :items="$attackRanges"
      :withTabs="true"
      :trashed="$trashed"
      :activeCount="$activeCount ?? null"
      :trashedCount="$trashedCount ?? null"
      baseRoute="admin.attack-ranges.index"
    >
      @foreach($attackRanges as $attackRange)
        <x-entity.list-card 
          :title="$attackRange->name"
          :edit-route="!$trashed ? route('admin.attack-ranges.edit', $attackRange) : null"
          :delete-route="$trashed 
            ? route('admin.attack-ranges.force-delete', $attackRange->id) 
            : route('admin.attack-ranges.destroy', $attackRange)"
          :restore-route="$trashed ? route('admin.attack-ranges.restore', $attackRange->id) : null"
          :confirm-message="$trashed 
            ? __('entities.attack_ranges.confirm_force_delete') 
            : __('entities.attack_ranges.confirm_delete')"
        >
          <x-slot:badges>
            <x-badge variant="info">
              {{ __('entities.attack_ranges.hero_abilities_count', ['count' => $attackRange->hero_abilities_count]) }}
            </x-badge>
          
            <x-badge variant="primary">
              {{ __('entities.attack_ranges.cards_count', ['count' => $attackRange->cards_count]) }}
            </x-badge>
            
            @if($trashed)
              <x-badge variant="danger">
                {{ __('admin.deleted_at', ['date' => $attackRange->deleted_at->format('d/m/Y H:i')]) }}
              </x-badge>
            @endif
          </x-slot:badges>
        </x-entity.list-card>
      @endforeach
      
      @if(method_exists($attackRanges, 'links'))
        <x-slot:pagination>
          {{ $attackRanges->appends(['trashed' => $trashed ? 1 : null])->links() }}
        </x-slot:pagination>
      @endif
    </x-entity.list>
  </div>
</x-admin-layout>