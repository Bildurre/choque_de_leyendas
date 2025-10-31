@php
  $submitRoute = isset($factionDeck) 
    ? route('admin.faction-decks.update', $factionDeck) 
    : route('admin.faction-decks.store');
  $submitMethod = isset($factionDeck) ? 'PUT' : 'POST';
  $submitLabel = isset($factionDeck) ? __('admin.update') : __('entities.faction_decks.create');
@endphp

<form action="{{ $submitRoute }}" method="POST" enctype="multipart/form-data" id="faction-deck-form" class="form">
  @csrf
  @if($submitMethod === 'PUT')
    @method('PUT')
  @endif
  
  <input type="hidden" name="game_mode_id" value="{{ $gameModeId ?? (isset($factionDeck) ? $factionDeck->game_mode_id : '') }}">
  
  <x-form.card :submit_label="$submitLabel" :cancel_route="route('admin.faction-decks.index')">
      
    <p>{{ __('entities.faction_decks.selected_game_mode') }}: <strong>{{ $gameMode->name ?? (isset($factionDeck) ? $factionDeck->gameMode->name : '') }}</strong></p>
    
    @if(isset($deckConfig))
        <ul>
          <li>{{ __('entities.faction_decks.min_cards') }}: <strong>{{ $deckConfig->min_cards }}</strong></li>
          <li>{{ __('entities.faction_decks.max_cards') }}: <strong>{{ $deckConfig->max_cards }}</strong></li>
          <li>{{ __('entities.faction_decks.max_copies_per_card') }}: <strong>{{ $deckConfig->max_copies_per_card }}</strong></li>
          <li>{{ __('entities.faction_decks.required_heroes') }}: <strong>{{ $deckConfig->required_heroes }}</strong></li>
        </ul>
    @else
      <p class="form-warning">{{ __('entities.faction_decks.no_deck_config') }}</p>
    @endif
    
    <x-form.fieldset :legend="__('entities.faction_decks.basic_info')">
      <div>
        <x-form.multilingual-input
          name="name"
          :label="__('entities.faction_decks.name')"
          :values="isset($factionDeck) ? $factionDeck->getTranslations('name') : []"
          required
        />

        <x-form.select
          name="faction_id"
          :label="__('entities.factions.singular')"
          :options="$factions->pluck('name', 'id')->toArray()"
          :selected="old('faction_id', $factionId ?? (isset($factionDeck) ? $factionDeck->faction_id : ''))"
          required
        />

        <x-form.checkbox
          name="is_published"
          :label="__('admin.published')"
          :checked="old('is_published', isset($factionDeck) ? $factionDeck->is_published : false)"
        />
      </div>

      <x-form.multilingual-wysiwyg
        name="description"
        :label="__('entities.faction_decks.description')"
        :values="isset($factionDeck) ? $factionDeck->getTranslations('description') : []"
      />

      <x-form.multilingual-wysiwyg
        name="epic_quote"
        :label="__('entities.faction_decks.epic_quote')"
        :values="isset($factionDeck) ? $factionDeck->getTranslations('epic_quote') : []"
      />
                      
      <x-form.image-upload
        name="icon"
        :label="__('entities.faction_decks.icon')"
        :current-image="isset($factionDeck) && $factionDeck->icon ? $factionDeck->getImageUrl() : null"
        :remove-name="isset($factionDeck) ? 'remove_icon' : null"
        />
    </x-form.fieldset>
    
    <x-form.fieldset :legend="__('entities.faction_decks.cards')">
      <x-form.deck-card-selector
        :cards="$allCards"
        :selectedCards="$selectedCards ?? []"
        :maxCopies="$deckConfig->max_copies_per_card"
        :minCards="$deckConfig->min_cards"
        :maxCards="$deckConfig->max_cards"
        name="cards"
      />
    </x-form.fieldset>

    <x-form.fieldset :legend="__('entities.faction_decks.heroes')">
      <x-form.deck-hero-selector
        :heroes="$allHeroes"
        :selectedHeroes="$selectedHeroes ?? []"
        :requiredHeroes="$deckConfig->required_heroes"
        name="heroes"
      />
    </x-form.fieldset>
  </x-form.card>
</form>