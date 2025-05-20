<x-public-layout>
  <div class="heroes-container">
    <h1 class="heroes-title">{{ __('public.heroes.title') }}</h1>
    
    <div class="heroes-grid">
      @forelse($heroes as $hero)
        <x-previews.hero :hero="$hero" />
      @empty
        <div class="heroes-empty">
          {{ __('public.heroes.empty') }}
        </div>
      @endforelse
    </div>
  </div>
</x-public-layout>