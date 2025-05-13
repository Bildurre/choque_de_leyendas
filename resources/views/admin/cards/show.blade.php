<x-admin-layout>
  <div class="page-header">
    <div class="page-header__content">
      <h1 class="page-title">{{ $card->name }}</h1>
      
      <div class="page-header__actions">
        <x-button-link
          :href="route('admin.cards.index')"
          variant="secondary"
          icon="arrow-left"
        >
          {{ __('admin.back_to_list') }}
        </x-button-link>
        
        <x-button-link
          :href="route('admin.cards.edit', $card)"
          variant="primary"
          icon="edit"
        >
          {{ __('admin.edit') }}
        </x-button-link>
      </div>
    </div>
  </div>
  
  <div class="page-content">
    <div class="card-view">
      <div class="card-view__container">
        <div class="card-view__header">
          <div class="card-view__badges">
            @if($card->cardType)
              <x-badge variant="primary">
                {{ $card->cardType->name }}
              </x-badge>
            @endif
            
            @if($card->faction)
              <x-badge 
                :variant="$card->faction->text_is_dark ? 'light' : 'dark'" 
                style="background-color: {{ $card->faction->color }};"
              >
                {{ $card->faction->name }}
              </x-badge>
            @endif
            
            @if($card->cost)
              <div class="badge-with-icons">
                <span class="badge-with-icons__cost">
                  <x-cost-display :cost="$card->cost" />
                </span>
              </div>
            @endif
          </div>
        </div>
        
        <div class="card-view__content">
          <div class="card-view__main">
            <div class="card-view__image-container">
              @if($card->image)
                <img src="{{ $card->getImageUrl() }}" alt="{{ $card->name }}" class="card-view__image">
              @else
                <div class="card-view__image-placeholder">
                  <div class="card-view__image-placeholder-icon">
                    <x-icon name="image" size="xl" />
                  </div>
                  <p>{{ __('cards.no_image') }}</p>
                </div>
              @endif
            </div>
            
            <div class="card-view__details">
              <div class="card-view__section">
                <h2 class="card-view__section-title">{{ __('cards.details') }}</h2>
                
                <div class="card-view__info-grid">
                  <!-- Card Type -->
                  <div class="card-view__info-item">
                    <span class="card-view__info-label">{{ __('card_types.singular') }}:</span>
                    <span class="card-view__info-value">
                      {{ $card->cardType ? $card->cardType->name : __('admin.none') }}
                    </span>
                  </div>
                  
                  <!-- Faction -->
                  <div class="card-view__info-item">
                    <span class="card-view__info-label">{{ __('factions.singular') }}:</span>
                    <span class="card-view__info-value">
                      {{ $card->faction ? $card->faction->name : __('admin.none') }}
                    </span>
                  </div>
                  
                  <!-- Equipment Type -->
                  <div class="card-view__info-item">
                    <span class="card-view__info-label">{{ __('equipment_types.singular') }}:</span>
                    <span class="card-view__info-value">
                      {{ $card->equipmentType ? $card->equipmentType->name : __('admin.none') }}
                    </span>
                  </div>
                  
                  <!-- Hands (if applicable) -->
                  @if($card->equipmentType && $card->equipmentType->category === 'weapon' && $card->hands)
                    <div class="card-view__info-item">
                      <span class="card-view__info-label">{{ __('cards.hands') }}:</span>
                      <span class="card-view__info-value">
                        {{ $card->hands }} {{ trans_choice('cards.hands_count', $card->hands) }}
                      </span>
                    </div>
                  @endif
                  
                  <!-- Attack Range -->
                  <div class="card-view__info-item">
                    <span class="card-view__info-label">{{ __('attack_ranges.singular') }}:</span>
                    <span class="card-view__info-value">
                      {{ $card->attackRange ? $card->attackRange->name : __('admin.none') }}
                    </span>
                  </div>
                  
                  <!-- Attack Subtype -->
                  <div class="card-view__info-item">
                    <span class="card-view__info-label">{{ __('attack_subtypes.singular') }}:</span>
                    <span class="card-view__info-value">
                      {{ $card->attackSubtype ? $card->attackSubtype->name : __('admin.none') }}
                    </span>
                  </div>
                  
                  <!-- Area Attack -->
                  <div class="card-view__info-item">
                    <span class="card-view__info-label">{{ __('cards.area') }}:</span>
                    <span class="card-view__info-value">
                      {{ $card->area ? __('admin.yes') : __('admin.no') }}
                    </span>
                  </div>
                  
                  <!-- Hero Ability -->
                  <div class="card-view__info-item">
                    <span class="card-view__info-label">{{ __('hero_abilities.singular') }}:</span>
                    <span class="card-view__info-value">
                      {{ $card->heroAbility ? $card->heroAbility->name : __('admin.none') }}
                    </span>
                  </div>
                  
                  <!-- Cost -->
                  <div class="card-view__info-item">
                    <span class="card-view__info-label">{{ __('cards.cost') }}:</span>
                    <span class="card-view__info-value">
                      @if($card->cost)
                        <div class="card-view__dice-cost">
                          <x-cost-display :cost="$card->cost" />
                        </div>
                      @else
                        {{ __('admin.none') }}
                      @endif
                    </span>
                  </div>
                </div>
              </div>
              
              @if($card->lore_text)
                <div class="card-view__section">
                  <h2 class="card-view__section-title">{{ __('cards.lore_text') }}</h2>
                  <div class="card-view__text-content">
                    {!! $card->lore_text !!}
                  </div>
                </div>
              @endif
              
              @if($card->effect)
                <div class="card-view__section">
                  <h2 class="card-view__section-title">{{ __('cards.effect') }}</h2>
                  <div class="card-view__text-content">
                    {!! $card->effect !!}
                  </div>
                </div>
              @endif
              
              @if($card->restriction)
                <div class="card-view__section">
                  <h2 class="card-view__section-title">{{ __('cards.restriction') }}</h2>
                  <div class="card-view__text-content">
                    {!! $card->restriction !!}
                  </div>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-admin-layout>