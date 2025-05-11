<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('heroes.plural') }}</h1>
  </div>
  
  <div class="page-content">
    <x-tabs>
      <x-slot:header>
        <x-tab-item 
          id="active" 
          :active="!$trashed" 
          :href="route('admin.heroes.index')"
          icon="list"
          :count="$activeCount ?? null"
        >
          {{ __('admin.active_items') }}
        </x-tab-item>
        
        <x-tab-item 
          id="trashed" 
          :active="$trashed" 
          :href="route('admin.heroes.index', ['trashed' => 1])"
          icon="trash"
          :count="$trashedCount ?? null"
        >
          {{ __('admin.trashed_items') }}
        </x-tab-item>
      </x-slot:header>
      
      <x-slot:content>
        <!-- Filtros -->
        <div class="hero-filters">
          <form action="{{ route('admin.heroes.index', $trashed ? ['trashed' => 1] : []) }}" method="GET" class="filters-form">
            @if($trashed)
              <input type="hidden" name="trashed" value="1">
            @endif
            
            <div class="filters-form__row">
              <div class="filters-form__field">
                <label for="search" class="filters-form__label">{{ __('heroes.search') }}</label>
                <input 
                  type="text" 
                  name="search" 
                  id="search" 
                  value="{{ $filters['search'] ?? '' }}" 
                  class="form-input"
                  placeholder="{{ __('heroes.search_placeholder') }}"
                >
              </div>
              
              <div class="filters-form__field">
                <label for="faction_id" class="filters-form__label">{{ __('factions.singular') }}</label>
                <select name="faction_id" id="faction_id" class="form-select">
                  <option value="">{{ __('heroes.all_factions') }}</option>
                  <option value="no_faction" {{ isset($filters['faction_id']) && $filters['faction_id'] === 'no_faction' ? 'selected' : '' }}>
                    {{ __('heroes.no_faction') }} ({{ $factionCounts['no_faction'] ?? 0 }})
                  </option>
                  @foreach($factions as $faction)
                    <option 
                      value="{{ $faction->id }}" 
                      {{ isset($filters['faction_id']) && (string)$filters['faction_id'] === (string)$faction->id ? 'selected' : '' }}
                    >
                      {{ $faction->name }} ({{ $factionCounts[$faction->id] ?? 0 }})
                    </option>
                  @endforeach
                </select>
              </div>
              
              <div class="filters-form__field">
                <label for="hero_race_id" class="filters-form__label">{{ __('hero_races.singular') }}</label>
                <select name="hero_race_id" id="hero_race_id" class="form-select">
                  <option value="">{{ __('heroes.all_races') }}</option>
                  @foreach($heroRaces as $race)
                    <option 
                      value="{{ $race->id }}" 
                      {{ isset($filters['hero_race_id']) && (string)$filters['hero_race_id'] === (string)$race->id ? 'selected' : '' }}
                    >
                      {{ $race->name }} ({{ $raceCounts[$race->id] ?? 0 }})
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
            
            <div class="filters-form__row">
              <div class="filters-form__field">
                <label for="hero_class_id" class="filters-form__label">{{ __('hero_classes.singular') }}</label>
                <select name="hero_class_id" id="hero_class_id" class="form-select">
                  <option value="">{{ __('heroes.all_classes') }}</option>
                  @foreach($heroClasses as $class)
                    <option 
                      value="{{ $class->id }}" 
                      {{ isset($filters['hero_class_id']) && (string)$filters['hero_class_id'] === (string)$class->id ? 'selected' : '' }}
                    >
                      {{ $class->name }} ({{ $classCounts[$class->id] ?? 0 }})
                    </option>
                  @endforeach
                </select>
              </div>
              
              <div class="filters-form__field">
                <label for="gender" class="filters-form__label">{{ __('heroes.gender') }}</label>
                <select name="gender" id="gender" class="form-select">
                  <option value="">{{ __('heroes.all_genders') }}</option>
                  <option value="male" {{ isset($filters['gender']) && $filters['gender'] === 'male' ? 'selected' : '' }}>
                    {{ __('heroes.genders.male') }}
                  </option>
                  <option value="female" {{ isset($filters['gender']) && $filters['gender'] === 'female' ? 'selected' : '' }}>
                    {{ __('heroes.genders.female') }}
                  </option>
                </select>
              </div>
              
              <div class="filters-form__field">
                <label for="hero_ability_id" class="filters-form__label">{{ __('hero_abilities.singular') }}</label>
                <select name="hero_ability_id" id="hero_ability_id" class="form-select">
                  <option value="">{{ __('heroes.all_abilities') }}</option>
                  @foreach($heroAbilities as $ability)
                    <option 
                      value="{{ $ability->id }}" 
                      {{ isset($filters['hero_ability_id']) && (string)$filters['hero_ability_id'] === (string)$ability->id ? 'selected' : '' }}
                    >
                      {{ $ability->name }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
            
            <div class="filters-form__actions">
              <button type="submit" class="btn btn--primary">
                {{ __('heroes.apply_filters') }}
              </button>
              
              <a href="{{ route('admin.heroes.index', $trashed ? ['trashed' => 1] : []) }}" class="btn btn--secondary">
                {{ __('heroes.clear_filters') }}
              </a>
            </div>
          </form>
        </div>
        
        <x-entity.list 
          title="{{ $trashed ? __('heroes.trashed') : __('heroes.plural') }}"
          :create-route="!$trashed ? route('admin.heroes.create') : null"
          :create-label="__('heroes.create')"
          :items="$heroes"
        >
          @foreach($heroes as $hero)
            @if($trashed)
              <x-entity.list-card 
                :title="$hero->name"
                :delete-route="route('admin.heroes.force-delete', $hero->id)"
                :confirm-message="__('heroes.confirm_force_delete')"
              >
                <x-slot:actions>
                  <form action="{{ route('admin.heroes.restore', $hero->id) }}" method="POST" class="action-button-form">
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
                  @if($hero->faction)
                    <x-badge 
                      :variant="$hero->faction->text_is_dark ? 'light' : 'dark'" 
                      style="background-color: {{ $hero->faction->color }};"
                    >
                      {{ $hero->faction->name }}
                    </x-badge>
                  @endif
                  
                  @if($hero->heroRace)
                    <x-badge variant="info">
                      {{ $hero->heroRace->name }}
                    </x-badge>
                  @endif
                  
                  @if($hero->heroClass)
                    <x-badge variant="primary">
                      {{ $hero->heroClass->name }}
                    </x-badge>
                  @endif
                  
                  <x-badge variant="{{ $hero->gender === 'male' ? 'success' : 'warning' }}">
                    {{ __('heroes.genders.' . $hero->gender) }}
                  </x-badge>
                  
                  <x-badge variant="danger">
                    {{ __('admin.deleted_at', ['date' => $hero->deleted_at->format('d/m/Y H:i')]) }}
                  </x-badge>
                </x-slot:badges>
                
                <div class="hero-details">
                  @if($hero->image)
                    <div class="hero-details__image">
                      <img src="{{ $hero->getImageUrl() }}" alt="{{ $hero->name }}" class="hero-image">
                    </div>
                  @endif
                  
                  <div class="hero-details__content">
                    <div class="hero-details__attributes">
                      <div class="hero-attribute">
                        <span class="hero-attribute__label">{{ __('heroes.attributes.agility') }}:</span>
                        <span class="hero-attribute__value">{{ $hero->agility }}</span>
                      </div>
                      <div class="hero-attribute">
                        <span class="hero-attribute__label">{{ __('heroes.attributes.mental') }}:</span>
                        <span class="hero-attribute__value">{{ $hero->mental }}</span>
                      </div>
                      <div class="hero-attribute">
                        <span class="hero-attribute__label">{{ __('heroes.attributes.will') }}:</span>
                        <span class="hero-attribute__value">{{ $hero->will }}</span>
                      </div>
                      <div class="hero-attribute">
                        <span class="hero-attribute__label">{{ __('heroes.attributes.strength') }}:</span>
                        <span class="hero-attribute__value">{{ $hero->strength }}</span>
                      </div>
                      <div class="hero-attribute">
                        <span class="hero-attribute__label">{{ __('heroes.attributes.armor') }}:</span>
                        <span class="hero-attribute__value">{{ $hero->armor }}</span>
                      </div>
                      <div class="hero-attribute hero-attribute--total">
                        <span class="hero-attribute__label">{{ __('heroes.attributes.health') }}:</span>
                        <span class="hero-attribute__value">{{ $hero->health }}</span>
                      </div>
                    </div>
                    
                    @if($hero->heroAbilities->count() > 0)
                      <div class="hero-details__abilities">
                        <h4 class="hero-details__section-title">{{ __('hero_abilities.plural') }}:</h4>
                        <ul class="hero-abilities-list">
                          @foreach($hero->heroAbilities as $ability)
                            <li class="hero-abilities-list__item">
                              <span class="hero-abilities-list__name">{{ $ability->name }}</span>
                              @if($ability->cost)
                                <span class="hero-abilities-list__cost">{!! $ability->icon_html !!}</span>
                              @endif
                            </li>
                          @endforeach
                        </ul>
                      </div>
                    @endif
                    
                    @if($hero->passive_name)
                      <div class="hero-details__passive">
                        <h4 class="hero-details__section-title">{{ __('heroes.passive') }}:</h4>
                        <div class="hero-details__passive-name">{{ $hero->passive_name }}</div>
                        @if($hero->passive_description)
                          <div class="hero-details__passive-description">
                            {{ strip_tags($hero->passive_description) }}
                          </div>
                        @endif
                      </div>
                    @endif
                  </div>
                </div>
              </x-entity.list-card>
            @else
              <x-entity.list-card 
                :title="$hero->name"
                :edit-route="route('admin.heroes.edit', $hero)"
                :delete-route="route('admin.heroes.destroy', $hero)"
                :confirm-message="__('heroes.confirm_delete')"
              >
                <x-slot:badges>
                  @if($hero->faction)
                    <x-badge 
                      :variant="$hero->faction->text_is_dark ? 'light' : 'dark'" 
                      style="background-color: {{ $hero->faction->color }};"
                    >
                      {{ $hero->faction->name }}
                    </x-badge>
                  @endif
                  
                  @if($hero->heroRace)
                    <x-badge variant="info">
                      {{ $hero->heroRace->name }}
                    </x-badge>
                  @endif
                  
                  @if($hero->heroClass)
                    <x-badge variant="primary">
                      {{ $hero->heroClass->name }}
                    </x-badge>
                  @endif
                  
                  <x-badge variant="{{ $hero->gender === 'male' ? 'success' : 'warning' }}">
                    {{ __('heroes.genders.' . $hero->gender) }}
                  </x-badge>
                </x-slot:badges>
                
                <div class="hero-details">
                  @if($hero->image)
                    <div class="hero-details__image">
                      <img src="{{ $hero->getImageUrl() }}" alt="{{ $hero->name }}" class="hero-image">
                    </div>
                  @endif
                  
                  <div class="hero-details__content">
                    <div class="hero-details__attributes">
                      <div class="hero-attribute">
                        <span class="hero-attribute__label">{{ __('heroes.attributes.agility') }}:</span>
                        <span class="hero-attribute__value">{{ $hero->agility }}</span>
                      </div>
                      <div class="hero-attribute">
                        <span class="hero-attribute__label">{{ __('heroes.attributes.mental') }}:</span>
                        <span class="hero-attribute__value">{{ $hero->mental }}</span>
                      </div>
                      <div class="hero-attribute">
                        <span class="hero-attribute__label">{{ __('heroes.attributes.will') }}:</span>
                        <span class="hero-attribute__value">{{ $hero->will }}</span>
                      </div>
                      <div class="hero-attribute">
                        <span class="hero-attribute__label">{{ __('heroes.attributes.strength') }}:</span>
                        <span class="hero-attribute__value">{{ $hero->strength }}</span>
                      </div>
                      <div class="hero-attribute">
                        <span class="hero-attribute__label">{{ __('heroes.attributes.armor') }}:</span>
                        <span class="hero-attribute__value">{{ $hero->armor }}</span>
                      </div>
                      <div class="hero-attribute hero-attribute--total">
                        <span class="hero-attribute__label">{{ __('heroes.attributes.health') }}:</span>
                        <span class="hero-attribute__value">{{ $hero->health }}</span>
                      </div>
                    </div>
                    
                    @if($hero->heroAbilities->count() > 0)
                      <div class="hero-details__abilities">
                        <h4 class="hero-details__section-title">{{ __('hero_abilities.plural') }}:</h4>
                        <ul class="hero-abilities-list">
                          @foreach($hero->heroAbilities as $ability)
                            <li class="hero-abilities-list__item">
                              <span class="hero-abilities-list__name">{{ $ability->name }}</span>
                              @if($ability->cost)
                                <span class="hero-abilities-list__cost">{!! $ability->icon_html !!}</span>
                              @endif
                            </li>
                          @endforeach
                        </ul>
                      </div>
                    @endif
                    
                    @if($hero->passive_name)
                      <div class="hero-details__passive">
                        <h4 class="hero-details__section-title">{{ __('heroes.passive') }}:</h4>
                        <div class="hero-details__passive-name">{{ $hero->passive_name }}</div>
                        @if($hero->passive_description)
                          <div class="hero-details__passive-description">
                            {{ strip_tags($hero->passive_description) }}
                          </div>
                        @endif
                      </div>
                    @endif
                  </div>
                </div>
              </x-entity.list-card>
            @endif
          @endforeach
          
          @if(method_exists($heroes, 'links'))
            <x-slot:pagination>
              {{ $heroes->links() }}
            </x-slot:pagination>
          @endif
        </x-entity.list>
      </x-slot:content>
    </x-tabs>
  </div>
</x-admin-layout>