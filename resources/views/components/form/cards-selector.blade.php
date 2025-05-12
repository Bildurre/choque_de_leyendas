@props([
  'name' => 'cards',
  'label' => null,
  'cards' => [],
  'selected' => [],
  'required' => false,
  'maxCopies' => 2
])

<div class="form-field">
  @if($label)
    <x-form.label :for="$name" :required="$required">{{ $label }}</x-form.label>
  @endif
  
  <div class="cards-selector">
    <div class="cards-selector__search">
      <input type="text" class="form-input cards-selector__search-input" placeholder="{{ __('faction_decks.search_cards') }}" />
    </div>
    
    <div class="cards-selector__container">
      <div class="cards-selector__list">
        @forelse($cards as $card)
          @php
            $isSelected = false;
            $copies = 0;
            
            // Determinar si la carta está seleccionada
            if (is_array($selected)) {
              foreach($selected as $selectedCard) {
                if(isset($selectedCard['id']) && $selectedCard['id'] == $card->id) {
                  $isSelected = true;
                  $copies = $selectedCard['copies'] ?? 1;
                  break;
                }
              }
            }
            
            // Si la carta tiene un tipo, usar su nombre. Si no, usar un valor por defecto
            $cardTypeName = $card->cardType->name ?? 'Unknown';
            $cardTypeClass = strtolower(str_replace(' ', '-', $cardTypeName));
          @endphp
          
          <div class="cards-selector__item {{ $isSelected ? 'is-selected' : '' }}" 
               data-card-id="{{ $card->id }}" 
               data-card-name="{{ $card->name }}" 
               data-card-type="{{ $cardTypeName }}">
            <div class="cards-selector__card">
              <div class="cards-selector__card-header">
                <div class="cards-selector__card-title">
                  <span class="cards-selector__checkbox">
                    <input 
                      type="checkbox" 
                      id="card_{{ $card->id }}" 
                      class="cards-selector__checkbox-input" 
                      {{ $isSelected ? 'checked' : '' }}
                    >
                    <label for="card_{{ $card->id }}" class="cards-selector__checkbox-label"></label>
                  </span>
                  <span class="cards-selector__card-name">{{ $card->name }}</span>
                </div>
                <div class="cards-selector__card-type card-type--{{ $cardTypeClass }}">
                  {{ $cardTypeName }}
                </div>
              </div>
              
              <div class="cards-selector__copies">
                <label class="cards-selector__copies-label">{{ __('faction_decks.copies') }}:</label>
                <div class="cards-selector__copies-controls">
                  <button type="button" class="cards-selector__copies-btn cards-selector__copies-btn--decrease" {{ $copies <= 1 ? 'disabled' : '' }} data-max-copies="{{ $maxCopies }}">-</button>
                  <input 
                    type="number" 
                    name="{{ $name }}[{{ $card->id }}][copies]" 
                    class="cards-selector__copies-input" 
                    value="{{ $copies ?: 1 }}" 
                    min="1" 
                    max="{{ $maxCopies }}" 
                    {{ !$isSelected ? 'disabled' : '' }}
                  >
                  <button type="button" class="cards-selector__copies-btn cards-selector__copies-btn--increase" {{ $copies >= $maxCopies ? 'disabled' : '' }} data-max-copies="{{ $maxCopies }}">+</button>
                  <input type="hidden" name="{{ $name }}[{{ $card->id }}][id]" value="{{ $card->id }}" {{ !$isSelected ? 'disabled' : '' }}>
                </div>
              </div>
              
              @if(isset($card->cost) && $card->cost)
                <div class="cards-selector__cost">
                  @if(method_exists($card, 'getIconHtmlAttribute'))
                    {!! $card->icon_html !!}
                  @else
                    {{ $card->cost }}
                  @endif
                </div>
              @endif
            </div>
          </div>
        @empty
          <div class="cards-selector__empty">
            <p>{{ __('faction_decks.no_cards_available') }}</p>
          </div>
        @endforelse
      </div>
      
      <div class="cards-selector__preview">
        <div class="cards-selector__stats">
          <div class="cards-selector__stat">
            <span class="cards-selector__stat-label">{{ __('faction_decks.total_cards') }}:</span>
            <span class="cards-selector__stat-value" id="total-cards">0</span>
          </div>
          <div class="cards-selector__stat">
            <span class="cards-selector__stat-label">{{ __('faction_decks.unique_cards') }}:</span>
            <span class="cards-selector__stat-value" id="unique-cards">0</span>
          </div>
        </div>
        
        <div class="cards-selector__selected">
          <h3 class="cards-selector__selected-title">{{ __('faction_decks.selected_cards') }}</h3>
          <div class="cards-selector__selected-list" id="selected-cards-list">
            <div class="cards-selector__no-selection">{{ __('faction_decks.no_cards_selected') }}</div>
          </div>
        </div>
        
        <div class="cards-selector__type-distribution">
          <h3 class="cards-selector__type-title">{{ __('faction_decks.card_type_distribution') }}</h3>
          <div class="cards-selector__chart" id="card-type-chart"></div>
        </div>
      </div>
    </div>
  </div>
  
  <x-form.error :name="$name" />
</div>