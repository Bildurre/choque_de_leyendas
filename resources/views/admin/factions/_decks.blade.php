<div class="faction-view__decks">
  @if($decks->count() > 0)
    <div class="deck-grid">
      @foreach($decks as $deck)
        <div class="deck-card">
          <div class="deck-card__header">
            <h3 class="deck-card__title">{{ $deck->name }}</h3>
            
            <div class="deck-card__badges">
              @if($deck->gameMode)
                <x-badge variant="info">
                  {{ $deck->gameMode->name }}
                </x-badge>
              @endif
              
              <x-badge variant="primary">
                {{ __('cards.count', ['count' => $deck->cards_count]) }}
              </x-badge>
              
              <x-badge variant="success">
                {{ __('heroes.count', ['count' => $deck->heroes_count]) }}
              </x-badge>
            </div>
          </div>
          
          <div class="deck-card__content">
            <div class="deck-card__image-container">
              @if($deck->icon)
                <img src="{{ $deck->getImageUrl() }}" alt="{{ $deck->name }}" class="deck-card__image">
              @else
                <div class="deck-card__image-placeholder">
                  <x-icon name="box" size="lg" />
                </div>
              @endif
            </div>
          </div>
          
          <div class="deck-card__footer">
            <x-action-button
              :href="route('admin.faction-decks.show', $deck)"
              icon="eye"
              variant="view"
              size="sm"
              :title="__('admin.view')"
            />
            <x-action-button
              :href="route('admin.faction-decks.edit', $deck)"
              icon="edit"
              variant="edit"
              size="sm"
              :title="__('admin.edit')"
            />
          </div>
        </div>
      @endforeach
    </div>
    
    <div class="pagination-container">
      {{ $decks->appends(['tab' => 'decks'])->links() }}
    </div>
  @else
    <div class="faction-view__empty">
      <p>{{ __('entities.factions.no_decks') }}</p>
      
      @if(isset($gameModes) && $gameModes->count() > 0)
        <x-dropdown 
          :label="__('entities.faction_decks.create')" 
          icon="plus"
          variant="primary"
        >
          @foreach($gameModes as $gameMode)
            <x-dropdown-item :href="route('admin.faction-decks.create', ['faction_id' => $faction->id, 'game_mode_id' => $gameMode->id])">
              {{ $gameMode->name }}
            </x-dropdown-item>
          @endforeach
        </x-dropdown>
      @endif
    </div>
  @endif
</div>