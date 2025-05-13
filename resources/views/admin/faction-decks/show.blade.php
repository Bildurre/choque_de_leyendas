<!-- resources/views/admin/faction-decks/show.blade.php -->
<x-admin-layout>
  <div class="page-header">
    <div class="page-header__content">
      <h1 class="page-title">{{ $factionDeck->name }}</h1>
      
      <div class="page-header__actions">
        <x-button-link
          :href="route('admin.faction-decks.index')"
          variant="secondary"
          icon="arrow-left"
        >
          {{ __('admin.back_to_list') }}
        </x-button-link>
        
        <x-button-link
          :href="route('admin.faction-decks.edit', $factionDeck)"
          variant="primary"
          icon="edit"
        >
          {{ __('admin.edit') }}
        </x-button-link>
      </div>
    </div>
  </div>
  
  <div class="page-content">
    <div class="faction-deck-view">
      <div class="faction-deck-view__header">
        <div class="faction-deck-view__info">
          <div class="faction-deck-view__badges">
            <x-badge 
              :variant="$factionDeck->faction->text_is_dark ? 'light' : 'dark'" 
              style="background-color: {{ $factionDeck->faction->color }};"
            >
              {{ $factionDeck->faction->name }}
            </x-badge>
            
            <x-badge variant="info">
              {{ $factionDeck->gameMode->name }}
            </x-badge>
          </div>
          
          <div class="faction-deck-view__stats">
            <div class="faction-deck-stat">
              <span class="faction-deck-stat__label">{{ __('faction_decks.total_cards') }}:</span>
              <span class="faction-deck-stat__value">{{ $factionDeck->totalCards }}</span>
            </div>
            
            <div class="faction-deck-stat">
              <span class="faction-deck-stat__label">{{ __('faction_decks.unique_cards') }}:</span>
              <span class="faction-deck-stat__value">{{ $factionDeck->cards->count() }}</span>
            </div>
            
            <div class="faction-deck-stat">
              <span class="faction-deck-stat__label">{{ __('faction_decks.total_heroes') }}:</span>
              <span class="faction-deck-stat__value">{{ $factionDeck->totalHeroes }}</span>
            </div>
            
            <div class="faction-deck-stat">
              <span class="faction-deck-stat__label">{{ __('faction_decks.unique_heroes') }}:</span>
              <span class="faction-deck-stat__value">{{ $factionDeck->heroes->count() }}</span>
            </div>
          </div>
        </div>
        
        <div class="faction-deck-view__icon">
          @if($factionDeck->icon)
            <img src="{{ $factionDeck->getImageUrl() }}" alt="{{ $factionDeck->name }}" class="faction-deck-view__image">
          @else
            <div class="faction-deck-view__placeholder">
              <x-icon name="box" size="xl" />
            </div>
          @endif
        </div>
      </div>
      
      <div class="faction-deck-view__content">
        <div class="faction-deck-view__cards">
          <h2 class="faction-deck-view__section-title">{{ __('cards.plural') }}</h2>
          
          @if($factionDeck->cards->count() > 0)
            <div class="card-grid">
              @foreach($factionDeck->cards->groupBy('cardType.name') as $cardTypeName => $cardGroup)
                <div class="card-group">
                  <h3 class="card-group__title">{{ $cardTypeName }}</h3>
                  
                  <div class="card-group__cards">
                    @foreach($cardGroup as $card)
                      <div class="card-item">
                        <div class="card-item__header">
                          <h4 class="card-item__name">{{ $card->name }}</h4>
                          <span class="card-item__copies">x{{ $card->pivot->copies }}</span>
                        </div>
                        
                        @if($card->cost)
                          <div class="card-item__cost">
                            {!! $card->icon_html !!}
                          </div>
                        @endif
                        
                        <div class="card-item__info">
                          @if($card->effect)
                            <div class="card-item__effect">
                              {{ strip_tags($card->effect) }}
                            </div>
                          @endif
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <div class="faction-deck-view__empty">
              <p>{{ __('faction_decks.no_cards') }}</p>
            </div>
          @endif
        </div>
        
        <div class="faction-deck-view__heroes">
          <h2 class="faction-deck-view__section-title">{{ __('heroes.plural') }}</h2>
          
          @if($factionDeck->heroes->count() > 0)
            <div class="hero-grid">
              @foreach($factionDeck->heroes as $hero)
                <div class="hero-item">
                  <div class="hero-item__header">
                    <h3 class="hero-item__name">{{ $hero->name }}</h3>
                    <span class="hero-item__copies">x{{ $hero->pivot->copies }}</span>
                  </div>
                  
                  <div class="hero-item__info">
                    <div class="hero-item__class">
                      {{ $hero->heroClass->name }}
                    </div>
                    
                    <div class="hero-item__attributes">
                      <div class="hero-item__attribute">
                        <span class="hero-item__attribute-label">{{ __('attributes.agility') }}:</span>
                        <span class="hero-item__attribute-value">{{ $hero->agility }}</span>
                      </div>
                      <div class="hero-item__attribute">
                        <span class="hero-item__attribute-label">{{ __('attributes.mental') }}:</span>
                        <span class="hero-item__attribute-value">{{ $hero->mental }}</span>
                      </div>
                      <div class="hero-item__attribute">
                        <span class="hero-item__attribute-label">{{ __('attributes.will') }}:</span>
                        <span class="hero-item__attribute-value">{{ $hero->will }}</span>
                      </div>
                      <div class="hero-item__attribute">
                        <span class="hero-item__attribute-label">{{ __('attributes.strength') }}:</span>
                        <span class="hero-item__attribute-value">{{ $hero->strength }}</span>
                      </div>
                      <div class="hero-item__attribute">
                        <span class="hero-item__attribute-label">{{ __('attributes.armor') }}:</span>
                        <span class="hero-item__attribute-value">{{ $hero->armor }}</span>
                      </div>
                      <div class="hero-item__attribute hero-item__attribute--total">
                        <span class="hero-item__attribute-label">{{ __('attributes.health') }}:</span>
                        <span class="hero-item__attribute-value">{{ $hero->health }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <div class="faction-deck-view__empty">
              <p>{{ __('faction_decks.no_heroes') }}</p>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</x-admin-layout>