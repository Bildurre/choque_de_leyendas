<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('equipment_types.plural') }}</h1>
  </div>
  
  <div class="page-content">
    <x-tabs>
      <x-slot:header>
        <x-tab-item 
          id="active" 
          :active="!$trashed" 
          :href="route('admin.equipment-types.index')"
          icon="list"
          :count="$activeCount ?? null"
        >
          {{ __('admin.active_items') }}
        </x-tab-item>
        
        <x-tab-item 
          id="trashed" 
          :active="$trashed" 
          :href="route('admin.equipment-types.index', ['trashed' => 1])"
          icon="trash"
          :count="$trashedCount ?? null"
        >
          {{ __('admin.trashed_items') }}
        </x-tab-item>
      </x-slot:header>
      
      <x-slot:content>
        <div class="category-filter">
          <span class="category-filter__label">{{ __('equipment_types.filter_by_category') }}:</span>
          <div class="category-filter__options">
            <a href="{{ route('admin.equipment-types.index', ['trashed' => $trashed ? 1 : null]) }}" 
              class="category-filter__option {{ !$category ? 'is-active' : '' }}">
              {{ __('equipment_types.all_categories') }}
              <span class="category-filter__count">({{ $activeCount }})</span>
            </a>
            
            @foreach($categories as $categoryKey => $categoryName)
              <a href="{{ route('admin.equipment-types.index', ['category' => $categoryKey, 'trashed' => $trashed ? 1 : null]) }}" 
                class="category-filter__option {{ $category === $categoryKey ? 'is-active' : '' }}">
                {{ $categoryName }}
                <span class="category-filter__count">({{ $categoryCounts[$categoryKey] ?? 0 }})</span>
              </a>
            @endforeach
          </div>
        </div>
        
        <x-entity.list 
          title="{{ $trashed ? __('equipment_types.trashed') : __('equipment_types.plural') }}"
          :create-route="!$trashed ? route('admin.equipment-types.create') : null"
          :create-label="__('equipment_types.create')"
          :items="$equipmentTypes"
        >
          @foreach($equipmentTypes as $equipmentType)
            @if($trashed)
              <x-entity.list-card 
                :title="$equipmentType->name"
                :delete-route="route('admin.equipment-types.force-delete', $equipmentType->id)"
                :confirm-message="__('equipment_types.confirm_force_delete')"
              >
                <x-slot:actions>
                  <form action="{{ route('admin.equipment-types.restore', $equipmentType->id) }}" method="POST" class="action-button-form">
                    @csrf
                    <button 
                      type="submit" 
                      class="action-button action-button--restore"
                      title="{{ __('admin.restore') }}"
                    >
                      <x-icon name="refresh" size="sm" class="action-button__icon" />
                    </button>
                  </form>
                </x-slot:actions>
                
                <x-slot:badges>
                  <x-badge variant="primary">
                    {{ $equipmentType->category_name }}
                  </x-badge>
                  
                  @if($equipmentType->cards_count > 0)
                    <x-badge variant="info">
                      {{ __('equipment_types.cards_count', ['count' => $equipmentType->cards_count]) }}
                    </x-badge>
                  @endif
                  
                  <x-badge variant="danger">
                    {{ __('admin.deleted_at', ['date' => $equipmentType->deleted_at->format('d/m/Y H:i')]) }}
                  </x-badge>
                </x-slot:badges>
              </x-entity.list-card>
            @else
              <x-entity.list-card 
                :title="$equipmentType->name"
                :edit-route="route('admin.equipment-types.edit', $equipmentType)"
                :delete-route="route('admin.equipment-types.destroy', $equipmentType)"
                :confirm-message="__('equipment_types.confirm_delete')"
              >
                <x-slot:badges>
                  <x-badge variant="primary">
                    {{ $equipmentType->category_name }}
                  </x-badge>
                  
                  @if($equipmentType->cards_count > 0)
                    <x-badge variant="info">
                      {{ __('equipment_types.cards_count', ['count' => $equipmentType->cards_count]) }}
                    </x-badge>
                  @endif
                </x-slot:badges>
              </x-entity.list-card>
            @endif
          @endforeach
          
          @if(method_exists($equipmentTypes, 'links'))
            <x-slot:pagination>
              {{ $equipmentTypes->appends(['trashed' => $trashed ? 1 : null, 'category' => $category])->links() }}
            </x-slot:pagination>
          @endif
        </x-entity.list>
      </x-slot:content>
    </x-tabs>
  </div>
</x-admin-layout>