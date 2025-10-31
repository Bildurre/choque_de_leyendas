@props([
  'cards' => [],
  'selectedCards' => [],
  'name' => 'cards',
  'maxCopies' => 3,
  'minCards' => 0,
  'maxCards' => 999,
])

@php
  $selectedIds = collect($selectedCards)->pluck('id')->toArray();
  $selectedCardsData = collect($selectedCards)->keyBy('id');
  
  // Calculate initial total cards (sum of copies)
  $initialTotalCards = collect($selectedCards)->sum('copies');
@endphp

<div class="deck-card-selector" 
  data-component="deck-card-selector" 
  data-max-copies="{{ $maxCopies }}"
  data-min-cards="{{ $minCards }}"
  data-max-cards="{{ $maxCards }}"
  data-initial-total="{{ $initialTotalCards }}"
>
  {{-- Search Input --}}
  <div class="deck-card-selector__search">
    <input 
      type="text" 
      class="deck-card-selector__search-input"
      placeholder="{{ __('entities.cards.search_placeholder') }}"
      data-search-input
    >
    <button 
      type="button" 
      class="deck-card-selector__search-clear" 
      data-search-clear
      style="display: none;"
      aria-label="{{ __('common.clear_search') }}"
    >
      <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      </svg>
    </button>
  </div>

  {{-- Two Column Layout --}}
  <div class="deck-card-selector__columns">
    {{-- Available Cards --}}
    <div class="deck-card-selector__column">
      <h3 class="deck-card-selector__title">
        {{ __('entities.cards.available') }}
      </h3>
      <div class="deck-card-selector__list" data-available-list>
        @forelse($cards as $card)
          <div 
            class="deck-card-selector__item" 
            data-card-item
            data-card-id="{{ $card->id }}"
            data-card-name="{{ strtolower($card->name) }}"
            data-card-faction="{{ $card->faction_id }}"
            data-card-unique="{{ $card->is_unique ? 'true' : 'false' }}"
            style="{{ in_array($card->id, $selectedIds) ? 'display: none;' : '' }}"
          >
            @include('admin.faction-decks._card-item', ['card' => $card])
          </div>
        @empty
          <p class="deck-card-selector__empty">
            {{ __('entities.cards.no_cards_available') }}
          </p>
        @endforelse
      </div>
    </div>

    {{-- Selected Cards --}}
    <div class="deck-card-selector__column">
      <h3 class="deck-card-selector__title">
        {{ __('entities.cards.selected') }}
        <span class="deck-card-selector__count" data-selected-count>0</span>
        <span class="deck-card-selector__total-cards" data-total-cards-display>
          <span data-total-cards>{{ $initialTotalCards }}</span>/{{ $minCards }}-{{ $maxCards }}
        </span>
      </h3>
      <div class="deck-card-selector__list" data-selected-list>
        @forelse($selectedCards as $selected)
          @php
            $card = $cards->firstWhere('id', $selected['id']);
          @endphp
          @if($card)
            <div 
              class="deck-card-selector__item deck-card-selector__item--selected" 
              data-selected-item
              data-card-id="{{ $card->id }}"
              data-card-unique="{{ $card->is_unique ? 'true' : 'false' }}"
            >
              @include('admin.faction-decks._card-item', [
                'card' => $card, 
                'isSelected' => true,
                'copies' => $selected['copies'] ?? 1
              ])
            </div>
          @endif
        @empty
          <p class="deck-card-selector__empty" data-empty-message>
            {{ __('entities.cards.no_cards_selected') }}
          </p>
        @endforelse
      </div>
    </div>
  </div>

  {{-- Hidden inputs container --}}
  <div data-hidden-inputs style="display: none;"></div>
</div>