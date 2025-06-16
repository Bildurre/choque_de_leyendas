<x-public-layout>
  {{-- Page background image --}}
  @if($faction->hasImage())
    <x-page-background :image="$faction->getImageUrl()" />
  @endif

  {{-- Header Block --}}
  @php
    $titleTranslations = [];
    $subtitleTranslations = [];
    
    foreach (config('laravellocalization.supportedLocales', ['es' => [], 'en' => []]) as $locale => $data) {
      $titleTranslations[$locale] = $faction->getTranslation('name', $locale);
      $subtitleTranslations[$locale] = $faction->getTranslation('lore_text', $locale);
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

  {{-- Faction Details Section --}}
  <section class="block faction-detail-block">
    <div class="block__inner">
      <div class="faction-detail-content">
        {{-- Preview Image --}}
        <div class="faction-detail__preview">
          <x-previews.preview-image :entity="$faction" type="faction" />
        </div>
        
        {{-- Faction Info --}}
        <div class="faction-detail__info">
          {{-- Basic Info Block --}}
          <div class="info-block">
            <h2 class="info-block__title">{{ __('public.factions.basic_info') }}</h2>
            <dl class="info-list">
              <dt>{{ __('entities.factions.name') }}</dt>
              <dd>{{ $faction->name }}</dd>
              
              <dt>{{ __('entities.factions.color') }}</dt>
              <dd>
                <span class="color-indicator" style="background-color: {{ $faction->color }};"></span>
                {{ $faction->color }}
              </dd>
              
              <dt>{{ __('entities.heroes.plural') }}</dt>
              <dd>{{ $faction->heroes()->published()->count() }}</dd>
              
              <dt>{{ __('entities.cards.plural') }}</dt>
              <dd>{{ $faction->cards()->published()->count() }}</dd>
              
              <dt>{{ __('entities.faction_decks.plural') }}</dt>
              <dd>{{ $faction->factionDecks()->published()->count() }}</dd>
            </dl>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Heroes Section --}}
  @if($faction->heroes()->published()->count() > 0)
    <section class="block faction-heroes-block">
      <div class="block__inner">
        <h2 class="block__title">{{ __('public.factions.faction_heroes') }}</h2>
        
        <div class="heroes-grid">
          @foreach($faction->heroes()->published()->orderBy('name')->get() as $hero)
            <div class="heroes-grid__item">
              <a href="{{ route('public.heroes.show', $hero) }}" class="heroes-grid__link">
                <x-previews.preview-image :entity="$hero" type="hero" />
                <div class="heroes-grid__info">
                  <h3 class="heroes-grid__name">{{ $hero->name }}</h3>
                  @if($hero->class)
                    <span class="heroes-grid__class">{{ $hero->class->name }}</span>
                  @endif
                </div>
              </a>
            </div>
          @endforeach
        </div>
      </div>
    </section>
  @endif

  {{-- Cards Section --}}
  @if($faction->cards()->published()->count() > 0)
    <section class="block faction-cards-block">
      <div class="block__inner">
        <h2 class="block__title">{{ __('public.factions.faction_cards') }}</h2>
        
        <div class="cards-grid">
          @foreach($faction->cards()->published()->orderBy('cost')->orderBy('name')->get() as $card)
            <div class="cards-grid__item">
              <a href="{{ route('public.cards.show', $card) }}" class="cards-grid__link">
                <div class="cards-grid__header">
                  <h3 class="cards-grid__name">{{ $card->name }}</h3>
                </div>
              </a>
            </div>
          @endforeach
        </div>
      </div>
    </section>
  @endif

  {{-- Faction Decks Section --}}
  @if($faction->factionDecks()->published()->count() > 0)
    <section class="block faction-decks-block">
      <div class="block__inner">
        <h2 class="block__title">{{ __('public.factions.preset_decks') }}</h2>
        
        <div class="decks-list">
          @foreach($faction->factionDecks()->published()->orderBy('name')->get() as $deck)
            <div class="deck-item">
              <div class="deck-item__content">
                <h3 class="deck-item__name">{{ $deck->name }}</h3>
                @if($deck->description)
                  <p class="deck-item__description">{{ $deck->description }}</p>
                @endif
              </div>
              <a href="{{ route('public.faction-decks.show', $deck) }}" class="deck-item__link">
                {{ __('public.factions.view_deck') }}
                <x-icon name="arrow-right" size="sm" />
              </a>
            </div>
          @endforeach
        </div>
      </div>
    </section>
  @endif

  {{-- Related Factions Block --}}
  @php
    $relatedFactionsBlock = new \App\Models\Block([
      'type' => 'relateds',
      'title' => ['es' => 'Otras Facciones', 'en' => 'Other Factions'],
      'subtitle' => ['es' => 'Explora más facciones del juego', 'en' => 'Explore more game factions'],
      'background_color' => 'none',
      'settings' => [
        'model_type' => 'faction',
        'display_type' => 'random',
        'button_text' => __('public.view_all_factions'),
        'text_alignment' => 'left'
      ]
    ]);
  @endphp
  {!! $relatedFactionsBlock->render() !!}
</x-public-layout>