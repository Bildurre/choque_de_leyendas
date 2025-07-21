<x-blocks.block :block="$block">
  {{-- Titles always go first, outside the wrapper --}}
  <x-blocks.titles :block="$block" />
  <div class="block__content">

    @if($block->content)
      <div class="block__text">{!! $block->content !!}</div>
    @endif
    
    @if($gameModes->isNotEmpty())
      <div class="game-modes-list">
        @foreach($gameModes as $mode)
          <article class="game-modes-list__item">
            <x-entity-show.info-block :title="$mode->name" class="game-modes-list__block">
              <x-icon name="decks" class="game-modes-list__icon" />
              
              @if($mode->description)
                <x-entity-show.effect-section>
                  {!! $mode->description !!}
                </x-entity-show.effect-section>
              @endif
              
              @if($mode->deckConfiguration)
                <x-entity-show.effect-section :title="__('entities.deck_attributes.singular')">
                  <x-entity-show.info-list>
                    <x-entity-show.info-list-item 
                      :label="__('entities.deck_attributes.min_cards')" 
                      :value="$mode->deckConfiguration->min_cards" 
                    />
                    <x-entity-show.info-list-item 
                      :label="__('entities.deck_attributes.max_cards')" 
                      :value="$mode->deckConfiguration->max_cards" 
                    />
                    <x-entity-show.info-list-item 
                      :label="__('entities.deck_attributes.max_copies_per_card')" 
                      :value="$mode->deckConfiguration->max_copies_per_card" 
                    />
                    <x-entity-show.info-list-item 
                      :label="__('entities.deck_attributes.max_copies_per_hero')" 
                      :value="$mode->deckConfiguration->max_copies_per_hero" 
                    />
                    <x-entity-show.info-list-item 
                      :label="__('entities.deck_attributes.required_heroes')" 
                      :value="$mode->deckConfiguration->required_heroes" 
                    />
                  </x-entity-show.info-list>
                </x-entity-show.effect-section>
              @endif
              
              @if($mode->factionDecks->isNotEmpty())
                <div class="game-modes-list__decks">
                  <span class="game-modes-list__decks-label">{{ __('pages.blocks.game_modes.faction_decks') }}:</span>
                  <span class="game-modes-list__decks-count">{{ $mode->factionDecks->count() }}</span>
                </div>
              @endif
            </x-entity-show.info-block>
          </article>
        @endforeach
      </div>
    @else
      <div class="block__empty">
        <x-icon name="decks" class="block__empty-icon" />
        <p>{{ __('pages.blocks.game_modes.no_modes') }}</p>
      </div>
    @endif
  </div>
</x-blocks.block>