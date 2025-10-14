<x-admin-layout>
  <x-admin.page-header :title="__('entities.card_subtypes.plural')">
    <x-slot:actions>
      @if(!$trashed)
        <x-button-link
          :href="route('admin.card-subtypes.create')"
          variant="primary"
          icon="plus"
        >
          {{ __('entities.card_subtypes.create') }}
        </x-button-link>
      @endif
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">
    <x-filters.card 
      :model="$cardSubtypeModel" 
      :request="$request" 
      :itemsCount="$cardSubtypes->count()"
      :totalCount="$totalCount"
      :filteredCount="$filteredCount"
    />

    <x-entity.list 
      :items="$cardSubtypes"
      :withTabs="true"
      :trashed="$trashed"
      :activeCount="$activeCount ?? null"
      :trashedCount="$trashedCount ?? null"
      baseRoute="admin.card-subtypes.index"
    >
      @foreach($cardSubtypes as $cardSubtype)
        <x-entity.list-card 
          :title="$cardSubtype->name"
          :edit-route="!$trashed ? route('admin.card-subtypes.edit', $cardSubtype) : null"
          :delete-route="$trashed 
            ? route('admin.card-subtypes.force-delete', $cardSubtype->id) 
            : route('admin.card-subtypes.destroy', $cardSubtype)"
          :restore-route="$trashed ? route('admin.card-subtypes.restore', $cardSubtype->id) : null"
          :confirm-message="$trashed 
            ? __('entities.card_subtypes.confirm_force_delete') 
            : __('entities.card_subtypes.confirm_delete')"
        >
          <x-slot:badges>
            <x-badge variant="secondary">
              {{ __('entities.card_subtypes.cards_count', ['count' => $cardSubtype->cards_count]) }}
            </x-badge>
            
            @if($trashed)
              <x-badge variant="danger">
                {{ __('admin.deleted_at', ['date' => $cardSubtype->deleted_at->format('d/m/Y H:i')]) }}
              </x-badge>
            @endif
          </x-slot:badges>
        </x-entity.list-card>
      @endforeach
      
      @if(method_exists($cardSubtypes, 'links'))
        <x-slot:pagination>
          {{ $cardSubtypes->links() }}
        </x-slot:pagination>
      @endif
    </x-entity.list>
  </div>
</x-admin-layout>