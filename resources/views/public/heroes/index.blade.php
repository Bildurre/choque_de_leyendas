<x-public-layout>
  <section class="block block--header" data-background="blue">
    <div class="block__inner">
      <div class="header-block__content text-center">
        <h2 class="header-block__title">{{ __('public.heroes.index_title') }}</h2>
        <h3 class="header-block__subtitle">{{ __('public.heroes.index_subtitle') }}</h3>
      </div>
    </div>
  </section>

  <!-- Lista de Héroes -->
  <section class="heroes-grid-section">
    <div class="container">
      <div class="heroes-filters">
        <div class="heroes-search">
          <input type="text" id="heroes-search-input" placeholder="{{ __('public.heroes.search_placeholder') }}" class="heroes-search__input">
        </div>
        
        <div class="heroes-filter-groups">
          <div class="heroes-filter-group">
            <label class="heroes-filter-group__label">{{ __('public.heroes.filter_by_faction') }}</label>
            <select id="faction-filter" class="heroes-filter-group__select">
              <option value="">{{ __('public.all') }}</option>
              @foreach($factions as $faction)
                <option value="{{ $faction->id }}">{{ $faction->name }}</option>
              @endforeach
            </select>
          </div>
          
          <div class="heroes-filter-group">
            <label class="heroes-filter-group__label">{{ __('public.heroes.filter_by_superclass') }}</label>
            <select id="superclass-filter" class="heroes-filter-group__select">
              <option value="">{{ __('public.all') }}</option>
              @foreach($superclasses as $superclass)
                <option value="{{ $superclass->id }}">{{ $superclass->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
      
      <div class="heroes-grid" id="heroes-grid">
        @foreach($heroes as $hero)
          <div class="heroes-grid__item" 
               data-faction="{{ $hero->faction_id }}" 
               data-superclass="{{ $hero->heroClass->hero_superclass_id }}"
               data-name="{{ strtolower($hero->name) }}">
            <a href="{{ route('public.heroes.show', $hero) }}" class="heroes-grid__link">
              <x-previews.hero :hero="$hero" />
            </a>
          </div>
        @endforeach
      </div>
      
      <div id="heroes-no-results" class="heroes-no-results" style="display: none;">
        {{ __('public.heroes.no_results') }}
      </div>
    </div>
  </section>

  <!-- Destacados de Cartas -->
  <section class="featured-section featured-section--cards">
    <div class="container">
      <div class="featured-section__header">
        <h2 class="featured-section__title">{{ __('public.heroes.featured_cards_title') }}</h2>
        <p class="featured-section__description">{{ __('public.heroes.featured_cards_description') }}</p>
      </div>
      
      <div class="featured-section__content">
        <div class="featured-cards">
          @foreach($featuredCards as $card)
            <div class="featured-cards__item">
              <a href="{{ route('public.cards.show', $card) }}" class="featured-cards__link">
                <x-previews.card :card="$card" />
              </a>
            </div>
          @endforeach
        </div>
      </div>
      
      <div class="featured-section__footer">
        <x-button-link :href="route('public.cards.index')" icon="layers" variant="primary">
          {{ __('public.heroes.view_all_cards') }}
        </x-button-link>
      </div>
    </div>
  </section>

  <!-- Destacados de Facciones -->
  <section class="featured-section featured-section--factions">
    <div class="container">
      <div class="featured-section__header">
        <h2 class="featured-section__title">{{ __('public.heroes.featured_factions_title') }}</h2>
        <p class="featured-section__description">{{ __('public.heroes.featured_factions_description') }}</p>
      </div>
      
      <div class="featured-section__content">
        <div class="featured-factions">
          @foreach($featuredFactions as $faction)
            <div class="featured-factions__item" style="--faction-color: {{ $faction->color }}">
              <a href="{{ route('public.factions.show', $faction) }}" class="featured-factions__link">
                <div class="featured-factions__icon">
                  <img src="{{ $faction->getImageUrl() }}" alt="{{ $faction->name }}">
                </div>
                <div class="featured-factions__info">
                  <h3 class="featured-factions__name">{{ $faction->name }}</h3>
                  <p class="featured-factions__description">{{ Str::limit(strip_tags($faction->lore_text), 100) }}</p>
                </div>
              </a>
            </div>
          @endforeach
        </div>
      </div>
      
      <div class="featured-section__footer">
        <x-button-link :href="route('public.factions.index')" icon="layers" variant="primary">
          {{ __('public.heroes.view_all_factions') }}
        </x-button-link>
      </div>
    </div>
  </section>
</x-public-layout>