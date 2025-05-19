<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('entities.heroes.plural') }}</h1>
  </div>
  
  <div class="page-content">

    <x-filters.card 
      :model="$heroModel" 
      :request="$request" 
      :itemsCount="$heroes->count()"
      :totalCount="$totalCount"
      :filteredCount="$filteredCount"
    />

    <x-entity.list 
      :create-route="!$trashed ? route('admin.heroes.create') : null"
      :create-label="__('entities.heroes.create')"
      :items="$heroes"
      :withTabs="true"
      :trashed="$trashed"
      :activeCount="$activeCount ?? null"
      :trashedCount="$trashedCount ?? null"
      baseRoute="admin.heroes.index"
    >
      @foreach($heroes as $hero)
        <x-entity.list-card 
          :title="$hero->name"
          :view-route="!$trashed ? route('admin.heroes.show', $hero) : null"
          :edit-route="!$trashed ? route('admin.heroes.edit', $hero) : null"
          :delete-route="$trashed 
            ? route('admin.heroes.force-delete', $hero->id) 
            : route('admin.heroes.destroy', $hero)"
          :restore-route="$trashed ? route('admin.heroes.restore', $hero->id) : null"
          :toggle-published-route="!$trashed ? route('admin.heroes.toggle-published', $hero) : null"
          :is-published="$hero->isPublished()"
          :confirm-message="$trashed 
            ? __('entities.heroes.confirm_force_delete') 
            : __('entities.heroes.confirm_delete')"
        >
          <x-slot:badges>
            <x-badge 
              :variant="$hero->faction->text_is_dark ? 'light' : 'dark'" 
              style="background-color: {{ $hero->faction->color }};"
            >
              {{ $hero->faction->name }}
            </x-badge>
            
            <x-badge variant="info">
              {{ $hero->heroRace->name }}
            </x-badge>
            
            <x-badge variant="primary">
              {{ $hero->heroClass->name }}
            </x-badge>
            
            <x-badge variant="{{ $hero->gender === 'male' ? 'success' : 'warning' }}">
              {{ __('entities.heroes.genders.' . $hero->gender) }}
            </x-badge>
            
            @if($hero->isPublished())
              <x-badge variant="success">
                {{ __('admin.published') }}
              </x-badge>
            @else
              <x-badge variant="warning">
                {{ __('admin.draft') }}
              </x-badge>
            @endif
            
            @if($trashed)
              <x-badge variant="danger">
                {{ __('admin.deleted_at', ['date' => $hero->deleted_at->format('d/m/Y H:i')]) }}
              </x-badge>
            @endif
          </x-slot:badges>
          
          <div class="hero-details">            
            {{-- <div class="hero-details__content">
              <div class="hero-details__attributes">
                <div class="hero-attribute">
                  <span class="hero-attribute__label">{{ __('entities.heroes.attributes.agility') }}:</span>
                  <span class="hero-attribute__value">{{ $hero->agility }}</span>
                </div>
                <div class="hero-attribute">
                  <span class="hero-attribute__label">{{ __('entities.heroes.attributes.mental') }}:</span>
                  <span class="hero-attribute__value">{{ $hero->mental }}</span>
                </div>
                <div class="hero-attribute">
                  <span class="hero-attribute__label">{{ __('entities.heroes.attributes.will') }}:</span>
                  <span class="hero-attribute__value">{{ $hero->will }}</span>
                </div>
                <div class="hero-attribute">
                  <span class="hero-attribute__label">{{ __('entities.heroes.attributes.strength') }}:</span>
                  <span class="hero-attribute__value">{{ $hero->strength }}</span>
                </div>
                <div class="hero-attribute">
                  <span class="hero-attribute__label">{{ __('entities.heroes.attributes.armor') }}:</span>
                  <span class="hero-attribute__value">{{ $hero->armor }}</span>
                </div>
                <div class="hero-attribute hero-attribute--total">
                  <span class="hero-attribute__label">{{ __('entities.heroes.attributes.health') }}:</span>
                  <span class="hero-attribute__value">{{ $hero->health }}</span>
                </div>
              </div>
              
              @if($hero->heroAbilities->count() > 0)
                <div class="hero-details__abilities">
                  <h4 class="hero-details__section-title">{{ __('entities.hero_abilities.plural') }}:</h4>
                  <ul class="hero-abilities-list">
                    @foreach($hero->heroAbilities as $ability)
                      <li class="hero-abilities-list__item">
                        <span class="hero-abilities-list__name">{{ $ability->name }}</span>
                        @if($ability->cost)
                          <span class="hero-abilities-list__cost">
                            <x-cost-display :cost="$ability->cost" size="sm" />
                          </span>
                        @endif
                      </li>
                    @endforeach
                  </ul>
                </div>
              @endif
              
              @if($hero->passive_name)
                <div class="hero-details__passive">
                  <h4 class="hero-details__section-title">{{ __('entities.heroes.passive') }}:</h4>
                  <div class="hero-details__passive-name">{{ $hero->passive_name }}</div>
                  @if($hero->passive_description)
                    <div class="hero-details__passive-description">
                      {{ strip_tags($hero->passive_description) }}
                    </div>
                  @endif
                </div>
              @endif
            </div> --}}
            <x-previews.hero :hero="$hero" />
          </div>
        </x-entity.list-card>
      @endforeach
      
      @if(method_exists($heroes, 'links'))
        <x-slot:pagination>
          {{ $heroes->appends(['trashed' => $trashed ? 1 : null])->links() }}
        </x-slot:pagination>
      @endif
    </x-entity.list>
  </div>
</x-admin-layout>