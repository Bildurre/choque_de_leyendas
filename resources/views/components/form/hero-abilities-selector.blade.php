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
    <div class="hero-abilities-selector__list">
      @foreach($abilities as $ability)
        <div class="hero-ability-item {{ in_array($ability->id, $selected) ? 'is-selected' : '' }}" data-ability-id="{{ $ability->id }}">
          <div class="hero-ability-item__header">
            <div class="hero-ability-item__selector">
              <input 
                type="checkbox" 
                id="ability_{{ $ability->id }}" 
                name="{{ $name }}[]" 
                value="{{ $ability->id }}" 
                class="hero-ability-item__checkbox"
                {{ in_array($ability->id, $selected) ? 'checked' : '' }}
              >
              <label for="ability_{{ $ability->id }}" class="hero-ability-item__label">{{ $ability->name }}</label>
            </div>
            
            <div class="hero-ability-item__info">
              @if($ability->cost)
                <div class="hero-ability-item__cost">
                  <div class="hero-ability-item__cost-icons">{!! $ability->icon_html !!}</div>
                </div>
              @endif
              
              @if($ability->attackSubtype)
                <div class="hero-ability-item__attack-type" title="{{ $ability->attackSubtype->name }}">
                  <span class="hero-ability-item__attack-subtype {{ $ability->attackSubtype->type }}">
                    {{ $ability->attackSubtype->name }}
                  </span>
                  @if($ability->attackRange)
                    <span class="hero-ability-item__attack-range">
                      {{ $ability->attackRange->name }}
                    </span>
                  @endif
                  @if($ability->area)
                    <span class="hero-ability-item__area">
                      {{ __('hero_abilities.area') }}
                    </span>
                  @endif
                </div>
              @endif
            </div>
          </div>
          
          <div class="hero-ability-item__preview">
            @if($ability->description)
              <div class="hero-ability-item__description">
                {{ strip_tags($ability->description) }}
              </div>
            @endif
          </div>
        </div>
      @endforeach
    </div>
    
    <div class="hero-abilities-selector__preview" id="abilities-preview">
      <div class="hero-abilities-selector__preview-placeholder">
        {{ __('heroes.select_ability_to_preview') }}
      </div>
      
      <div class="hero-abilities-selector__preview-content" style="display: none;">
        <h3 class="hero-abilities-selector__preview-title"></h3>
        
        <div class="hero-abilities-selector__preview-details">
          <div class="hero-abilities-selector__preview-cost"></div>
          <div class="hero-abilities-selector__preview-attack-type"></div>
        </div>
        
        <div class="hero-abilities-selector__preview-description"></div>
      </div>
    </div>
  </div>
  
  <x-form.error :name="$name" />
</div>