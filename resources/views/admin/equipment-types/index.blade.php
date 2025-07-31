<x-admin-layout>
  <x-admin.page-header :title="__('entities.equipment_types.plural')">
    <x-slot:actions>
      @if(!$trashed)
        <x-button-link
          :href="route('admin.equipment-types.create')"
          variant="primary"
          icon="plus"
        >
          {{ __('entities.equipment_types.create') }}
        </x-button-link>
      @endif
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">

    <x-filters.card 
      :model="$equipmentTypeModel" 
      :request="$request" 
      :itemsCount="$equipmentTypes->count()"
      :totalCount="$totalCount"
      :filteredCount="$filteredCount"
    />

    <x-entity.list 
      :items="$equipmentTypes"
      :withTabs="true"
      :trashed="$trashed"
      :activeCount="$activeCount ?? null"
      :trashedCount="$trashedCount ?? null"
      baseRoute="admin.equipment-types.index"
    >
      @foreach($equipmentTypes as $equipmentType)
        <x-entity.list-card 
          :title="$equipmentType->name"
          :edit-route="!$trashed ? route('admin.equipment-types.edit', $equipmentType) : null"
          :delete-route="$trashed 
            ? route('admin.equipment-types.force-delete', $equipmentType->id) 
            : route('admin.equipment-types.destroy', $equipmentType)"
          :restore-route="$trashed ? route('admin.equipment-types.restore', $equipmentType->id) : null"
          :confirm-message="$trashed 
            ? __('entities.equipment_types.confirm_force_delete') 
            : __('entities.equipment_types.confirm_delete')"
        >
          <x-slot:badges>
            <x-badge variant="primary">
              {{ $equipmentType->category_name }}
            </x-badge>
            
            <x-badge variant="info">
              {{ __('entities.equipment_types.cards_count', ['count' => $equipmentType->cards_count]) }}
            </x-badge>
            
            @if($trashed)
              <x-badge variant="danger">
                {{ __('admin.deleted_at', ['date' => $equipmentType->deleted_at->format('d/m/Y H:i')]) }}
              </x-badge>
            @endif
          </x-slot:badges>
        </x-entity.list-card>
      @endforeach
      
      @if(method_exists($equipmentTypes, 'links'))
        <x-slot:pagination>
          {{ $equipmentTypes->appends(['trashed' => $trashed ? 1 : null])->links() }}
        </x-slot:pagination>
      @endif
    </x-entity.list>
  </div>
</x-admin-layout>