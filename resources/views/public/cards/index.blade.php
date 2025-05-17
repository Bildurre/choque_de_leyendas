<x-public-layout>
  <div class="cards-container">
    <h1 class="cards-title">{{ __('public.cards.title') }}</h1>
    
    <div class="cards-grid">
      @forelse($cards as $hero)
        <a href="{{ route('public.cards.show', $hero) }}" class="hero-card">
          <h2 class="hero-card__name">{{ $hero->name }}</h2>
        </a>
      @empty
        <div class="cards-empty">
          {{ __('public.cards.empty') }}
        </div>
      @endforelse
    </div>
  </div>
</x-public-layout>