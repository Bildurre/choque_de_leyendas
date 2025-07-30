<x-admin-layout>
  <div class="page-header">
    <div class="page-header__content">
      <h1 class="page-title">{{ $factionDeck->name }}</h1>
      <p class="page-subtitle">{{ $factionDeck->faction->name }} - {{ $factionDeck->gameMode->name }}</p>
      
      <div class="page-header__actions">
        <x-button-link
          :href="route('admin.faction-decks.index', ['tab' => $factionDeck->game_mode_id])"
          variant="secondary"
          icon="arrow-left"
        >
          {{ __('admin.back_to_list') }}
        </x-button-link>
        
        <x-button-link
          :href="route('admin.faction-decks.edit', $factionDeck)"
          variant="primary"
          icon="edit"
        >
          {{ __('admin.edit') }}
        </x-button-link>
        
        <x-button
          :action="route('admin.faction-decks.toggle-published', $factionDeck)"
          method="PUT"
          :variant="$factionDeck->isPublished() ? 'warning' : 'success'"
          :icon="$factionDeck->isPublished() ? 'eye-off' : 'eye'"
        >
          {{ $factionDeck->isPublished() ? __('admin.unpublish') : __('admin.publish') }}
        </x-button>
        
        <x-button
          :action="route('admin.faction-decks.destroy', $factionDeck)"
          method="DELETE"
          variant="danger"
          icon="trash"
          :confirm-message="__('entities.faction_decks.confirm_delete')"
        >
          {{ __('admin.delete') }}
        </x-button>
      </div>
    </div>
  </div>
  
  <div class="page-content">
    <x-tabs>
      <x-slot:header>
        <x-tab-item 
          id="info" 
          :active="$tab === 'info'" 
          :href="route('admin.faction-decks.show', [$factionDeck, 'tab' => 'info'])"
          icon="info"
        >
          {{ __('entities.faction_decks.tabs.info') }}
        </x-tab-item>
        
        <x-tab-item 
          id="heroes" 
          :active="$tab === 'heroes'" 
          :href="route('admin.faction-decks.show', [$factionDeck, 'tab' => 'heroes'])"
          icon="heroes"
          :count="$statistics['totalHeroes']"
        >
          {{ __('entities.faction_decks.tabs.heroes') }}
        </x-tab-item>
        
        <x-tab-item 
          id="cards" 
          :active="$tab === 'cards'" 
          :href="route('admin.faction-decks.show', [$factionDeck, 'tab' => 'cards'])"
          icon="cards"
          :count="$statistics['totalCards']"
        >
          {{ __('entities.faction_decks.tabs.cards') }}
        </x-tab-item>
      </x-slot:header>
      
      <x-slot:content>
        @if($tab === 'info')
          {{-- Information Tab --}}
          <div class="tab-content">
            <div class="deck-stats-wrapper">
              {{-- Basic Information --}}
              <x-entity-show.info-block title="entities.faction_decks.basic_info">
                <x-entity-show.info-list>
                  <x-entity-show.info-list-item 
                    label="{{ __('entities.faction_decks.name') }}"
                    :value="$factionDeck->name" 
                  />

                  <x-entity-show.info-list-item label="{{ __('entities.factions.singular') }}">
                    <x-entity-show.info-link :href="route('admin.factions.show', [$factionDeck->faction])">
                      {{ $factionDeck->faction->name }}
                    </x-entity-show.info-link>
                  </x-entity-show.info-list-item>
                  
                  <x-entity-show.info-list-item label="{{ __('entities.game_modes.singular') }}">
                    {{ $factionDeck->gameMode->name }}
                  </x-entity-show.info-list-item>
                  
                  <x-entity-show.info-list-item 
                    label="{{ __('entities.heroes.plural') }}" 
                    :value="$statistics['totalHeroes'] . ' (' . $statistics['uniqueHeroes'] . ' ' . __('admin.unique') . ')'" 
                  />

                  <x-entity-show.info-list-item 
                    label="{{ __('entities.cards.plural') }}" 
                    :value="$statistics['totalCards'] . ' (' . $statistics['uniqueCards'] . ' ' . __('admin.unique') . ')'" 
                  />
                  
                  <x-entity-show.info-list-item label="{{ __('admin.status') }}">
                    @if($factionDeck->isPublished())
                      <x-badge variant="success">{{ __('admin.published') }}</x-badge>
                    @else
                      <x-badge variant="warning">{{ __('admin.draft') }}</x-badge>
                    @endif
                  </x-entity-show.info-list-item>
                </x-entity-show.info-list>
              </x-entity-show.info-block>

              {{-- Description --}}
              @if($factionDeck->description)
                <x-entity-show.info-block title="entities.faction_decks.description">
                  <div class="prose">
                    {!! $factionDeck->description !!}
                  </div>
                </x-entity-show.info-block>
              @endif

              {{-- Epic Quote --}}
              @if($factionDeck->epic_quote)
                <x-entity-show.info-block title="entities.faction_decks.epic_quote">
                  <blockquote class="epic-quote">
                    {!! $factionDeck->epic_quote !!}
                  </blockquote>
                </x-entity-show.info-block>
              @endif

              {{-- Dice Distribution --}}
              @if($statistics['cardsByDiceCount']->count() > 0)
                <x-entity-show.info-block title="entities.faction_decks.dice_distribution">
                  <x-entity-show.info-list>
                    @foreach($statistics['cardsByDiceCount']->sortKeys() as $diceCount => $cardCount)
                      <x-entity-show.info-list-item 
                        label="{{ $diceCount == 0 ? __('entities.faction_decks.no_dice') : ($diceCount == 1 ? __('entities.faction_decks.dice_count_singular', ['count' => $diceCount]) : __('entities.faction_decks.dice_count_plural', ['count' => $diceCount])) }}" 
                        :value="$cardCount" 
                      />
                    @endforeach
                    
                    <x-entity-show.info-list-item 
                      label="{{ __('entities.faction_decks.average_dice_count') }}" 
                      :value="number_format($statistics['averageDiceCount'], 2)" 
                    />
                  </x-entity-show.info-list>
                </x-entity-show.info-block>
              @endif

              {{-- Symbol Distribution --}}
              <x-entity-show.info-block title="entities.faction_decks.symbol_distribution">
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
              <x-entity-show.info-block title="entities.faction_decks.card_type_breakdown">
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
              <x-entity-show.info-block title="entities.faction_decks.hero_superclass_breakdown">
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
              <x-entity-show.info-block title="entities.faction_decks.hero_class_breakdown">
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
          {{-- Heroes Tab --}}
          @php
            $heroes = $factionDeck->heroes->sortBy('name');
          @endphp
          
          <x-entity.list
            :items="$heroes"
            :showHeader="false"
            emptyMessage="{{ __('entities.faction_decks.no_heroes') }}"
            :wide="true"
          >
            @foreach($heroes as $hero)
              <x-entity.list-card 
                :title="$hero->name"
                :view-route="route('admin.heroes.show', $hero)"
                :edit-route="route('admin.heroes.edit', $hero)"
              >
                <x-slot:badges>
                  @if($hero->pivot->copies > 1)
                    <x-badge variant="primary">
                      x{{ $hero->pivot->copies }}
                    </x-badge>
                  @endif
                  
                  <x-badge 
                    :variant="$hero->faction->text_is_dark ? 'light' : 'dark'" 
                    style="background-color: {{ $hero->faction->color }};"
                  >
                    {{ $hero->faction->name }}
                  </x-badge>
                  
                  @if($hero->heroClass)
                    <x-badge variant="info">
                      {{ $hero->heroClass->name }}
                    </x-badge>
                  @endif
                  
                  @if($hero->heroRace)
                    <x-badge variant="secondary">
                      {{ $hero->heroRace->name }}
                    </x-badge>
                  @endif
                </x-slot:badges>
                
                <div class="hero-details">
                  <x-previews.preview-image :entity="$hero" type="hero"/>
                </div>
              </x-entity.list-card>
            @endforeach
          </x-entity.list>
          
        @elseif($tab === 'cards')
          {{-- Cards Tab --}}
          @php
            $cards = $factionDeck->cards->sortBy([
              ['cost', 'asc'],
              ['name', 'asc']
            ]);
          @endphp
          
          <x-entity.list
            :items="$cards"
            :showHeader="false"
            emptyMessage="{{ __('entities.faction_decks.no_cards') }}"
            :wide="true"
          >
            @foreach($cards as $card)
              <x-entity.list-card 
                :title="$card->name"
                :view-route="route('admin.cards.show', $card)"
                :edit-route="route('admin.cards.edit', $card)"
              >
                <x-slot:badges>
                  @if($card->pivot->copies > 1)
                    <x-badge variant="primary">
                      x{{ $card->pivot->copies }}
                    </x-badge>
                  @endif
                  
                  @if($card->cost)
                    <x-badge variant="secondary">
                      <x-cost-display :cost="$card->cost" />
                    </x-badge>
                  @endif
                  
                  <x-badge variant="info">
                    {{ $card->cardType->name }}
                  </x-badge>
                  
                  @if($card->faction)
                    <x-badge 
                      :variant="$card->faction->text_is_dark ? 'light' : 'dark'" 
                      style="background-color: {{ $card->faction->color }};"
                    >
                      {{ $card->faction->name }}
                    </x-badge>
                  @endif
                </x-slot:badges>
                
                <div class="card-details">
                  <x-previews.preview-image :entity="$card" type="card"/>
                </div>
              </x-entity.list-card>
            @endforeach
          </x-entity.list>
        @endif
      </x-slot:content>
    </x-tabs>
  </div>
</x-admin-layout>