<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('attack_ranges.plural') }}</h1>
  </div>
  
  <div class="page-content">
    <x-tabs>
      <x-slot:header>
        <x-tab-item 
          id="active" 
          :active="!$trashed" 
          :href="route('admin.attack-ranges.index')"
          icon="list"
          :count="$activeCount ?? null"
        >
          {{ __('admin.active_items') }}
        </x-tab-item>
        
        <x-tab-item 
          id="trashed" 
          :active="$trashed" 
          :href="route('admin.attack-ranges.index', ['trashed' => 1])"
          icon="trash"
          :count="$trashedCount ?? null"
        >
          {{ __('admin.trashed_items') }}
        </x-tab-item>
      </x-slot:header>
      
      <x-slot:content>
        <x-entity.list 
          title="{{ $trashed ? __('attack_ranges.trashed') : __('attack_ranges.plural') }}"
          :create-route="!$trashed ? route('admin.attack-ranges.create') : null"
          :create-label="__('attack_ranges.create')"
          :items="$attackRanges"
        >
          @foreach($attackRanges as $attackRange)
            @if($trashed)
              <x-entity.list-card 
                :title="$attackRange->name"
                :delete-route="route('admin.attack-ranges.force-delete', $attackRange->id)"
                :confirm-message="__('attack_ranges.confirm_force_delete')"
              >
                <x-slot:actions>
                  <form action="{{ route('admin.attack-ranges.restore', $attackRange->id) }}" method="POST" class="action-button-form">
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
                  @if($attackRange->hero_abilities_count > 0)
                    <x-badge variant="info">
                      {{ __('attack_ranges.hero_abilities_count', ['count' => $attackRange->hero_abilities_count]) }}
                    </x-badge>
                  @endif
                  
                  @if($attackRange->cards_count > 0)
                    <x-badge variant="primary">
                      {{ __('attack_ranges.cards_count', ['count' => $attackRange->cards_count]) }}
                    </x-badge>
                  @endif
                  
                  <x-badge variant="danger">
                    {{ __('admin.deleted_at', ['date' => $attackRange->deleted_at->format('d/m/Y H:i')]) }}
                  </x-badge>
                </x-slot:badges>
                
                @if($attackRange->icon)
                  <div class="attack-range-icon">
                    <img src="{{ $attackRange->getIconUrl() }}" alt="{{ $attackRange->name }}" class="attack-range-icon__image">
                  </div>
                @endif
              </x-entity.list-card>
            @else
              <x-entity.list-card 
                :title="$attackRange->name"
                :edit-route="route('admin.attack-ranges.edit', $attackRange)"
                :delete-route="route('admin.attack-ranges.destroy', $attackRange)"
                :confirm-message="__('attack_ranges.confirm_delete')"
              >
                <x-slot:badges>
                  @if($attackRange->hero_abilities_count > 0)
                    <x-badge variant="info">
                      {{ __('attack_ranges.hero_abilities_count', ['count' => $attackRange->hero_abilities_count]) }}
                    </x-badge>
                  @endif
                  
                  @if($attackRange->cards_count > 0)
                    <x-badge variant="primary">
                      {{ __('attack_ranges.cards_count', ['count' => $attackRange->cards_count]) }}
                    </x-badge>
                  @endif
                </x-slot:badges>
                
                @if($attackRange->icon)
                  <div class="attack-range-icon">
                    <img src="{{ $attackRange->getIconUrl() }}" alt="{{ $attackRange->name }}" class="attack-range-icon__image">
                  </div>
                @endif
              </x-entity.list-card>
            @endif
          @endforeach
          
          @if(method_exists($attackRanges, 'links'))
            <x-slot:pagination>
              {{ $attackRanges->appends(['trashed' => $trashed ? 1 : null])->links() }}
            </x-slot:pagination>
          @endif
        </x-entity.list>
      </x-slot:content>
    </x-tabs>
  </div>
</x-admin-layout>