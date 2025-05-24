<x-public-layout>
  {{-- Header Block --}}
  <section class="block block--header">
    <div class="block__inner">
      <div class="header-block__content text-left">
        <h2 class="header-block__title">{{ __('public.cards.title') }}</h2>
        @if(__('public.cards.description') !== 'public.cards.description')
          <h3 class="header-block__subtitle">{{ __('public.cards.description') }}</h3>
        @endif
      </div>
    </div>
  </section>

  {{-- Cards List Block --}}
  <section class="block">
    <div class="block__inner">
      <x-entity.list 
        :items="$cards"
        :showHeader="false"
        emptyMessage="{{ __('public.cards.no_cards') }}"
      >
        <x-slot:filters>
          {{-- Space for future filters if needed --}}
        </x-slot:filters>

        @foreach($cards as $card)
          <div class="cards-list__item">
            <a href="{{ route('public.cards.show', $card) }}" class="cards-list__link">
              <x-previews.preview-image :entity="$card" type="card" />
            </a>
          </div>
        @endforeach
        
        <x-slot:pagination>
          {{ $cards->links() }}
        </x-slot:pagination>
      </x-entity.list>
    </div>
  </section>
</x-public-layout>