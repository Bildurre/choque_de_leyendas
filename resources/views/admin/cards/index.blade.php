<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('cards.plural') }}</h1>
  </div>
  
  <div class="page-content">
    <x-tabs>
      <x-slot:header>
        <x-tab-item 
          id="active" 
          :active="!$trashed" 
          :href="route('admin.cards.index')"
          icon="list"
          :count="$activeCount ?? null"
        >
          {{ __('admin.active_items') }}
        </x-tab-item>
        
        <x-tab-item 
          id="trashed" 
          :active="$trashed" 
          :href="route('admin.cards.index', ['trashed' => 1])"
          icon="trash"
          :count="$trashedCount ?? null"
        >
          {{ __('admin.trashed_items') }}
        </x-tab-item>
      </x-slot:header>
      
      <x-slot:content>
        <!-- Filtros -->
        <div class="card-filters">
          <form action="{{ route('admin.cards.index', $trashed ? ['trashed' => 1] : []) }}" method="GET" class="filters-form">
            @if($trashed)
              <input type="hidden" name="trashed" value="1">
            @endif
            
            <div class="filters-form__row">
              <div class="filters-form__field">
                <label for="search" class="filters-form__label">{{ __('cards.search') }}</label>
                <input 
                  type="text" 
                  name="search" 
                  id="search" 
                  value="{{ $filters['search'] ?? '' }}" 
                  class="form-input"
                  placeholder="{{ __('cards.search_placeholder') }}"
                >
              </div>
              
              <div class="filters-form__field">
                <label for="faction_id" class="filters-form__label">{{ __('factions.singular') }}</label>
                <select name="faction_id" id="faction_id" class="form-select">
                  <option value="">{{ __('cards.all_factions') }}</option>
                  <option value="no_faction" {{ isset($filters['faction_id']) && $filters['faction_id'] === 'no_faction' ? 'selected' : '' }}>
                    {{ __('cards.no_faction') }} ({{ $factionCounts['no_faction'] ?? 0 }})
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
                <label for="card_type_id" class="filters-form__label">{{ __('card_types.singular') }}</label>
                <select name="card_type_id" id="card_type_id" class="form-select">
                  <option value="">{{ __('cards.all_card_types') }}</option>
                  @foreach($cardTypes as $cardType)
                    <option 
                      value="{{ $cardType->id }}" 
                      {{ isset($filters['card_type_id']) && (string)$filters['card_type_id'] === (string)$cardType->id ? 'selected' : '' }}
                    >
                      {{ $cardType->name }} ({{ $cardTypeCounts[$cardType->id] ?? 0 }})
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
            
            <div class="filters-form__row">
              <div class="filters-form__field">
                <label for="equipment_type_id" class="filters-form__label">{{ __('equipment_types.singular') }}</label>
                <select name="equipment_type_id" id="equipment_type_id" class="form-select">
                  <option value="">{{ __('cards.all_equipment_types') }}</option>
                  <optgroup label="{{ __('equipment_types.categories.weapon') }}">
                    @foreach($equipmentTypes->where('category', 'weapon') as $equipmentType)
                      <option 
                        value="{{ $equipmentType->id }}" 
                        {{ isset($filters['equipment_type_id']) && (string)$filters['equipment_type_id'] === (string)$equipmentType->id ? 'selected' : '' }}
                      >
                        {{ $equipmentType->name }}
                      </option>
                    @endforeach
                  </optgroup>
                  <optgroup label="{{ __('equipment_types.categories.armor') }}">
                    @foreach($equipmentTypes->where('category', 'armor') as $equipmentType)
                      <option 
                        value="{{ $equipmentType->id }}" 
                        {{ isset($filters['equipment_type_id']) && (string)$filters['equipment_type_id'] === (string)$equipmentType->id ? 'selected' : '' }}
                      >
                        {{ $equipmentType->name }}
                      </option>
                    @endforeach
                  </optgroup>
                </select>
              </div>
              
              <div class="filters-form__field">
                <label for="attack_subtype_id" class="filters-form__label">{{ __('attack_subtypes.singular') }}</label>
                <select name="attack_subtype_id" id="attack_subtype_id" class="form-select">
                  <option value="">{{ __('cards.all_attack_subtypes') }}</option>
                  <optgroup label="{{ __('attack_subtypes.types.physical') }}">
                    @foreach($attackSubtypes->where('type', 'physical') as $attackSubtype)
                      <option 
                        value="{{ $attackSubtype->id }}" 
                        {{ isset($filters['attack_subtype_id']) && (string)$filters['attack_subtype_id'] === (string)$attackSubtype->id ? 'selected' : '' }}
                      >
                        {{ $attackSubtype->name }}
                      </option>
                    @endforeach
                  </optgroup>
                  <optgroup label="{{ __('attack_subtypes.types.magical') }}">
                    @foreach($attackSubtypes->where('type', 'magical') as $attackSubtype)
                      <option 
                        value="{{ $attackSubtype->id }}" 
                        {{ isset($filters['attack_subtype_id']) && (string)$filters['attack_subtype_id'] === (string)$attackSubtype->id ? 'selected' : '' }}
                      >
                        {{ $attackSubtype->name }}
                      </option>
                    @endforeach
                  </optgroup>
                </select>
              </div>
              
              <div class="filters-form__field">
                <label for="cost" class="filters-form__label">{{ __('cards.cost') }}</label>
                <select name="cost" id="cost" class="form-select">
                  <option value="">{{ __('cards.all_costs') }}</option>
                  <option value="free" {{ isset($filters['cost']) && $filters['cost'] === 'free' ? 'selected' : '' }}>
                    {{ __('game.cost.free') }}
                  </option>
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
                  {{ __('cards.area_attacks_only') }}
                </label>
              </div>
            </div>
            
            <div class="filters-form__actions">
              <button type="submit" class="btn btn--primary">
                {{ __('cards.apply_filters') }}
              </button>
              
              <a href="{{ route('admin.cards.index', $trashed ? ['trashed' => 1] : []) }}" class="btn btn--secondary">
                {{ __('cards.clear_filters') }}
              </a>
            </div>
          </form>
        </div>
        
        <x-entity.list 
          title="{{ $trashed ? __('cards.trashed') : __('cards.plural') }}"
          :create-route="!$trashed ? route('admin.cards.create') : null"
          :create-label="__('cards.create')"
          :items="$cards"
        >
          @foreach($cards as $card)
            @if($trashed)
              <x-entity.list-card 
                :title="$card->name"
                :delete-route="route('admin.cards.force-delete', $card->id)"
                :confirm-message="__('cards.confirm_force_delete')"
              >
                <x-slot:actions>
                  <form action="{{ route('admin.cards.restore', $card->id) }}" method="POST" class="action-button-form">
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
                  @if($card->cardType)
                    <x-badge variant="primary">
                      {{ $card->cardType->name }}
                    </x-badge>
                  @endif
                  
                  @if($card->faction)
                    <x-badge 
                      :variant="$card->faction->text_is_dark ? 'light' : 'dark'" 
                      style="background-color: {{ $card->faction->color }};"
                    >
                      {{ $card->faction->name }}
                    </x-badge>
                  @endif
                  
                  @if($card->equipmentType)
                    <x-badge variant="info">
                      {{ $card->equipmentType->name }} 
                      @if($card->hands)
                        ({{ $card->hands }} {{ trans_choice('cards.hands_count', $card->hands) }})
                      @endif
                    </x-badge>
                  @endif
                  
                  @if($card->attackSubtype)
                    <x-badge variant="{{ $card->attackSubtype->type === 'physical' ? 'warning' : 'success' }}">
                      {{ $card->attackSubtype->name }}
                      @if($card->area)
                        ({{ __('cards.area') }})
                      @endif
                    </x-badge>
                  @endif
                  
                  @if($card->cost)
                    <div class="badge-with-icons">
                      <span class="badge-with-icons__cost">{!! $card->icon_html !!}</span>
                    </div>
                  @endif
                  
                  <x-badge variant="danger">
                    {{ __('admin.deleted_at', ['date' => $card->deleted_at->format('d/m/Y H:i')]) }}
                  </x-badge>
                </x-slot:badges>
                
                <div class="card-details">
                  @if($card->image)
                    <div class="card-details__image">
                      <img src="{{ $card->getImageUrl() }}" alt="{{ $card->name }}" class="card-image">
                    </div>
                  @endif
                  
                  <div class="card-details__content">
                    @if($card->effect)
                      <div class="card-details__section">
                        <h4 class="card-details__label">{{ __('cards.effect') }}:</h4>
                        <div class="card-details__text">{{ strip_tags($card->effect) }}</div>
                      </div>
                    @endif
                    
                    @if($card->restriction)
                      <div class="card-details__section">
                        <h4 class="card-details__label">{{ __('cards.restriction') }}:</h4>
                        <div class="card-details__text">{{ strip_tags($card->restriction) }}</div>
                      </div>
                    @endif
                  </div>
                </div>
              </x-entity.list-card>
            @else
              <x-entity.list-card 
                :title="$card->name"
                :edit-route="route('admin.cards.edit', $card)"
                :delete-route="route('admin.cards.destroy', $card)"
                :confirm-message="__('cards.confirm_delete')"
              >
                <x-slot:badges>
                  @if($card->cardType)
                    <x-badge variant="primary">
                      {{ $card->cardType->name }}
                    </x-badge>
                  @endif
                  
                  @if($card->faction)
                    <x-badge 
                      :variant="$card->faction->text_is_dark ? 'light' : 'dark'" 
                      style="background-color: {{ $card->faction->color }};"
                    >
                      {{ $card->faction->name }}
                    </x-badge>
                  @endif
                  
                  @if($card->equipmentType)
                    <x-badge variant="info">
                      {{ $card->equipmentType->name }} 
                      @if($card->hands)
                        ({{ $card->hands }} {{ trans_choice('cards.hands_count', $card->hands) }})
                      @endif
                    </x-badge>
                  @endif
                  
                  @if($card->attackSubtype)
                    <x-badge variant="{{ $card->attackSubtype->type === 'physical' ? 'warning' : 'success' }}">
                      {{ $card->attackSubtype->name }}
                      @if($card->area)
                        ({{ __('cards.area') }})
                      @endif
                    </x-badge>
                  @endif
                  
                  @if($card->cost)
                    <div class="badge-with-icons">
                      <span class="badge-with-icons__cost">{!! $card->icon_html !!}</span>
                    </div>
                  @endif
                </x-slot:badges>
                
                <div class="card-details">
                  @if($card->image)
                    <div class="card-details__image">
                      <img src="{{ $card->getImageUrl() }}" alt="{{ $card->name }}" class="card-image">
                    </div>
                  @endif
                  
                  <div class="card-details__content">
                    @if($card->effect)
                      <div class="card-details__section">
                        <h4 class="card-details__label">{{ __('cards.effect') }}:</h4>
                        <div class="card-details__text">{{ strip_tags($card->effect) }}</div>
                      </div>
                    @endif
                    
                    @if($card->restriction)
                      <div class="card-details__section">
                        <h4 class="card-details__label">{{ __('cards.restriction') }}:</h4>
                        <div class="card-details__text">{{ strip_tags($card->restriction) }}</div>
                      </div>
                    @endif
                  </div>
                </div>
              </x-entity.list-card>
            @endif
          @endforeach
          
          @if(method_exists($cards, 'links'))
            <x-slot:pagination>
              {{ $cards->links() }}
            </x-slot:pagination>
          @endif
        </x-entity.list>
      </x-slot:content>
    </x-tabs>
  </div>
</x-admin-layout>