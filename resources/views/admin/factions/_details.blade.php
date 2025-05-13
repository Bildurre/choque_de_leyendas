<div class="faction-view__details">
  <div class="faction-view__header">
    <div class="faction-view__color-sample" style="background-color: {{ $faction->color }};">
      <span class="faction-view__color-value {{ $faction->text_is_dark ? 'faction-view__color-value--dark' : 'faction-view__color-value--light' }}">
        {{ $faction->color }}
      </span>
    </div>
  </div>
  
  <div class="faction-view__content">
    <div class="faction-view__main">
      <div class="faction-view__icon-container">
        @if($faction->icon)
          <img src="{{ $faction->getImageUrl() }}" alt="{{ $faction->name }}" class="faction-view__icon">
        @else
          <div class="faction-view__icon-placeholder">
            <div class="faction-view__icon-placeholder-icon">
              <x-icon name="image" size="xl" />
            </div>
            <p>{{ __('factions.no_icon') }}</p>
          </div>
        @endif
      </div>
      
      <div class="faction-view__info">
        <div class="faction-view__section">
          <h2 class="faction-view__section-title">{{ __('factions.details') }}</h2>
          
          <div class="faction-view__info-grid">
            <!-- Stats -->
            <div class="faction-view__info-item">
              <span class="faction-view__info-label">{{ __('heroes.plural') }}:</span>
              <span class="faction-view__info-value">
                {{ $faction->heroes_count }}
              </span>
            </div>
            
            <div class="faction-view__info-item">
              <span class="faction-view__info-label">{{ __('cards.plural') }}:</span>
              <span class="faction-view__info-value">
                {{ $faction->cards_count }}
              </span>
            </div>
            
            <div class="faction-view__info-item">
              <span class="faction-view__info-label">{{ __('faction_decks.plural') }}:</span>
              <span class="faction-view__info-value">
                {{ $faction->faction_decks_count }}
              </span>
            </div>
            
            <div class="faction-view__info-item">
              <span class="faction-view__info-label">{{ __('factions.color') }}:</span>
              <span class="faction-view__info-value">
                <span class="faction-view__color-chip" style="background-color: {{ $faction->color }};"></span>
                {{ $faction->color }}
              </span>
            </div>
            
            <div class="faction-view__info-item">
              <span class="faction-view__info-label">{{ __('factions.text_color') }}:</span>
              <span class="faction-view__info-value">
                {{ $faction->text_is_dark ? __('factions.text_dark') : __('factions.text_light') }}
              </span>
            </div>
          </div>
        </div>
        
        @if($faction->lore_text)
          <div class="faction-view__section">
            <h2 class="faction-view__section-title">{{ __('factions.lore_text') }}</h2>
            <div class="faction-view__text-content">
              {!! $faction->lore_text !!}
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>