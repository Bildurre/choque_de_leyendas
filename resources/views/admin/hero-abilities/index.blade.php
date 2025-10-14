<x-admin-layout>
  <x-admin.page-header :title="__('entities.hero_abilities.plural')">
    <x-slot:actions>
      @if(!$trashed)
        <x-button-link
          :href="route('admin.hero-abilities.create')"
          variant="primary"
          icon="plus"
        >
          {{ __('entities.hero_abilities.create') }}
        </x-button-link>
      @endif
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">
    <x-filters.card 
      :model="$heroAbilityModel" 
      :request="$request" 
      :itemsCount="$heroAbilities->count()"
      :totalCount="$totalCount"
      :filteredCount="$filteredCount"
    />

    <x-entity.list 
      :items="$heroAbilities"
      :withTabs="true"
      :trashed="$trashed"
      :activeCount="$activeCount ?? null"
      :trashedCount="$trashedCount ?? null"
      baseRoute="admin.hero-abilities.index"
    >
      @foreach($heroAbilities as $heroAbility)
        <x-entity.list-card 
          :title="$heroAbility->name"
          :edit-route="!$trashed ? route('admin.hero-abilities.edit', $heroAbility) : null"
          :delete-route="$trashed 
            ? route('admin.hero-abilities.force-delete', $heroAbility->id) 
            : route('admin.hero-abilities.destroy', $heroAbility)"
          :restore-route="$trashed ? route('admin.hero-abilities.restore', $heroAbility->id) : null"
          :confirm-message="$trashed 
            ? __('entities.hero_abilities.confirm_force_delete') 
            : __('entities.hero_abilities.confirm_delete')"
        >
          <x-slot:badges>
            @if($heroAbility->attack_type)
              <x-badge variant="{{ $heroAbility->attack_type === 'physical' ? 'warning' : 'info' }}">
                {{ __('entities.hero_abilities.attack_types.' . $heroAbility->attack_type) }}
              </x-badge>
            @endif

            @if($heroAbility->attackRange)
              <x-badge variant="primary">
                {{ $heroAbility->attackRange->name }}
              </x-badge>
            @endif

            @if($heroAbility->attackSubtype)
              <x-badge variant="primary">
                {{ $heroAbility->attackSubtype->type }}
              </x-badge>

              <x-badge variant="primary">
                {{ $heroAbility->attackSubtype->name }}
              </x-badge>
            @endif

            @if($heroAbility->area)
              <x-badge variant="primary">
                ({{ __('entities.hero_abilities.area') }})
              </x-badge>
            @endif
            
            @if($heroAbility->cost)
              <div class="badge-with-icons">
                <span class="badge-with-icons__cost">
                  <x-cost-display :cost="$heroAbility->cost" />
                </span>
              </div>
            @endif
            
            @if($trashed)
              <x-badge variant="danger">
                {{ __('admin.deleted_at', ['date' => $heroAbility->deleted_at->format('d/m/Y H:i')]) }}
              </x-badge>
            @endif
          </x-slot:badges>
          
          @if($heroAbility->description)
            <div class="ability-details">
              <div class="ability-details__content">
                <div class="ability-details__section">
                  <div class="ability-details__text">{{ strip_tags($heroAbility->description) }}</div>
                </div>
              </div>
            </div>
          @endif
        </x-entity.list-card>
      @endforeach
      
      @if(method_exists($heroAbilities, 'links'))
        <x-slot:pagination>
          {{ $heroAbilities->links() }}
        </x-slot:pagination>
      @endif
    </x-entity.list>
  </div>
</x-admin-layout>