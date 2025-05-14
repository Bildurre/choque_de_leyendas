@props([
  'name' => 'heroes',
  'label' => null,
  'heroes' => [],
  'selected' => [],
  'required' => false,
  'maxCopies' => 1
])

<div class="form-field">
  @if($label)
    <x-form.label :for="$name" :required="$required">{{ $label }}</x-form.label>
  @endif
  
  <div class="heroes-selector">
    <div class="heroes-selector__search">
      <input type="text" class="form-input heroes-selector__search-input" placeholder="{{ __('faction_decks.search_heroes') }}" />
    </div>
    
    <div class="heroes-selector__container">
      <div class="heroes-selector__list">
        @forelse($heroes as $hero)
          @php
            $isSelected = false;
            $copies = 0;
            
            // Determinar si el héroe está seleccionado
            if (is_array($selected)) {
              foreach($selected as $selectedHero) {
                if(isset($selectedHero['id']) && $selectedHero['id'] == $hero->id) {
                  $isSelected = true;
                  $copies = $selectedHero['copies'] ?? 1;
                  break;
                }
              }
            }
            
            // Si el héroe tiene una clase, usar su nombre. Si no, usar un valor por defecto
            $heroClassName = $hero->heroClass->name ?? 'Unknown';
            $heroClassClass = strtolower(str_replace(' ', '-', $heroClassName));
          @endphp
          
          <div class="heroes-selector__item {{ $isSelected ? 'is-selected' : '' }}" 
               data-hero-id="{{ $hero->id }}" 
               data-hero-name="{{ $hero->name }}" 
               data-hero-class="{{ $heroClassName }}">
            <div class="heroes-selector__hero">
              <div class="heroes-selector__hero-header">
                <div class="heroes-selector__hero-title">
                  <span class="heroes-selector__checkbox">
                    <input 
                      type="checkbox" 
                      id="hero_{{ $hero->id }}" 
                      class="heroes-selector__checkbox-input" 
                      {{ $isSelected ? 'checked' : '' }}
                    >
                    <label for="hero_{{ $hero->id }}" class="heroes-selector__checkbox-label"></label>
                  </span>
                  <span class="heroes-selector__hero-name">{{ $hero->name }}</span>
                </div>
                <div class="heroes-selector__hero-class hero-class--{{ $heroClassClass }}">
                  {{ $heroClassName }}
                </div>
              </div>
              
              <div class="heroes-selector__hero-attributes">
                <div class="heroes-selector__attribute">
                  <span class="heroes-selector__attribute-label">{{ __('entities.heroes.attributes.agility') }}:</span>
                  <span class="heroes-selector__attribute-value">{{ $hero->agility }}</span>
                </div>
                <div class="heroes-selector__attribute">
                  <span class="heroes-selector__attribute-label">{{ __('entities.heroes.attributes.mental') }}:</span>
                  <span class="heroes-selector__attribute-value">{{ $hero->mental }}</span>
                </div>
                <div class="heroes-selector__attribute">
                  <span class="heroes-selector__attribute-label">{{ __('entities.heroes.attributes.will') }}:</span>
                  <span class="heroes-selector__attribute-value">{{ $hero->will }}</span>
                </div>
                <div class="heroes-selector__attribute">
                  <span class="heroes-selector__attribute-label">{{ __('entities.heroes.attributes.strength') }}:</span>
                  <span class="heroes-selector__attribute-value">{{ $hero->strength }}</span>
                </div>
                <div class="heroes-selector__attribute">
                  <span class="heroes-selector__attribute-label">{{ __('entities.heroes.attributes.armor') }}:</span>
                  <span class="heroes-selector__attribute-value">{{ $hero->armor }}</span>
                </div>
                <div class="heroes-selector__attribute heroes-selector__attribute--total">
                  <span class="heroes-selector__attribute-label">{{ __('entities.heroes.attributes.health') }}:</span>
                  <span class="heroes-selector__attribute-value">{{ $hero->health ?? '-' }}</span>
                </div>
              </div>
              
              <div class="heroes-selector__copies">
                <label class="heroes-selector__copies-label">{{ __('entities.faction_decks.copies') }}:</label>
                <div class="heroes-selector__copies-controls">
                  <button type="button" class="heroes-selector__copies-btn heroes-selector__copies-btn--decrease" {{ $copies <= 1 ? 'disabled' : '' }} data-max-copies="{{ $maxCopies }}">-</button>
                  <input 
                    type="number" 
                    name="{{ $name }}[{{ $hero->id }}][copies]" 
                    class="heroes-selector__copies-input" 
                    value="{{ $copies ?: 1 }}" 
                    min="1" 
                    max="{{ $maxCopies }}" 
                    {{ !$isSelected ? 'disabled' : '' }}
                  >
                  <button type="button" class="heroes-selector__copies-btn heroes-selector__copies-btn--increase" {{ $copies >= $maxCopies ? 'disabled' : '' }} data-max-copies="{{ $maxCopies }}">+</button>
                  <input type="hidden" name="{{ $name }}[{{ $hero->id }}][id]" value="{{ $hero->id }}" {{ !$isSelected ? 'disabled' : '' }}>
                </div>
              </div>
            </div>
          </div>
        @empty
          <div class="heroes-selector__empty">
            <p>{{ __('faction_decks.no_heroes_available') }}</p>
          </div>
        @endforelse
      </div>
      
      <div class="heroes-selector__preview">
        <div class="heroes-selector__stats">
          <div class="heroes-selector__stat">
            <span class="heroes-selector__stat-label">{{ __('entities.faction_decks.total_heroes') }}:</span>
            <span class="heroes-selector__stat-value" id="total-heroes">0</span>
          </div>
          <div class="heroes-selector__stat">
            <span class="heroes-selector__stat-label">{{ __('entities.faction_decks.unique_heroes') }}:</span>
            <span class="heroes-selector__stat-value" id="unique-heroes">0</span>
          </div>
        </div>
        
        <div class="heroes-selector__selected">
          <h3 class="heroes-selector__selected-title">{{ __('faction_decks.selected_heroes') }}</h3>
          <div class="heroes-selector__selected-list" id="selected-heroes-list">
            <div class="heroes-selector__no-selection">{{ __('faction_decks.no_heroes_selected') }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <x-form.error :name="$name" />
</div>