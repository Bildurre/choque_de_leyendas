<!-- resources/views/admin/hero-races/index.blade.php -->
<x-admin-layout>
  <x-admin.page-header :title="__('entities.hero_races.plural')">
    <x-slot:actions>
      @if(!$trashed)
        <x-button-link
          :href="route('admin.hero-races.create')"
          variant="primary"
          icon="plus"
        >
          {{ __('entities.hero_races.create') }}
        </x-button-link>
      @endif
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">

    <x-filters.card 
      :model="$heroRaceModel" 
      :request="$request" 
      :itemsCount="$heroRaces->count()"
      :totalCount="$totalCount"
      :filteredCount="$filteredCount"
    />

    <x-entity.list 
      :items="$heroRaces"
      :withTabs="true"
      :trashed="$trashed"
      :activeCount="$activeCount ?? null"
      :trashedCount="$trashedCount ?? null"
      baseRoute="admin.hero-races.index"
    >
      @foreach($heroRaces as $heroRace)
        <x-entity.list-card 
          :title="$heroRace->name"
          :edit-route="!$trashed ? route('admin.hero-races.edit', $heroRace) : null"
          :delete-route="$trashed 
            ? route('admin.hero-races.force-delete', $heroRace->id) 
            : route('admin.hero-races.destroy', $heroRace)"
          :restore-route="$trashed ? route('admin.hero-races.restore', $heroRace->id) : null"
          :confirm-message="$trashed 
            ? __('entities.hero_races.confirm_force_delete') 
            : __('entities.hero_races.confirm_delete')"
        >
          <x-slot:badges>
            <x-badge variant="info">
              {{ __('entities.hero_races.heroes_count', ['count' => $heroRace->heroes_count]) }}
            </x-badge>
            
            @if($trashed)
              <x-badge variant="danger">
                {{ __('admin.deleted_at', ['date' => $heroRace->deleted_at->format('d/m/Y H:i')]) }}
              </x-badge>
            @endif
          </x-slot:badges>
        </x-entity.list-card>
      @endforeach
      
      @if(method_exists($heroRaces, 'links'))
        <x-slot:pagination>
          {{ $heroRaces->appends(['trashed' => $trashed ? 1 : null])->links() }}
        </x-slot:pagination>
      @endif
    </x-entity.list>
  </div>
</x-admin-layout>