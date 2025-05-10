<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('hero_races.plural') }}</h1>
  </div>
  
  <div class="page-content">
    <x-tabs>
      <x-slot:header>
        <x-tab-item 
          id="active" 
          :active="!$trashed" 
          :href="route('admin.hero-races.index')"
          icon="list"
          :count="$activeCount ?? null"
        >
          {{ __('admin.active_items') }}
        </x-tab-item>
        
        <x-tab-item 
          id="trashed" 
          :active="$trashed" 
          :href="route('admin.hero-races.index', ['trashed' => 1])"
          icon="trash"
          :count="$trashedCount ?? null"
        >
          {{ __('admin.trashed_items') }}
        </x-tab-item>
      </x-slot:header>
      
      <x-slot:content>
        <x-entity.list 
          title="{{ $trashed ? __('hero_races.trashed') : __('hero_races.plural') }}"
          :create-route="!$trashed ? route('admin.hero-races.create') : null"
          :create-label="__('hero_races.create')"
          :items="$heroRaces"
        >
          @foreach($heroRaces as $heroRace)
            @if($trashed)
              <x-entity.list-card 
                :title="$heroRace->name"
                :delete-route="route('admin.hero-races.force-delete', $heroRace->id)"
                :confirm-message="__('hero_races.confirm_force_delete')"
              >
                <x-slot:actions>
                  <form action="{{ route('admin.hero-races.restore', $heroRace->id) }}" method="POST" class="action-button-form">
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
                  @if($heroRace->heroes_count > 0)
                    <x-badge variant="info">
                      {{ __('hero_races.heroes_count', ['count' => $heroRace->heroes_count]) }}
                    </x-badge>
                  @endif
                  
                  <x-badge variant="danger">
                    {{ __('admin.deleted_at', ['date' => $heroRace->deleted_at->format('d/m/Y H:i')]) }}
                  </x-badge>
                </x-slot:badges>
              </x-entity.list-card>
            @else
              <x-entity.list-card 
                :title="$heroRace->name"
                :edit-route="route('admin.hero-races.edit', $heroRace)"
                :delete-route="route('admin.hero-races.destroy', $heroRace)"
                :confirm-message="__('hero_races.confirm_delete')"
              >
                <x-slot:badges>
                  @if($heroRace->heroes_count > 0)
                    <x-badge variant="info">
                      {{ __('hero_races.heroes_count', ['count' => $heroRace->heroes_count]) }}
                    </x-badge>
                  @endif
                </x-slot:badges>
              </x-entity.list-card>
            @endif
          @endforeach
          
          @if(method_exists($heroRaces, 'links'))
            <x-slot:pagination>
              {{ $heroRaces->appends(['trashed' => $trashed ? 1 : null])->links() }}
            </x-slot:pagination>
          @endif
        </x-entity.list>
      </x-slot:content>
    </x-tabs>
  </div>
</x-admin-layout>