<x-public-layout
:title="__('entities.faction_decks.page_title', ['name' => $factionDeck->name])"
  :metaDescription="__('entities.faction_decks.page_description', [
    'name' => $factionDeck->name,
    'faction' => $factionDeck->faction->name,
    'description' => Str::limit(strip_tags($factionDeck->gameMode->name), 100)
  ])"
  ogType="article"
  :ogImage="$factionDeck->faction->getImageUrl() ?? $factionDeck->faction->getImageUrl()"
>
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

  {{-- Content Tabs y resto del contenido sin cambios --}}
</x-public-layout>