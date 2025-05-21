<!-- resources/views/admin/faction-decks/_form.blade.php -->
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
  
  <!-- Elemento oculto para pasar la configuración a JavaScript -->
  <script type="application/json" id="deck-config-data">
    @json($deckConfig ?? null)
  </script>
  
  <x-form.card :submit_label="$submitLabel" :cancel_route="route('admin.faction-decks.index')">
    <!-- Información de configuración del mazo -->
    <div class="form-section">
      <h2 class="form-section-title">{{ __('entities.faction_decks.deck_config_info') }}</h2>
      
      <div class="form-info">
        <p>{{ __('entities.faction_decks.selected_game_mode') }}: <strong>{{ $gameMode->name ?? (isset($factionDeck) ? $factionDeck->gameMode->name : '') }}</strong></p>
        
        @if(isset($deckConfig))
          <div class="deck-config-details">
            <p>{{ __('entities.faction_decks.deck_config_details') }}:</p>
            <ul>
              <li>{{ __('entities.faction_decks.min_cards') }}: <strong>{{ $deckConfig->min_cards }}</strong></li>
              <li>{{ __('entities.faction_decks.max_cards') }}: <strong>{{ $deckConfig->max_cards }}</strong></li>
              <li>{{ __('entities.faction_decks.max_copies_per_card') }}: <strong>{{ $deckConfig->max_copies_per_card }}</strong></li>
              <li>{{ __('entities.faction_decks.max_copies_per_hero') }}: <strong>{{ $deckConfig->max_copies_per_hero }}</strong></li>
              <li>{{ __('entities.faction_decks.required_heroes') }}: <strong>{{ $deckConfig->required_heroes }}</strong></li>
            </ul>
          </div>
        @else
          <p class="form-warning">{{ __('entities.faction_decks.no_deck_config') }}</p>
        @endif
      </div>
    </div>
    
    <!-- Sección de información básica del mazo -->
    <div class="form-section">
      <h2 class="form-section-title">{{ __('entities.faction_decks.basic_info') }}</h2>
      
      <div class="form-grid">
        <!-- Campo para el nombre del mazo (multilingüe) -->
        <x-form.multilingual-input
          name="name"
          :label="__('entities.faction_decks.name')"
          :values="isset($factionDeck) ? $factionDeck->getTranslations('name') : []"
          required
        />
        
        <!-- Campo para seleccionar facción -->
        <x-form.select
          name="faction_id"
          :label="__('entities.factions.singular')"
          :options="$factions->pluck('name', 'id')->toArray()"
          :selected="old('faction_id', $factionId ?? (isset($factionDeck) ? $factionDeck->faction_id : ''))"
          required
        />
        
        <!-- Campo para subir icono -->
        <x-form.image-upload
          name="icon"
          :label="__('entities.faction_decks.icon')"
          :current-image="isset($factionDeck) && $factionDeck->icon ? $factionDeck->getImageUrl() : null"
          :remove-name="isset($factionDeck) ? 'remove_icon' : null"
        />
      </div>

      <x-form.checkbox
        name="is_published"
        :label="__('admin.published')"
        :checked="old('is_published', isset($factionDeck) ? $factionDeck->is_published : false)"
      />
    </div>
    
    <!-- Sección para las estadísticas del mazo (se usará en la validación en tiempo real) -->
    <div class="form-section">
      <div class="deck-stats">
        <div class="deck-stats__header">
          <h3 class="deck-stats__title">{{ __('entities.faction_decks.deck_stats') }}</h3>
        </div>
        
        <div class="deck-stats__grid">
          <div class="deck-stats__item">
            <span class="deck-stats__label">{{ __('entities.faction_decks.cards') }}:</span>
            <span class="deck-stats__value deck-stats__cards">0/{{ $deckConfig->min_cards }}-{{ $deckConfig->max_cards }}</span>
          </div>
          
          <div class="deck-stats__item">
            <span class="deck-stats__label">{{ __('entities.faction_decks.heroes') }}:</span>
            <span class="deck-stats__value deck-stats__heroes">0/{{ $deckConfig->required_heroes }}</span>
          </div>
        </div>
      </div>
    </div>
    
    <div class="form-section">
      <h2 class="form-section-title">{{ __('entities.faction_decks.cards') }}</h2>
      
      <x-form.entity-selector
        name="cards"
        :label="__('entities.faction_decks.select_cards')"
        :entities="$allCards"
        :selected="$selectedCards ?? []"
        entityType="card"
        secondaryField="cardType.name"
        :showCopies="true"
        :maxCopies="$deckConfig->max_copies_per_card"
        :detailsView="'admin.faction-decks._card-details'"
        data-faction-filter="true"
      />
    </div>
    
    <div class="form-section">
      <h2 class="form-section-title">{{ __('entities.faction_decks.heroes') }}</h2>
      
      <x-form.entity-selector
        name="heroes"
        :label="__('entities.faction_decks.select_heroes')"
        :entities="$allHeroes"
        :selected="$selectedHeroes ?? []"
        entityType="hero"
        secondaryField="heroClass.name"
        :showCopies="true"
        :maxCopies="$deckConfig->max_copies_per_hero"
        :detailsView="'admin.faction-decks._hero-details'"
        data-faction-filter="true"
      />
    </div>
  </x-form.card>
</form>

<script>
  // Datos para filtrado por facción
  window.entityData = {
    cards: @json($allCards->map(function($card) {
      return [
        'id' => $card->id,
        'faction_id' => $card->faction_id
      ];
    })),
    heroes: @json($allHeroes->map(function($hero) {
      return [
        'id' => $hero->id,
        'faction_id' => $hero->faction_id
      ];
    }))
  };
</script>