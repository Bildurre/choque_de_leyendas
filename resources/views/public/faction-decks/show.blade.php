@php
  $tab = request()->get('tab', 'info');
@endphp
<x-public-layout
  :title="__('entities.faction_decks.page_title', ['name' => $factionDeck->name])"
  :metaDescription="__('entities.faction_decks.page_description', [
    'name' => $factionDeck->name,
    'faction' => $factionDeck->factions->pluck('name')->join(', '),
    'description' => Str::limit(strip_tags($factionDeck->gameMode->name), 100)
  ])"
  ogType="article"
  :ogImage="$factionDeck->hasImage() ? $factionDeck->getImageUrl() : ($factionDeck->getPrimaryFaction()?->getImageUrl() ?? '')"
>
  {{-- Page background with deck icon or primary faction icon --}}
  @if($factionDeck->hasImage())
    <x-page-background :image="$factionDeck->getImageUrl()" />
  @elseif($factionDeck->getPrimaryFaction() && $factionDeck->getPrimaryFaction()->hasImage())
    <x-page-background :image="$factionDeck->getPrimaryFaction()->getImageUrl()" />
  @endif

  {{-- Header Block con acciones --}}
  @php
    $titleTranslations = [];
    $subtitleTranslations = [];
    
    foreach (config('laravellocalization.supportedLocales', ['es' => [], 'en' => []]) as $locale => $data) {
      $titleTranslations[$locale] = $factionDeck->getTranslation('name', $locale);
      
      $factionNames = $factionDeck->factions->map(function($faction) use ($locale) {
        return $faction->getTranslation('name', $locale);
      })->join(', ');
      
      $subtitleTranslations[$locale] = $factionNames . ' - ' . $factionDeck->gameMode->getTranslation('name', $locale);
    }
    
    $headerBlock = new \App\Models\Block([
      'type' => 'header',
      'title' => $titleTranslations,
      'subtitle' => $subtitleTranslations,
      'background_color' => 'theme-border',
      'settings' => [
        'text_alignment' => 'left'
      ]
    ]);
  @endphp

  @component('content.blocks.header', ['block' => $headerBlock])
    @slot('actions')
      <x-pdf.download-button
        :entity="$factionDeck"
        entityType="deck"
        type="outlined"
      >
      {{ __('pdf.download.button_title') }}
      </x-pdf.download-button>
    @endslot
  @endcomponent

  {{-- Content Tabs --}}
  <section class="block">
    <div class="block__inner">
      <x-tabs>
        <x-slot:header>
          <x-tab-item 
            id="info" 
            :active="request()->get('tab', 'info') === 'info'" 
            :href="route('public.faction-decks.show', ['factionDeck' => $factionDeck, 'tab' => 'info'])"
            icon="info"
          >
            {{ __('entities.faction_decks.tabs.info') }}
          </x-tab-item>
          
          <x-tab-item 
            id="heroes" 
            :active="request()->get('tab') === 'heroes'" 
            :href="route('public.faction-decks.show', ['factionDeck' => $factionDeck, 'tab' => 'heroes'])"
            icon="heroes"
            :count="$statistics['totalHeroes']"
          >
            {{ __('entities.faction_decks.tabs.heroes') }}
          </x-tab-item>
          
          <x-tab-item 
            id="cards" 
            :active="request()->get('tab') === 'cards'" 
            :href="route('public.faction-decks.show', ['factionDeck' => $factionDeck, 'tab' => 'cards'])"
            icon="cards"
            :count="$statistics['totalCards']"
          >
            {{ __('entities.faction_decks.tabs.cards') }}
          </x-tab-item>
        </x-slot:header>
        
        <x-slot:content>          
          @if($tab === 'info')
            {{-- Information Tab with all statistics --}}
            <div class="tab-content">
              <div class="info-blocks-grid">
                {{-- Basic Information --}}
                <x-entity-show.info-block title="public.faction_decks.basic_info">
                  <x-entity-show.info-list>
                    <x-entity-show.info-list-item 
                      label="{{ __('entities.faction_decks.name') }}"
                      :value="$factionDeck->name" 
                    />

                    <x-entity-show.info-list-item label="{{ __('entities.factions.plural') }}">
                      @foreach($factionDeck->factions as $faction)
                        <x-entity-show.info-link :href="route('public.factions.show', $faction)">
                          {{ $faction->name }}
                        </x-entity-show.info-link>
                        @if(!$loop->last), @endif
                      @endforeach
                    </x-entity-show.info-list-item>
                    
                    <x-entity-show.info-list-item label="{{ __('entities.game_modes.singular') }}">
                      <x-entity-show.info-link :href="route('public.faction-decks.index', ['tab' => $factionDeck->gameMode->id])">
                        {{ $factionDeck->gameMode->name }}
                      </x-entity-show.info-link>
                    </x-entity-show.info-list-item>
                    
                    <x-entity-show.info-list-item 
                      label="{{ __('entities.heroes.plural') }}" 
                      :value="$statistics['totalHeroes'] . ' (' . $statistics['uniqueHeroes'] . ' ' . __('public.unique') . ')'" 
                    />

                    <x-entity-show.info-list-item 
                      label="{{ __('entities.cards.plural') }}" 
                      :value="$statistics['totalCards'] . ' (' . $statistics['uniqueCards'] . ' ' . __('public.unique') . ')'" 
                    />
                  </x-entity-show.info-list>
                </x-entity-show.info-block>

                {{-- Dice Distribution --}}
                @if($statistics['cardsByDiceCount']->count() > 0)
                  <x-entity-show.info-block title="public.faction_decks.dice_distribution">
                    <x-entity-show.info-list>
                      @foreach($statistics['cardsByDiceCount']->sortKeys() as $diceCount => $cardCount)
                        <x-entity-show.info-list-item 
                          label="{{ $diceCount == 0 ? __('public.faction_decks.no_dice') : ($diceCount == 1 ? __('public.faction_decks.dice_count_singular', ['count' => $diceCount]) : __('public.faction_decks.dice_count_plural', ['count' => $diceCount])) }}" 
                          :value="$cardCount" 
                        />
                      @endforeach
                      
                      <x-entity-show.info-list-item 
                        label="{{ __('public.faction_decks.average_dice_count') }}" 
                        :value="number_format($statistics['averageDiceCount'], 2)" 
                      />
                    </x-entity-show.info-list>
                  </x-entity-show.info-block>
                @endif

                {{-- Symbol Distribution --}}
                <x-entity-show.info-block title="public.faction_decks.symbol_distribution">
                  <x-entity-show.info-list>
                    @if($statistics['symbolCounts']['R'] > 0)
                      <x-entity-show.info-list-item :value="$statistics['symbolCounts']['R']">
                        <x-slot:label>
                          <x-icon-dice type="red" />
                        </x-slot:label>
                      </x-entity-show.info-list-item>
                    @endif
                    
                    @if($statistics['symbolCounts']['G'] > 0)
                      <x-entity-show.info-list-item :value="$statistics['symbolCounts']['G']">
                        <x-slot:label>
                          <x-icon-dice type="green" />
                        </x-slot:label>
                      </x-entity-show.info-list-item>
                    @endif
                    
                    @if($statistics['symbolCounts']['B'] > 0)
                      <x-entity-show.info-list-item :value="$statistics['symbolCounts']['B']">
                        <x-slot:label>
                          <x-icon-dice type="blue" />
                        </x-slot:label>
                      </x-entity-show.info-list-item>
                    @endif
                  </x-entity-show.info-list>
                </x-entity-show.info-block>

                {{-- Card Type Breakdown --}}
                <x-entity-show.info-block title="public.faction_decks.card_type_breakdown">
                  <x-entity-show.info-list>
                    @foreach($statistics['cardsByType'] as $type => $copies)
                      @if($copies > 0)
                        @php
                          $label = str_starts_with($type, 'equipment_') 
                            ? __('entities.equipment_types.categories.' . str_replace('equipment_', '', $type))
                            : $type;
                        @endphp
                        <x-entity-show.info-list-item 
                          label="{{ $label }}" 
                          :value="$copies" 
                        />
                      @endif
                    @endforeach          
                  </x-entity-show.info-list>
                </x-entity-show.info-block>

                {{-- Hero Superclass Breakdown --}}
                <x-entity-show.info-block title="public.faction_decks.hero_superclass_breakdown">
                  <x-entity-show.info-list>
                    @foreach($statistics['heroesBySuperclass'] as $superclass => $copies)
                      @if($copies > 0)
                        <x-entity-show.info-list-item 
                          label="{{ $superclass }}" 
                          :value="$copies" 
                        />
                      @endif
                    @endforeach          
                  </x-entity-show.info-list>
                </x-entity-show.info-block>

                {{-- Hero Class Breakdown --}}
                <x-entity-show.info-block title="public.faction_decks.hero_class_breakdown">
                  <x-entity-show.info-list>
                    @foreach($statistics['heroesByClass'] as $class => $copies)
                      @if($copies > 0)
                        <x-entity-show.info-list-item 
                          label="{{ $class }}" 
                          :value="$copies" 
                        />
                      @endif
                    @endforeach          
                  </x-entity-show.info-list>
                </x-entity-show.info-block>
              </div>
            </div>
            
          @elseif($tab === 'heroes')
            {{-- Heroes Tab Content --}}
            @php
              $heroes = $factionDeck->heroes->sortBy('name');
            @endphp
            
            <x-entity.list
              :items="$heroes"
              :showHeader="false"
              emptyMessage="{{ __('public.faction_decks.no_heroes') }}"
              :wide=true
            >
              @foreach($heroes as $hero)
                <x-entity.public-card 
                  :entity="$hero"
                  type="hero"
                  :view-route="route('public.heroes.show', $hero)"
                />
              @endforeach
            </x-entity.list>
            
          @elseif($tab === 'cards')
            {{-- Cards Tab Content --}}
            @php
              $cards = $factionDeck->cards->sortBy([
                ['cost', 'asc'],
                ['name', 'asc']
              ]);
            @endphp
            
            <x-entity.list
              :items="$cards"
              :showHeader="false"
              emptyMessage="{{ __('public.faction_decks.no_cards') }}"
              :wide=true
            >
              @foreach($cards as $card)
                <x-entity.public-card 
                  :entity="$card"
                  type="card"
                  :view-route="route('public.cards.show', $card)"
                >
                  <x-slot:extra>
                    @if($card->pivot->copies > 1)
                      <div class="card-quantity">
                        <span>x{{ $card->pivot->copies }}</span>
                      </div>
                    @endif
                  </x-slot:extra>
                </x-entity.public-card>
              @endforeach
            </x-entity.list>
          @endif
        </x-slot:content>
      </x-tabs>
    </div>
  </section>

  {{-- epic quote --}}
  @php
    $epicText = new \App\Models\Block([
      'type' => 'quote',
      'title' => null,
      'subtitle' => [
        'es' => "<p>" . $factionDeck->getTranslation('epic_quote', 'es') . "</p>", 
        'en' => "<p>" . $factionDeck->getTranslation('epic_quote', 'en') . "</p>"
      ],
      'background_color' => 'theme-border',
      'content' => null,
      'settings' => [
        'text_alignment' => 'center'
      ]
    ]);
  @endphp
  {!! $epicText->render() !!}

  {{-- Related Factions Block --}}
  @php
    $relatedDecksBlock = new \App\Models\Block([
      'type' => 'relateds',
      'title' => null,
      'subtitle' => ['es' => 'Explora otros mazos del juego', 'en' => 'Explore other game decks'],
      'background_color' => 'none',
      'content' => [
        'en' => [
          'button_text' => __('View all Decks'),
        ],
        'es' => [
          'button_text' => __('Ver todos los Mazos'),
        ]
      ],
      'settings' => [
        'model_type' => 'deck',
        'display_type' => 'random',
        'text_alignment' => 'left',
        'button_size' => 'md',
        'button_variant' => 'secondary',
      ]
    ]);
  @endphp
  {!! $relatedDecksBlock->render() !!}
</x-public-layout>