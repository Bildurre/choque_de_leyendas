<x-public-layout>
  {{-- Page background with deck icon or faction icon --}}
  @if($factionDeck->hasImage())
    <x-page-background :image="$factionDeck->getImageUrl()" />
  @elseif($factionDeck->faction->hasImage())
    <x-page-background :image="$factionDeck->faction->getImageUrl()" />
  @endif

  {{-- Header Block --}}
  @php
    $titleTranslations = [];
    $subtitleTranslations = [];
    
    foreach (config('laravellocalization.supportedLocales', ['es' => [], 'en' => []]) as $locale => $data) {
      $titleTranslations[$locale] = $factionDeck->getTranslation('name', $locale);
      // Add faction name as subtitle
      $subtitleTranslations[$locale] = $factionDeck->faction->getTranslation('name', $locale);
    }
    
    $headerBlock = new \App\Models\Block([
      'type' => 'header',
      'title' => $titleTranslations,
      'subtitle' => $subtitleTranslations,
      'background_color' => 'none',
      'settings' => [
        'text_alignment' => 'center'
      ]
    ]);
  @endphp
  {!! $headerBlock->render() !!}

  {{-- Action Buttons --}}
  <section class="block">
    <div class="block__inner">
      <div class="deck-actions">
        <button 
          type="button" 
          class="print-collection-add"
          data-entity-type="deck"
          data-entity-id="{{ $factionDeck->id }}"
        >
          <x-icon name="plus" />
          {{ __('public.add_deck_to_collection') }}
        </button>
        
        <a 
          href="{{ route('public.print-collection.deck-pdf', $factionDeck) }}"
          class="print-collection-download"
          target="_blank"
        >
          <x-icon name="download" />
          {{ __('public.download_deck_pdf') }}
        </a>
      </div>
    </div>
  </section>

  {{-- Statistics Card --}}
  <section class="block">
    <div class="block__inner">
      <div class="deck-stats-card">
        <h2 class="deck-stats-card__title">{{ __('public.faction_decks.statistics') }}</h2>
        <div class="deck-stats-card__grid">
          <div class="deck-stats-card__item">
            <span class="deck-stats-card__value">{{ $factionDeck->gameMode->name }}</span>
            <span class="deck-stats-card__label">{{ __('entities.game_modes.singular') }}</span>
          </div>
          <div class="deck-stats-card__item">
            <span class="deck-stats-card__value">{{ $factionDeck->totalHeroes }}</span>
            <span class="deck-stats-card__label">{{ __('entities.heroes.plural') }}</span>
          </div>
          <div class="deck-stats-card__item">
            <span class="deck-stats-card__value">{{ $factionDeck->totalCards }}</span>
            <span class="deck-stats-card__label">{{ __('entities.cards.plural') }}</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Content Tabs --}}
  <section class="block">
    <div class="block__inner">
      <x-tabs>
        <x-slot:header>
          <x-tab-item 
            id="heroes" 
            :active="request()->get('tab', 'heroes') === 'heroes'" 
            :href="route('public.faction-decks.show', ['faction_deck' => $factionDeck, 'tab' => 'heroes'])"
            icon="users"
            :count="$factionDeck->heroes->count()"
          >
            {{ __('entities.heroes.plural') }}
          </x-tab-item>
          
          <x-tab-item 
            id="cards" 
            :active="request()->get('tab') === 'cards'" 
            :href="route('public.faction-decks.show', ['faction_deck' => $factionDeck, 'tab' => 'cards'])"
            icon="layers"
            :count="$factionDeck->cards->count()"
          >
            {{ __('entities.cards.plural') }}
          </x-tab-item>
        </x-slot:header>
        
        <x-slot:content>
          @php
            $tab = request()->get('tab', 'heroes');
          @endphp
          
          @if($tab === 'heroes')
            {{-- Heroes Tab Content --}}
            <x-entity.list
              :items="$factionDeck->heroes"
              :showHeader="false"
              emptyMessage="{{ __('public.faction_decks.no_heroes') }}"
            >
              @foreach($factionDeck->heroes as $hero)
                <x-entity.public-card 
                  :entity="$hero"
                  type="hero"
                  :view-route="route('public.heroes.show', $hero)"
                />
              @endforeach
            </x-entity.list>
            
          @elseif($tab === 'cards')
            {{-- Cards Tab Content --}}
            <x-entity.list
              :items="$factionDeck->cards"
              :showHeader="false"
              emptyMessage="{{ __('public.faction_decks.no_cards') }}"
            >
              @foreach($factionDeck->cards->sortBy('cost')->sortBy('name') as $card)
                <x-entity.public-card 
                  :entity="$card"
                  type="card"
                  :view-route="route('public.cards.show', $card)"
                />
              @endforeach
            </x-entity.list>
          @endif
        </x-slot:content>
      </x-tabs>
    </div>
  </section>

  {{-- Back to Faction Button --}}
  <section class="block">
    <div class="block__inner">
      <div class="deck-actions">
        <x-button-link
          :href="route('public.factions.show', $factionDeck->faction)"
          variant="secondary"
          size="lg"
          icon="arrow-left"
        >
          {{ __('public.faction_decks.back_to_faction', ['faction' => $factionDeck->faction->name]) }}
        </x-button-link>
      </div>
    </div>
  </section>

  {{-- Related Decks Block --}}
  @php
    // Get other decks from the same faction
    $relatedDecks = $factionDeck->faction->factionDecks()
      ->published()
      ->where('id', '!=', $factionDeck->id)
      ->limit(4)
      ->get();
  @endphp
  
  @if($relatedDecks->count() > 0)
    <section class="block">
      <div class="block__inner">
        <h2 class="block__title">{{ __('public.faction_decks.other_decks') }}</h2>
        <div class="related-decks-grid">
          @foreach($relatedDecks as $relatedDeck)
            <div class="related-decks-grid__item">
              <a href="{{ route('public.faction-decks.show', $relatedDeck) }}" class="related-deck-card">
                <h3 class="related-deck-card__name">{{ $relatedDeck->name }}</h3>
                <x-badge variant="info">{{ $relatedDeck->gameMode->name }}</x-badge>
              </a>
            </div>
          @endforeach
        </div>
      </div>
    </section>
  @endif
</x-public-layout>