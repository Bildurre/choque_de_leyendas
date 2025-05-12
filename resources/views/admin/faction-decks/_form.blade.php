@php
  $submitRoute = isset($factionDeck) 
    ? route('admin.faction-decks.update', $factionDeck) 
    : route('admin.faction-decks.store');
  $submitMethod = isset($factionDeck) ? 'PUT' : 'POST';
  $submitLabel = isset($factionDeck) ? __('admin.update') : __('faction_decks.create');
  
  // Set default selections
  $selectedCards = $selectedCards ?? [];
  $selectedHeroes = $selectedHeroes ?? [];
  
  // Get max copies configuration
  $deckConfig = \App\Models\DeckAttributesConfiguration::getDefault();
  $maxCardCopies = $deckConfig->max_copies_per_card;
  $maxHeroCopies = $deckConfig->max_copies_per_hero;
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
      />
      
      <x-form.image-upload
        name="icon"
        :label="__('faction_decks.icon')"
        :current-image="isset($factionDeck) && $factionDeck->icon ? $factionDeck->getIconUrl() : null"
        :remove-name="isset($factionDeck) ? 'remove_icon' : null"
      />
    
      @if(isset($availableCards) && count($availableCards) > 0)
        <x-form.cards-selector
          :label="__('faction_decks.add_cards')"
          :cards="$availableCards"
          :selected="$selectedCards"
          :max-copies="$maxCardCopies"
        />
      @else
        <div class="faction-deck-empty-message">
          <p>{{ __('faction_decks.select_faction_first') }}</p>
        </div>
      @endif
    
      @if(isset($availableHeroes) && count($availableHeroes) > 0)
        <x-form.heroes-selector
          :label="__('faction_decks.add_heroes')"
          :heroes="$availableHeroes"
          :selected="$selectedHeroes"
          :max-copies="$maxHeroCopies"
        />
      @else
        <div class="faction-deck-empty-message">
          <p>{{ __('faction_decks.select_faction_first') }}</p>
        </div>
      @endif
    </div>
  </x-form.card>
</form>
