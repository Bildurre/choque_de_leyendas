@php
  $submitRoute = isset($factionDeck) 
    ? route('admin.faction-decks.update', $factionDeck) 
    : route('admin.faction-decks.store');
  $submitMethod = isset($factionDeck) ? 'PUT' : 'POST';
  $submitLabel = isset($factionDeck) ? __('admin.update') : __('faction_decks.create');
  
  // Set default selections
  $selectedCards = $selectedCards ?? [];
  $selectedHeroes = $selectedHeroes ?? [];
  
  // Default values for max copies (will be updated via JS)
  $maxCardCopies = 2;
  $maxHeroCopies = 1;
@endphp

<form action="{{ $submitRoute }}" method="POST" enctype="multipart/form-data" class="form" id="faction-deck-form">
  @csrf
  @if($submitMethod === 'PUT')
    @method('PUT')
  @endif
  
  <x-form.card :submit_label="$submitLabel" :cancel_route="route('admin.faction-decks.index')">
    <div class="form-grid">
      <x-form.multilingual-input
        name="name"
        :label="__('faction_decks.name')"
        :values="isset($factionDeck) ? $factionDeck->getTranslations('name') : []"
        required
      />
      
      <x-form.select
        name="faction_id"
        :label="__('factions.singular')"
        :options="$factions->pluck('name', 'id')->toArray()"
        :selected="old('faction_id', isset($factionDeck) ? $factionDeck->faction_id : ($selectedFactionId ?? ''))"
        required
        id="faction-selector"
      />
      
      <x-form.select
        name="game_mode_id"
        :label="__('game_modes.singular')"
        :options="$gameModes->pluck('name', 'id')->toArray()"
        :selected="old('game_mode_id', isset($factionDeck) ? $factionDeck->game_mode_id : '')"
        required
        id="game-mode-selector"
      />
      
      <x-form.image-upload
        name="icon"
        :label="__('faction_decks.icon')"
        :current-image="isset($factionDeck) && $factionDeck->icon ? $factionDeck->getImageUrl() : null"
        :remove-name="isset($factionDeck) ? 'remove_icon' : null"
      />
      
      <div class="deck-configuration-info" id="deck-configuration-info" style="display: none;">
        <h3>{{ __('faction_decks.deck_configuration') }}</h3>
        <div class="deck-config-item">
          <span class="deck-config-label">{{ __('deck_attributes.min_cards') }}:</span>
          <span class="deck-config-value" id="min-cards-value">-</span>
        </div>
        <div class="deck-config-item">
          <span class="deck-config-label">{{ __('deck_attributes.max_cards') }}:</span>
          <span class="deck-config-value" id="max-cards-value">-</span>
        </div>
        <div class="deck-config-item">
          <span class="deck-config-label">{{ __('deck_attributes.max_copies_per_card') }}:</span>
          <span class="deck-config-value" id="max-copies-card-value">-</span>
        </div>
        <div class="deck-config-item">
          <span class="deck-config-label">{{ __('deck_attributes.max_copies_per_hero') }}:</span>
          <span class="deck-config-value" id="max-copies-hero-value">-</span>
        </div>
      </div>
    
      <div id="cards-container">
        @if(isset($availableCards) && count($availableCards) > 0)
          <x-form.cards-selector
            :label="__('faction_decks.add_cards')"
            :cards="$availableCards"
            :selected="$selectedCards"
            :max-copies="$maxCardCopies"
          />
        @else
          <div class="faction-deck-empty-message" id="cards-placeholder">
            <p>{{ __('faction_decks.select_faction_first') }}</p>
          </div>
        @endif
      </div>
    
      <div id="heroes-container">
        @if(isset($availableHeroes) && count($availableHeroes) > 0)
          <x-form.heroes-selector
            :label="__('faction_decks.add_heroes')"
            :heroes="$availableHeroes"
            :selected="$selectedHeroes"
            :max-copies="$maxHeroCopies"
          />
        @else
          <div class="faction-deck-empty-message" id="heroes-placeholder">
            <p>{{ __('faction_decks.select_faction_first') }}</p>
          </div>
        @endif
      </div>
    </div>
  </x-form.card>
</form>