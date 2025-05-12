<x-admin-layout>
  <div class="page-header">
    <div class="page-header__content">
      <h1 class="page-title">{{ $faction->name }}</h1>
      
      <div class="page-header__actions">
        <x-button-link
          :href="route('admin.factions.index')"
          variant="secondary"
          icon="arrow-left"
        >
          {{ __('admin.back_to_list') }}
        </x-button-link>
        
        <x-button-link
          :href="route('admin.factions.edit', $faction)"
          variant="primary"
          icon="edit"
        >
          {{ __('admin.edit') }}
        </x-button-link>
      </div>
    </div>
  </div>
  
  <div class="page-content">
    <div class="faction-view">
      <div class="faction-view__container">
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
            
            <div class="faction-view__details">
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

      <div class="faction-view__related">
        <x-tabs>
          <x-slot:header>
            <x-tab-item 
              id="heroes" 
              :active="true" 
              :href="'#heroes'"
              icon="users"
              :count="$faction->heroes_count"
            >
              {{ __('heroes.plural') }}
            </x-tab-item>
            
            <x-tab-item 
              id="cards" 
              :active="false" 
              :href="'#cards'"
              icon="layers"
              :count="$faction->cards_count"
            >
              {{ __('cards.plural') }}
            </x-tab-item>
          </x-slot:header>
          
          <x-slot:content>
            <div id="heroes-panel" class="tabs__panel tabs__panel--active">
              @if($faction->heroes_count > 0)
                <div class="faction-heroes-grid">
                  @foreach($faction->heroes as $hero)
                    <div class="faction-hero-card">
                      <div class="faction-hero-card__header">
                        <h3 class="faction-hero-card__name">{{ $hero->name }}</h3>
                      </div>
                      
                      @if($hero->image)
                        <div class="faction-hero-card__image">
                          <img src="{{ $hero->getImageUrl() }}" alt="{{ $hero->name }}">
                        </div>
                      @endif
                      
                      <div class="faction-hero-card__details">
                        <div class="faction-hero-card__attribute">
                          <span class="faction-hero-card__attribute-label">{{ __('heroes.attributes.agility') }}</span>
                          <span class="faction-hero-card__attribute-value">{{ $hero->agility }}</span>
                        </div>
                        <div class="faction-hero-card__attribute">
                          <span class="faction-hero-card__attribute-label">{{ __('heroes.attributes.mental') }}</span>
                          <span class="faction-hero-card__attribute-value">{{ $hero->mental }}</span>
                        </div>
                        <div class="faction-hero-card__attribute">
                          <span class="faction-hero-card__attribute-label">{{ __('heroes.attributes.will') }}</span>
                          <span class="faction-hero-card__attribute-value">{{ $hero->will }}</span>
                        </div>
                        <div class="faction-hero-card__attribute">
                          <span class="faction-hero-card__attribute-label">{{ __('heroes.attributes.strength') }}</span>
                          <span class="faction-hero-card__attribute-value">{{ $hero->strength }}</span>
                        </div>
                        <div class="faction-hero-card__attribute">
                          <span class="faction-hero-card__attribute-label">{{ __('heroes.attributes.armor') }}</span>
                          <span class="faction-hero-card__attribute-value">{{ $hero->armor }}</span>
                        </div>
                      </div>
                      
                      <div class="faction-hero-card__footer">
                        <x-button-link
                          :href="route('admin.heroes.show', $hero)"
                          variant="secondary"
                          size="sm"
                        >
                          {{ __('admin.view') }}
                        </x-button-link>
                      </div>
                    </div>
                  @endforeach
                </div>
              @else
                <div class="faction-view__empty">
                  {{ __('factions.no_heroes') }}
                </div>
              @endif
            </div>
            
            <div id="cards-panel" class="tabs__panel">
              @if($faction->cards_count > 0)
                <div class="faction-cards-grid">
                  @foreach($faction->cards as $card)
                    <div class="faction-card-card">
                      <div class="faction-card-card__header">
                        <h3 class="faction-card-card__name">{{ $card->name }}</h3>
                        
                        @if($card->cardType)
                          <div class="faction-card-card__type">
                            {{ $card->cardType->name }}
                          </div>
                        @endif
                      </div>
                      
                      @if($card->image)
                        <div class="faction-card-card__image">
                          <img src="{{ $card->getImageUrl() }}" alt="{{ $card->name }}">
                        </div>
                      @endif
                      
                      <div class="faction-card-card__content">
                        @if($card->effect)
                          <div class="faction-card-card__effect">
                            {{ strip_tags($card->effect) }}
                          </div>
                        @endif
                      </div>
                      
                      <div class="faction-card-card__footer">
                        <x-button-link
                          :href="route('admin.cards.show', $card)"
                          variant="secondary"
                          size="sm"
                        >
                          {{ __('admin.view') }}
                        </x-button-link>
                      </div>
                    </div>
                  @endforeach
                </div>
              @else
                <div class="faction-view__empty">
                  {{ __('factions.no_cards') }}
                </div>
              @endif
            </div>
          </x-slot:content>
        </x-tabs>
      </div>
    </div>
  </div>
</x-admin-layout>