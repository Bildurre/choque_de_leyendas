<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('attack_subtypes.plural') }}</h1>
  </div>
  
  <div class="page-content">
    <x-tabs>
      <x-slot:header>
        <x-tab-item 
          id="active" 
          :active="!$trashed" 
          :href="route('admin.attack-subtypes.index')"
          icon="list"
          :count="$activeCount ?? null"
        >
          {{ __('admin.active_items') }}
        </x-tab-item>
        
        <x-tab-item 
          id="trashed" 
          :active="$trashed" 
          :href="route('admin.attack-subtypes.index', ['trashed' => 1])"
          icon="trash"
          :count="$trashedCount ?? null"
        >
          {{ __('admin.trashed_items') }}
        </x-tab-item>
      </x-slot:header>
      
      <x-slot:content>
        <div class="type-filter">
          <span class="type-filter__label">{{ __('attack_subtypes.filter_by_type') }}:</span>
          <div class="type-filter__options">
            <a href="{{ route('admin.attack-subtypes.index', ['trashed' => $trashed ? 1 : null]) }}" 
              class="type-filter__option {{ !$type ? 'is-active' : '' }}">
              {{ __('attack_subtypes.all_types') }}
              <span class="type-filter__count">({{ $activeCount }})</span>
            </a>
            
            @foreach($types as $typeKey => $typeName)
              <a href="{{ route('admin.attack-subtypes.index', ['type' => $typeKey, 'trashed' => $trashed ? 1 : null]) }}" 
                class="type-filter__option {{ $type === $typeKey ? 'is-active' : '' }}">
                {{ $typeName }}
                <span class="type-filter__count">({{ $typeCounts[$typeKey] ?? 0 }})</span>
              </a>
            @endforeach
          </div>
        </div>
        
        <x-entity.list 
          title="{{ $trashed ? __('attack_subtypes.trashed') : __('attack_subtypes.plural') }}"
          :create-route="!$trashed ? route('admin.attack-subtypes.create') : null"
          :create-label="__('attack_subtypes.create')"
          :items="$attackSubtypes"
        >
          @foreach($attackSubtypes as $attackSubtype)
            @if($trashed)
              <x-entity.list-card 
                :title="$attackSubtype->name"
                :delete-route="route('admin.attack-subtypes.force-delete', $attackSubtype->id)"
                :confirm-message="__('attack_subtypes.confirm_force_delete')"
              >
                <x-slot:actions>
                  <form action="{{ route('admin.attack-subtypes.restore', $attackSubtype->id) }}" method="POST" class="action-button-form">
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
                    {{ $attackSubtype->type_name }}
                  </x-badge>
                  
                  @if($attackSubtype->hero_abilities_count > 0)
                    <x-badge variant="info">
                      {{ __('attack_subtypes.hero_abilities_count', ['count' => $attackSubtype->hero_abilities_count]) }}
                    </x-badge>
                  @endif
                  
                  @if($attackSubtype->cards_count > 0)
                    <x-badge variant="success">
                      {{ __('attack_subtypes.cards_count', ['count' => $attackSubtype->cards_count]) }}
                    </x-badge>
                  @endif
                  
                  <x-badge variant="danger">
                    {{ __('admin.deleted_at', ['date' => $attackSubtype->deleted_at->format('d/m/Y H:i')]) }}
                  </x-badge>
                </x-slot:badges>
              </x-entity.list-card>
            @else
              <x-entity.list-card 
                :title="$attackSubtype->name"
                :edit-route="route('admin.attack-subtypes.edit', $attackSubtype)"
                :delete-route="route('admin.attack-subtypes.destroy', $attackSubtype)"
                :confirm-message="__('attack_subtypes.confirm_delete')"
              >
                <x-slot:badges>
                  <x-badge variant="primary">
                    {{ $attackSubtype->type_name }}
                  </x-badge>
                  
                  @if($attackSubtype->hero_abilities_count > 0)
                    <x-badge variant="info">
                      {{ __('attack_subtypes.hero_abilities_count', ['count' => $attackSubtype->hero_abilities_count]) }}
                    </x-badge>
                  @endif
                  
                  @if($attackSubtype->cards_count > 0)
                    <x-badge variant="success">
                      {{ __('attack_subtypes.cards_count', ['count' => $attackSubtype->cards_count]) }}
                    </x-badge>
                  @endif
                </x-slot:badges>
              </x-entity.list-card>
            @endif
          @endforeach
          
          @if(method_exists($attackSubtypes, 'links'))
            <x-slot:pagination>
              {{ $attackSubtypes->appends(['trashed' => $trashed ? 1 : null, 'type' => $type])->links() }}
            </x-slot:pagination>
          @endif
        </x-entity.list>
      </x-slot:content>
    </x-tabs>
  </div>
</x-admin-layout>