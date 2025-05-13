@props([
  'name' => 'hero_abilities',
  'label' => null,
  'abilities' => [],
  'selected' => [],
  'required' => false
])

<div class="form-field">
  @if($label)
    <x-form.label :for="$name" :required="$required">{{ $label }}</x-form.label>
  @endif
  
  <div class="hero-abilities-selector">
    <div class="hero-abilities-selector__controls">
      <div class="hero-abilities-selector__search">
        <input type="text" class="form-input hero-abilities-selector__search-input" placeholder="{{ __('heroes.search_abilities') }}" id="ability-search-input">
        <button type="button" class="hero-abilities-selector__search-clear" id="ability-search-clear">
          <x-icon name="x" size="sm" />
        </button>
      </div>
    </div>

    <div class="hero-abilities-selector__container">
      <div class="hero-abilities-selector__available">
        <h3 class="hero-abilities-selector__title">{{ __('heroes.available_abilities') }}</h3>
        <div class="hero-abilities-selector__list">
          @foreach($abilities as $ability)
            <div class="hero-ability-item {{ in_array($ability->id, $selected) ? 'is-selected' : '' }}" data-ability-id="{{ $ability->id }}">
              <input type="checkbox" id="ability_{{ $ability->id }}" name="{{ $name }}[]" value="{{ $ability->id }}" class="hero-ability-item__checkbox" {{ in_array($ability->id, $selected) ? 'checked' : '' }}>
              
              <div class="hero-ability-item__content">
                <div class="hero-ability-item__header">
                  <div class="hero-ability-item__name">{{ $ability->name }}</div>
                  
                  @if($ability->cost)
                    <div class="hero-ability-item__cost">
                      <div class="hero-ability-item__cost-icons">{!! $ability->icon_html !!}</div>
                    </div>
                  @endif
                </div>
                
                <div class="hero-ability-item__details">
                  @if($ability->attackRange)
                    <div class="hero-ability-item__range">
                      {{ $ability->attackRange->name }}
                    </div>
                  @endif
                  
                  @if($ability->attackSubtype)
                    <div class="hero-ability-item__subtype {{ $ability->attackSubtype->type }}">
                      {{ $ability->attackSubtype->name }}
                      @if($ability->area)
                        <span class="hero-ability-item__area">({{ __('hero_abilities.area') }})</span>
                      @endif
                    </div>
                  @endif
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
      
      <div class="hero-abilities-selector__selected">
        <h3 class="hero-abilities-selector__title">{{ __('heroes.selected_abilities') }}</h3>
        <div class="hero-abilities-selector__selected-list" id="abilities-selected-list">
          <div class="hero-abilities-selector__placeholder">{{ __('heroes.no_abilities_selected') }}</div>
        </div>
      </div>
    </div>
  </div>
  
  <x-form.error :name="$name" />
</div>