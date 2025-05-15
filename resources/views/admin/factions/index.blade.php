<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('entities.factions.plural') }}</h1>
  </div>
  
  <div class="page-content">

    <x-filters.card 
      :model="$factionModel" 
      :request="$request" 
      :itemsCount="$factions->count()"
      :totalCount="$totalCount"
      :filteredCount="$filteredCount"
    />

    <x-entity.list 
      :create-route="!$trashed ? route('admin.factions.create') : null"
      :create-label="__('entities.factions.create')"
      :items="$factions"
      :withTabs="true"
      :trashed="$trashed"
      :activeCount="$activeCount ?? null"
      :trashedCount="$trashedCount ?? null"
      baseRoute="admin.factions.index"
    >
      @foreach($factions as $faction)
        <x-entity.list-card 
          :title="$faction->name"
          :view-route="!$trashed ? route('admin.factions.show', $faction) : null"
          :edit-route="!$trashed ? route('admin.factions.edit', $faction) : null"
          :delete-route="$trashed 
            ? route('admin.factions.force-delete', $faction->id) 
            : route('admin.factions.destroy', $faction)"
          :restore-route="$trashed ? route('admin.factions.restore', $faction->id) : null"
          :confirm-message="$trashed 
            ? __('entities.factions.confirm_force_delete') 
            : __('entities.factions.confirm_delete')"
        >
          <x-slot:badges>
            <x-badge 
              :variant="$faction->text_is_dark ? 'light' : 'dark'" 
              style="background-color: {{ $faction->color }};"
            >
              {{ $faction->color }}
            </x-badge>
            
            <x-badge variant="info">
              {{ __('entities.factions.heroes_count', ['count' => $faction->heroes_count]) }}
            </x-badge>
            
            <x-badge variant="primary">
              {{ __('entities.factions.cards_count', ['count' => $faction->cards_count]) }}
            </x-badge>
            
            @if($trashed)
              <x-badge variant="danger">
                {{ __('admin.deleted_at', ['date' => $faction->deleted_at->format('d/m/Y H:i')]) }}
              </x-badge>
            @endif
          </x-slot:badges>
          
          @if($faction->icon)
            <div class="faction-icon">
              <img src="{{ $faction->getImageUrl() }}" alt="{{ $faction->name }}" class="faction-icon__image">
            </div>
          @endif
          
          @if($faction->lore_text)
            <div class="faction-lore">
              <div class="faction-lore__excerpt">
                {{ Str::limit(strip_tags($faction->lore_text), 150) }}
              </div>
            </div>
          @endif
        </x-entity.list-card>
      @endforeach
      
      @if(method_exists($factions, 'links'))
        <x-slot:pagination>
          {{ $factions->appends(['trashed' => $trashed ? 1 : null])->links() }}
        </x-slot:pagination>
      @endif
    </x-entity.list>
  </div>
</x-admin-layout>