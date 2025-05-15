<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('entities.card_types.plural') }}</h1>
  </div>
  
  <div class="page-content">

    <x-filters.card 
      :model="$cardTypeModel" 
      :request="$request" 
      :itemsCount="$cardTypes->count()"
      :totalCount="$totalCount"
      :filteredCount="$filteredCount"
    />

    <x-entity.list 
      :create-route="!$trashed ? route('admin.card-types.create') : null"
      :create-label="__('entities.card_types.create')"
      :items="$cardTypes"
      :withTabs="true"
      :trashed="$trashed"
      :activeCount="$activeCount ?? null"
      :trashedCount="$trashedCount ?? null"
      baseRoute="admin.card-types.index"
    >
      @foreach($cardTypes as $cardType)
        <x-entity.list-card 
          :title="$cardType->name"
          :edit-route="!$trashed ? route('admin.card-types.edit', $cardType) : null"
          :delete-route="$trashed 
            ? route('admin.card-types.force-delete', $cardType->id) 
            : route('admin.card-types.destroy', $cardType)"
          :restore-route="$trashed ? route('admin.card-types.restore', $cardType->id) : null"
          :confirm-message="$trashed 
            ? __('entities.card_types.confirm_force_delete') 
            : __('entities.card_types.confirm_delete')"
        >
          <x-slot:badges>
            <x-badge variant="info">
              {{ __('entities.card_types.cards_count', ['count' => $cardType->cards_count]) }}
            </x-badge>
            
            @if($cardType->heroSuperclass)
              <x-badge variant="primary">
                {{ __('entities.hero_superclasses.singular') }}: {{ $cardType->heroSuperclass->name }}
              </x-badge>
            @endif
            
            @if($trashed)
              <x-badge variant="danger">
                {{ __('admin.deleted_at', ['date' => $cardType->deleted_at->format('d/m/Y H:i')]) }}
              </x-badge>
            @endif
          </x-slot:badges>
        </x-entity.list-card>
      @endforeach
      
      @if(method_exists($cardTypes, 'links'))
        <x-slot:pagination>
          {{ $cardTypes->appends(['trashed' => $trashed ? 1 : null])->links() }}
        </x-slot:pagination>
      @endif
    </x-entity.list>
  </div>
</x-admin-layout>