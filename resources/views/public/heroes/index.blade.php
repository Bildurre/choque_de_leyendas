<x-public-layout>
  <div class="heroes-container">
    <h1 class="heroes-title">{{ __('public.heroes.title') }}</h1>
    
    <div class="heroes-grid">
      @forelse($heroes as $hero)
        <a href="{{ route('public.heroes.show', $hero) }}" class="hero-card">
          <h2 class="hero-card__name">{{ $hero->name }}</h2>
        </a>
      @empty
        <div class="heroes-empty">
          {{ __('public.heroes.empty') }}
        </div>
      @endforelse
    </div>
  </div>
</x-public-layout>