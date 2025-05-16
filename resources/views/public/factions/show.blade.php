<x-public-layout>
  <div class="faction-detail">
    <h1 class="faction-detail__name">{{ $faction->name }}</h1>
    
    <!-- Heroes de la facción -->
    <section class="faction-heroes">
      <h2 class="section-title">{{ __('public.factions.heroes') }}</h2>
      
      <div class="heroes-grid">
        @forelse($faction->heroes()->published()->get() as $hero)
          <a href="{{ route('public.heroes.show', $hero) }}" class="hero-card">
            <h3 class="hero-card__name">{{ $hero->name }}</h3>
          </a>
        @empty
          <div class="empty-message">{{ __('public.factions.no_heroes') }}</div>
        @endforelse
      </div>
    </section>
    
    <!-- Cartas de la facción -->
    <section class="faction-cards">
      <h2 class="section-title">{{ __('public.factions.cards') }}</h2>
      
      <div class="cards-grid">
        @forelse($faction->cards()->published()->get() as $card)
          <a href="{{ route('public.cards.show', $card) }}" class="card-card">
            <h3 class="card-card__name">{{ $card->name }}</h3>
          </a>
        @empty
          <div class="empty-message">{{ __('public.factions.no_cards') }}</div>
        @endforelse
      </div>
    </section>
    
    <!-- Mazos de la facción -->
    <section class="faction-decks">
      <h2 class="section-title">{{ __('public.factions.decks') }}</h2>
      
      <div class="decks-grid">
        @forelse($faction->factionDecks()->published()->get() as $deck)
          <div class="deck-card">
            <h3 class="deck-card__name">{{ $deck->name }}</h3>
          </div>
        @empty
          <div class="empty-message">{{ __('public.factions.no_decks') }}</div>
        @endforelse
      </div>
    </section>
  </div>
</x-public-layout>