<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('equipment_types.plural') }}</h1>
  </div>
  
  <div class="page-content">
    <x-entity.list 
      :create-route="!$trashed ? route('admin.equipment-types.create') : null"
      :create-label="__('equipment_types.create')"
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
            ? __('equipment_types.confirm_force_delete') 
            : __('equipment_types.confirm_delete')"
        >
          <x-slot:badges>
            <x-badge variant="primary">
              {{ $equipmentType->category_name }}
            </x-badge>
            
            <x-badge variant="info">
              {{ __('equipment_types.cards_count', ['count' => $equipmentType->cards_count]) }}
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
          {{ $equipmentTypes->appends(['trashed' => $trashed ? 1 : null, 'category' => $category])->links() }}
        </x-slot:pagination>
      @endif
    </x-entity.list>
  </div>
</x-admin-layout>