<x-public-layout>
  {{-- Page background with faction icon --}}
  @if($faction->hasImage())
    <x-page-background :image="$faction->getImageUrl()" />
  @endif

  {{-- Header Block con acciones --}}
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
      'text_alignment' => 'left'
    ]
  ]);
@endphp

@component('content.blocks.header', ['block' => $headerBlock])
  @slot('actions')


    <x-button
      type="button"
      variant="primary"
      icon="pdf-add"
      data-entity-type="faction"
      data-entity-id="{{ $faction->id }}"
      class="print-collection-add"
    >
      {{ __('public.add_to_collection') }}
    </x-button>

    <x-button-link
      href="{{ route('public.print-collection.faction-pdf', $faction) }}"
      variant="secondary"
      icon="pdf-download"
      data-entity-type="faction"
      data-entity-id="{{ $faction->id }}"
      class="print-collection-download"
      target="_blank"
    >
      {{ __('public.download_pdf') }}
    </x-button-link>
  @endslot
@endcomponent

  {{-- Statistics Card --}}
  <section class="block">
    <div class="block__inner">
      <div class="faction-stats-card">
        <h2 class="faction-stats-card__title">{{ __('public.factions.statistics') }}</h2>
        <div class="faction-stats-card__grid">
          <div class="faction-stats-card__item">
            <span class="faction-stats-card__value">{{ $faction->cards()->published()->count() }}</span>
            <span class="faction-stats-card__label">{{ __('entities.cards.plural') }}</span>
          </div>
          <div class="faction-stats-card__item">
            <span class="faction-stats-card__value">{{ $faction->heroes()->published()->with('heroClass')->distinct('hero_class_id')->count('hero_class_id') }}</span>
            <span class="faction-stats-card__label">{{ __('entities.hero_classes.plural') }}</span>
          </div>
          <div class="faction-stats-card__item">
            <span class="faction-stats-card__value">{{ $faction->factionDecks()->published()->count() }}</span>
            <span class="faction-stats-card__label">{{ __('entities.faction_decks.plural') }}</span>
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
            :href="route('public.factions.show', ['faction' => $faction, 'tab' => 'heroes'])"
            icon="users"
            :count="$faction->heroes()->published()->count()"
          >
            {{ __('entities.heroes.plural') }}
          </x-tab-item>
          
          <x-tab-item 
            id="cards" 
            :active="request()->get('tab') === 'cards'" 
            :href="route('public.factions.show', ['faction' => $faction, 'tab' => 'cards'])"
            icon="layers"
            :count="$faction->cards()->published()->count()"
          >
            {{ __('entities.cards.plural') }}
          </x-tab-item>
          
          <x-tab-item 
            id="decks" 
            :active="request()->get('tab') === 'decks'" 
            :href="route('public.factions.show', ['faction' => $faction, 'tab' => 'decks'])"
            icon="box"
            :count="$faction->factionDecks()->published()->count()"
          >
            {{ __('entities.faction_decks.plural') }}
          </x-tab-item>
        </x-slot:header>
        
        <x-slot:content>
          @php
            $tab = request()->get('tab', 'heroes');
          @endphp
          
          @if($tab === 'heroes')
            {{-- Heroes Tab Content --}}
            @php
              $heroes = $faction->heroes()->published()
                ->with(['heroClass.heroSuperclass', 'heroRace', 'faction', 'heroAbilities.attackRange', 'heroAbilities.attackSubtype'])
                ->orderBy('name')
                ->paginate(12);
            @endphp
            
            <x-entity.list
              :items="$heroes"
              :showHeader="false"
              emptyMessage="{{ __('public.factions.no_heroes') }}"
            >
              @foreach($heroes as $hero)
                <x-entity.public-card 
                  :entity="$hero"
                  type="hero"
                  :view-route="route('public.heroes.show', $hero)"
                />
              @endforeach
              
              <x-slot:pagination>
                {{ $heroes->appends(['tab' => 'heroes'])->links() }}
              </x-slot:pagination>
            </x-entity.list>
            
          @elseif($tab === 'cards')
            {{-- Cards Tab Content --}}
            @php
              $cards = $faction->cards()->published()
                ->with(['cardType.heroSuperclass', 'equipmentType', 'attackRange', 'attackSubtype', 'heroAbility.attackRange', 'heroAbility.attackSubtype', 'faction'])
                ->orderBy('cost')
                ->orderBy('name')
                ->paginate(12);
            @endphp
            
            <x-entity.list
              :items="$cards"
              :showHeader="false"
              emptyMessage="{{ __('public.factions.no_cards') }}"
            >
              @foreach($cards as $card)
                <x-entity.public-card 
                  :entity="$card"
                  type="card"
                  :view-route="route('public.cards.show', $card)"
                />
              @endforeach
              
              <x-slot:pagination>
                {{ $cards->appends(['tab' => 'cards'])->links() }}
              </x-slot:pagination>
            </x-entity.list>
            
          @elseif($tab === 'decks')
            {{-- Decks Tab Content --}}
            @php
              $decks = $faction->factionDecks()->published()
                ->with(['gameMode', 'heroes.heroClass', 'cards.cardType'])
                ->orderBy('name')
                ->paginate(12);
            @endphp
            
            <x-entity.list
              :items="$decks"
              :showHeader="false"
              emptyMessage="{{ __('public.factions.no_decks') }}"
            >
              @foreach($decks as $deck)
                <x-entity.public-card 
                  :entity="$deck"
                  type="deck"
                  :view-route="route('public.faction-decks.show', $deck)"
                />
              @endforeach
              
              <x-slot:pagination>
                {{ $decks->appends(['tab' => 'decks'])->links() }}
              </x-slot:pagination>
            </x-entity.list>
          @endif
        </x-slot:content>
      </x-tabs>
    </div>
  </section>

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