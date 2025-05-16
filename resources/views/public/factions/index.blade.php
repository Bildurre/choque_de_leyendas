<x-public-layout>
  <div class="factions-container">
    <h1 class="factions-title">{{ __('public.factions.title') }}</h1>
    
    <div class="factions-grid">
      @forelse($factions as $faction)
        <a href="{{ route('public.factions.show', $faction) }}" class="faction-card">
          <h2 class="faction-card__name">{{ $faction->name }}</h2>
        </a>
      @empty
        <div class="factions-empty">
          {{ __('public.factions.empty') }}
        </div>
      @endforelse
    </div>
  </div>
</x-public-layout>