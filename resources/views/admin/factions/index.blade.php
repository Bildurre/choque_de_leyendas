<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('factions.plural') }}</h1>
  </div>
  
  <div class="page-content">
    <x-tabs>
      <x-slot:header>
        <x-tab-item 
          id="active" 
          :active="!$trashed" 
          :href="route('admin.factions.index')"
          icon="list"
          :count="$activeCount ?? null"
        >
          {{ __('admin.active_items') }}
        </x-tab-item>
        
        <x-tab-item 
          id="trashed" 
          :active="$trashed" 
          :href="route('admin.factions.index', ['trashed' => 1])"
          icon="trash"
          :count="$trashedCount ?? null"
        >
          {{ __('admin.trashed_items') }}
        </x-tab-item>
      </x-slot:header>
      
      <x-slot:content>
        <x-entity.list 
          title="{{ $trashed ? __('factions.trashed') : __('factions.plural') }}"
          :create-route="!$trashed ? route('admin.factions.create') : null"
          :create-label="__('factions.create')"
          :items="$factions"
        >
          @foreach($factions as $faction)
            @if($trashed)
              <x-entity.list-card 
                :title="$faction->name"
                :delete-route="route('admin.factions.force-delete', $faction->id)"
                :confirm-message="__('factions.confirm_force_delete')"
              >
                <x-slot:actions>
                  <form action="{{ route('admin.factions.restore', $faction->id) }}" method="POST" class="action-button-form">
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
                  <x-badge 
                    :variant="$faction->text_is_dark ? 'light' : 'dark'" 
                    style="background-color: {{ $faction->color }};"
                  >
                    {{ $faction->color }}
                  </x-badge>
                  
                  @if($faction->heroes_count > 0)
                    <x-badge variant="info">
                      {{ __('factions.heroes_count', ['count' => $faction->heroes_count]) }}
                    </x-badge>
                  @endif
                  
                  @if($faction->cards_count > 0)
                    <x-badge variant="primary">
                      {{ __('factions.cards_count', ['count' => $faction->cards_count]) }}
                    </x-badge>
                  @endif
                  
                  <x-badge variant="danger">
                    {{ __('admin.deleted_at', ['date' => $faction->deleted_at->format('d/m/Y H:i')]) }}
                  </x-badge>
                </x-slot:badges>
                
                @if($faction->icon)
                  <div class="faction-icon">
                    <img src="{{ $faction->getIconUrl() }}" alt="{{ $faction->name }}" class="faction-icon__image">
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
            @else
              <x-entity.list-card 
                :title="$faction->name"
                :edit-route="route('admin.factions.edit', $faction)"
                :delete-route="route('admin.factions.destroy', $faction)"
                :confirm-message="__('factions.confirm_delete')"
              >
                <x-slot:badges>
                  <x-badge 
                    :variant="$faction->text_is_dark ? 'light' : 'dark'" 
                    style="background-color: {{ $faction->color }};"
                  >
                    {{ $faction->color }}
                  </x-badge>
                  
                  @if($faction->heroes_count > 0)
                    <x-badge variant="info">
                      {{ __('factions.heroes_count', ['count' => $faction->heroes_count]) }}
                    </x-badge>
                  @endif
                  
                  @if($faction->cards_count > 0)
                    <x-badge variant="primary">
                      {{ __('factions.cards_count', ['count' => $faction->cards_count]) }}
                    </x-badge>
                  @endif
                </x-slot:badges>
                
                @if($faction->icon)
                  <div class="faction-icon">
                    <img src="{{ $faction->getIconUrl() }}" alt="{{ $faction->name }}" class="faction-icon__image">
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
            @endif
          @endforeach
          
          @if(method_exists($factions, 'links'))
            <x-slot:pagination>
              {{ $factions->appends(['trashed' => $trashed ? 1 : null])->links() }}
            </x-slot:pagination>
          @endif
        </x-entity.list>
      </x-slot:content>
    </x-tabs>
  </div>
</x-admin-layout>