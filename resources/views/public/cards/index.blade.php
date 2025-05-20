<x-public-layout>
  <div class="cards-container">
    <h1 class="cards-title">{{ __('public.cards.title') }}</h1>
    
    <div class="cards-grid">
      @forelse($cards as $card)
      <x-previews.card :card="$card" />
      @empty
        <div class="cards-empty">
          {{ __('public.cards.empty') }}
        </div>
      @endforelse
    </div>
  </div>
</x-public-layout>