<x-public-layout
  :title="__('entities.faction_decks.index_page_title')"
  :metaDescription="__('entities.faction_decks.index_page_description')"
>
  <x-page-background :image="asset('storage/images/pages/faction-decks-bg.jpeg')" />

  {{-- Header Block --}}
  @php
    $titleTranslations = [];
    $subtitleTranslations = [];
    
    foreach (config('laravellocalization.supportedLocales', ['es' => [], 'en' => []]) as $locale => $data) {
      $titleTranslations[$locale] = __('public.faction_decks.title', [], $locale);
      
      $description = __('public.faction_decks.description', [], $locale);
      $subtitleTranslations[$locale] = $description !== 'public.faction_decks.description' ? $description : '';
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
  {!! $headerBlock->render() !!}

  {{-- Content Tabs --}}
  <section class="block">
    <div class="block__inner">
      @if($gameModes->isNotEmpty())
        <x-tabs>
          <x-slot:header>
            @foreach($gameModes as $gameMode)
              <x-tab-item 
                id="mode-{{ $gameMode->id }}" 
                :active="(string)$activeTab === (string)$gameMode->id" 
                :href="route('public.faction-decks.index', ['tab' => $gameMode->id])"
                :useInitial="true"
                :count="$deckCounts->get($gameMode->id, 0)"
              >
                {{ $gameMode->name }}
              </x-tab-item>
            @endforeach
          </x-slot:header>
          
          <x-slot:content>
            @if($selectedGameMode && $factionDecks->isNotEmpty())
              <x-entity.list
                :items="$factionDecks"
                :showHeader="false"
                emptyMessage="{{ __('public.faction_decks.no_decks') }}"
              >
                @foreach($factionDecks as $deck)
                  <x-entity.public-card 
                    :entity="$deck"
                    type="deck"
                    :view-route="route('public.faction-decks.show', $deck)"
                  />
                @endforeach
                
                <x-slot:pagination>
                  {{ $factionDecks->links() }}
                </x-slot:pagination>
              </x-entity.list>
            @else
              <div class="empty-message">
                <x-icon name="layers" class="empty-message__icon" />
                <p>{{ __('public.faction_decks.no_decks_for_mode', ['mode' => $selectedGameMode->name ?? '']) }}</p>
              </div>
            @endif
          </x-slot:content>
        </x-tabs>
      @else
        <div class="empty-message">
          <x-icon name="layers" class="empty-message__icon" />
          <p>{{ __('public.faction_decks.no_game_modes') }}</p>
        </div>
      @endif
    </div>
  </section>

  {{-- Related Heroes Block --}}
  @php
    $heroesBlock = new \App\Models\Block([
      'type' => 'relateds',
      'title' => ['es' => 'Héroes', 'en' => 'Heroes'],
      'subtitle' => ['es' => 'Conoce a los héroes del juego', 'en' => 'Meet the game heroes'],
      'background_color' => 'theme-card',
      'content' => [
        'en' => [
          'button_text' => __('View all Heroes'),
        ],
        'es' => [
          'button_text' => __('Ver todos los Héroes'),
        ]
      ],
      'settings' => [
        'model_type' => 'hero',
        'display_type' => 'random',
        'text_alignment' => 'left'
      ]
    ]);
  @endphp
  {!! $heroesBlock->render() !!}

  {{-- Related Cards Block --}}
  @php
    $cardsBlock = new \App\Models\Block([
      'type' => 'relateds',
      'title' => ['es' => 'Cartas', 'en' => 'Cards'],
      'subtitle' => ['es' => 'Descubre las cartas del juego', 'en' => 'Discover the game cards'],
      'background_color' => 'none',
      'content' => [
        'en' => [
          'button_text' => __('View all Cards'),
        ],
        'es' => [
          'button_text' => __('Ver todas las Cartas'),
        ]
      ],
      'settings' => [
        'model_type' => 'card',
        'display_type' => 'random',
        'text_alignment' => 'left'
      ]
    ]);
  @endphp
  {!! $cardsBlock->render() !!}
</x-public-layout>