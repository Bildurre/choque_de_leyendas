<x-public-layout>
  {{-- Header Block --}}
  <section class="block block--header">
    <div class="block__inner">
      <div class="header-block__content text-left">
        <h2 class="header-block__title">{{ __('public.heroes.title') }}</h2>
        @if(__('public.heroes.description') !== 'public.heroes.description')
          <h3 class="header-block__subtitle">{{ __('public.heroes.description') }}</h3>
        @endif
      </div>
    </div>
  </section>

  {{-- Heroes List Block --}}
  <section class="block">
    <div class="block__inner">
      <x-entity.list 
        :items="$heroes"
        :showHeader="false"
        emptyMessage="{{ __('public.heroes.no_heroes') }}"
      >
        <x-slot:filters>
          {{-- Space for future filters if needed --}}
        </x-slot:filters>

        @foreach($heroes as $hero)
          <div class="heroes-list__item">
            <a href="{{ route('public.heroes.show', $hero) }}" class="heroes-list__link">
              <x-previews.hero :hero="$hero" />
            </a>
          </div>
        @endforeach
        
        <x-slot:pagination>
          {{ $heroes->links() }}
        </x-slot:pagination>
      </x-entity.list>
    </div>
  </section>
</x-public-layout>