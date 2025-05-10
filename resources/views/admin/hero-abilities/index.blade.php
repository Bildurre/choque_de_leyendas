<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('hero_abilities.plural') }}</h1>
  </div>
  
  <div class="page-content">
    <x-tabs>
      <x-slot:header>
        <x-tab-item 
          id="active" 
          :active="!$trashed" 
          :href="route('admin.hero-abilities.index')"
          icon="list"
          :count="$activeCount ?? null"
        >
          {{ __('admin.active_items') }}
        </x-tab-item>
        
        <x-tab-item 
          id="trashed" 
          :active="$trashed" 
          :href="route('admin.hero-abilities.index', ['trashed' => 1])"
          icon="trash"
          :count="$trashedCount ?? null"
        >
          {{ __('admin.trashed_items') }}
        </x-tab-item>
      </x-slot:header>
      
      <x-slot:content>
        <!-- Filtros -->
        <div class="ability-filters">
          <form action="{{ route('admin.hero-abilities.index', $trashed ? ['trashed' => 1] : []) }}" method="GET" class="filters-form">
            @if($trashed)
              <input type="hidden" name="trashed" value="1">
            @endif
            
            <div class="filters-form__row">
              <div class="filters-form__field">
                <label for="search" class="filters-form__label">{{ __('hero_abilities.search') }}</label>
                <input 
                  type="text" 
                  name="search" 
                  id="search" 
                  value="{{ $filters['search'] ?? '' }}" 
                  class="form-input"
                  placeholder="{{ __('hero_abilities.search_placeholder') }}"
                >
              </div>
              
              <div class="filters-form__field">
                <label for="attack_subtype_id" class="filters-form__label">{{ __('attack_subtypes.singular') }}</label>
                <select name="attack_subtype_id" id="attack_subtype_id" class="form-select">
                  <option value="">{{ __('hero_abilities.all_attack_subtypes') }}</option>
                  <option value="no_attack" {{ isset($filters['attack_subtype_id']) && $filters['attack_subtype_id'] === 'no_attack' ? 'selected' : '' }}>
                    {{ __('hero_abilities.no_attack') }} ({{ $attackSubtypeCounts['no_attack'] ?? 0 }})
                  </option>
                  <optgroup label="{{ __('attack_subtypes.types.physical') }}">
                    @foreach($attackSubtypes->where('type', 'physical') as $attackSubtype)
                      <option 
                        value="{{ $attackSubtype->id }}" 
                        {{ isset($filters['attack_subtype_id']) && (string)$filters['attack_subtype_id'] === (string)$attackSubtype->id ? 'selected' : '' }}
                      >
                        {{ $attackSubtype->name }} ({{ $attackSubtypeCounts[$attackSubtype->id] ?? 0 }})
                      </option>
                    @endforeach
                  </optgroup>
                  <optgroup label="{{ __('attack_subtypes.types.magical') }}">
                    @foreach($attackSubtypes->where('type', 'magical') as $attackSubtype)
                      <option 
                        value="{{ $attackSubtype->id }}" 
                        {{ isset($filters['attack_subtype_id']) && (string)$filters['attack_subtype_id'] === (string)$attackSubtype->id ? 'selected' : '' }}
                      >
                        {{ $attackSubtype->name }} ({{ $attackSubtypeCounts[$attackSubtype->id] ?? 0 }})
                      </option>
                    @endforeach
                  </optgroup>
                </select>
              </div>
              
              <div class="filters-form__field">
                <label for="cost" class="filters-form__label">{{ __('hero_abilities.cost') }}</label>
                <select name="cost" id="cost" class="form-select">
                  <option value="">{{ __('hero_abilities.all_costs') }}</option>
                  <option value="R" {{ isset($filters['cost']) && $filters['cost'] === 'R' ? 'selected' : '' }}>
                    {{ __('game.cost.contains_red') }}
                  </option>
                  <option value="G" {{ isset($filters['cost']) && $filters['cost'] === 'G' ? 'selected' : '' }}>
                    {{ __('game.cost.contains_green') }}
                  </option>
                  <option value="B" {{ isset($filters['cost']) && $filters['cost'] === 'B' ? 'selected' : '' }}>
                    {{ __('game.cost.contains_blue') }}
                  </option>
                </select>
              </div>
              
              <div class="filters-form__field filters-form__field--checkbox">
                <label for="area" class="filters-form__checkbox-label">
                  <input 
                    type="checkbox" 
                    name="area" 
                    id="area" 
                    value="1" 
                    {{ isset($filters['area']) && $filters['area'] ? 'checked' : '' }}
                    class="form-checkbox"
                  >
                  {{ __('hero_abilities.area_attacks_only') }}
                </label>
              </div>
            </div>
            
            <div class="filters-form__actions">
              <button type="submit" class="btn btn--primary">
                {{ __('hero_abilities.apply_filters') }}
              </button>
              
              <a href="{{ route('admin.hero-abilities.index', $trashed ? ['trashed' => 1] : []) }}" class="btn btn--secondary">
                {{ __('hero_abilities.clear_filters') }}
              </a>
            </div>
          </form>
        </div>
        
        <x-entity.list 
          title="{{ $trashed ? __('hero_abilities.trashed') : __('hero_abilities.plural') }}"
          :create-route="!$trashed ? route('admin.hero-abilities.create') : null"
          :create-label="__('hero_abilities.create')"
          :items="$heroAbilities"
        >
          @foreach($heroAbilities as $heroAbility)
            @if($trashed)
              <x-entity.list-card 
                :title="$heroAbility->name"
                :delete-route="route('admin.hero-abilities.force-delete', $heroAbility->id)"
                :confirm-message="__('hero_abilities.confirm_force_delete')"
              >
                <x-slot:actions>
                  <form action="{{ route('admin.hero-abilities.restore', $heroAbility->id) }}" method="POST" class="action-button-form">
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
                  @if($heroAbility->attackSubtype)
                    <x-badge variant="{{ $heroAbility->attackSubtype->type === 'physical' ? 'warning' : 'success' }}">
                      {{ $heroAbility->attackSubtype->name }}
                      @if($heroAbility->area)
                        ({{ __('hero_abilities.area') }})
                      @endif
                    </x-badge>
                  @endif
                  
                  @if($heroAbility->cost)
                    <div class="badge-with-icons">
                      <span class="badge-with-icons__cost">{!! $heroAbility->icon_html !!}</span>
                    </div>
                  @endif
                  
                  @if($heroAbility->heroes_count > 0)
                    <x-badge variant="info">
                      {{ __('hero_abilities.heroes_count', ['count' => $heroAbility->heroes_count]) }}
                    </x-badge>
                  @endif
                  
                  @if($heroAbility->cards_count > 0)
                    <x-badge variant="primary">
                      {{ __('hero_abilities.cards_count', ['count' => $heroAbility->cards_count]) }}
                    </x-badge>
                  @endif
                  
                  <x-badge variant="danger">
                    {{ __('admin.deleted_at', ['date' => $heroAbility->deleted_at->format('d/m/Y H:i')]) }}
                  </x-badge>
                </x-slot:badges>
                
                @if($heroAbility->description)
                  <div class="ability-details">
                    <div class="ability-details__content">
                      <div class="ability-details__section">
                        <h4 class="ability-details__label">{{ __('hero_abilities.description') }}:</h4>
                        <div class="ability-details__text">{{ strip_tags($heroAbility->description) }}</div>
                      </div>
                    </div>
                  </div>
                @endif
              </x-entity.list-card>
            @else
              <x-entity.list-card 
                :title="$heroAbility->name"
                :edit-route="route('admin.hero-abilities.edit', $heroAbility)"
                :delete-route="route('admin.hero-abilities.destroy', $heroAbility)"
                :confirm-message="__('hero_abilities.confirm_delete')"
              >
                <x-slot:badges>
                  @if($heroAbility->attackSubtype)
                    <x-badge variant="{{ $heroAbility->attackSubtype->type === 'physical' ? 'warning' : 'success' }}">
                      {{ $heroAbility->attackSubtype->name }}
                      @if($heroAbility->area)
                        ({{ __('hero_abilities.area') }})
                      @endif
                    </x-badge>
                  @endif
                  
                  @if($heroAbility->cost)
                    <div class="badge-with-icons">
                      <span class="badge-with-icons__cost">{!! $heroAbility->icon_html !!}</span>
                    </div>
                  @endif
                  
                  @if($heroAbility->heroes_count > 0)
                    <x-badge variant="info">
                      {{ __('hero_abilities.heroes_count', ['count' => $heroAbility->heroes_count]) }}
                    </x-badge>
                  @endif
                  
                  @if($heroAbility->cards_count > 0)
                    <x-badge variant="primary">
                      {{ __('hero_abilities.cards_count', ['count' => $heroAbility->cards_count]) }}
                    </x-badge>
                  @endif
                </x-slot:badges>
                
                @if($heroAbility->description)
                  <div class="ability-details">
                    <div class="ability-details__content">
                      <div class="ability-details__section">
                        <h4 class="ability-details__label">{{ __('hero_abilities.description') }}:</h4>
                        <div class="ability-details__text">{{ strip_tags($heroAbility->description) }}</div>
                      </div>
                    </div>
                  </div>
                @endif
              </x-entity.list-card>
            @endif
          @endforeach
          
          @if(method_exists($heroAbilities, 'links'))
            <x-slot:pagination>
              {{ $heroAbilities->links() }}
            </x-slot:pagination>
          @endif
        </x-entity.list>
      </x-slot:content>
    </x-tabs>
  </div>
</x-admin-layout>