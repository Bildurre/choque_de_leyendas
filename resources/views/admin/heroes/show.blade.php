<x-admin-layout>
  <div class="page-header">
    <div class="page-header__content">
      <h1 class="page-title">{{ $hero->name }}</h1>
      
      <div class="page-header__actions">
        <x-button-link
          :href="route('admin.heroes.index')"
          variant="secondary"
          icon="arrow-left"
        >
          {{ __('admin.back_to_list') }}
        </x-button-link>
        
        <x-button-link
          :href="route('admin.heroes.edit', $hero)"
          variant="primary"
          icon="edit"
        >
          {{ __('admin.edit') }}
        </x-button-link>
      </div>
    </div>
  </div>
  
  <div class="page-content">
    <div class="hero-view">
      <div class="hero-view__container">
        <div class="hero-view__header">
          <div class="hero-view__badges">
            @if($hero->faction)
              <x-badge 
                :variant="$hero->faction->text_is_dark ? 'light' : 'dark'" 
                style="background-color: {{ $hero->faction->color }};"
              >
                {{ $hero->faction->name }}
              </x-badge>
            @endif
            
            @if($hero->heroRace)
              <x-badge variant="info">
                {{ $hero->heroRace->name }}
              </x-badge>
            @endif
            
            @if($hero->heroClass)
              <x-badge variant="primary">
                {{ $hero->heroClass->name }}
              </x-badge>
            @endif
            
            <x-badge variant="{{ $hero->gender === 'male' ? 'success' : 'warning' }}">
              {{ __('heroes.genders.' . $hero->gender) }}
            </x-badge>
          </div>
        </div>
        
        <div class="hero-view__content">
          <div class="hero-view__main">
            <div class="hero-view__image-container">
              @if($hero->image)
                <img src="{{ $hero->getImageUrl() }}" alt="{{ $hero->name }}" class="hero-view__image">
              @else
                <div class="hero-view__image-placeholder">
                  <div class="hero-view__image-placeholder-icon">
                    <x-icon name="image" size="xl" />
                  </div>
                  <p>{{ __('heroes.no_image') }}</p>
                </div>
              @endif
            </div>
            
            <div class="hero-view__details">
              <div class="hero-view__section">
                <h2 class="hero-view__section-title">{{ __('heroes.attributes') }}</h2>
                
                <div class="hero-view__attribute-grid">
                  <div class="hero-view__attribute">
                    <span class="hero-view__attribute-label">{{ __('heroes.attributes.agility') }}:</span>
                    <span class="hero-view__attribute-value">{{ $hero->agility }}</span>
                  </div>
                  
                  <div class="hero-view__attribute">
                    <span class="hero-view__attribute-label">{{ __('heroes.attributes.mental') }}:</span>
                    <span class="hero-view__attribute-value">{{ $hero->mental }}</span>
                  </div>
                  
                  <div class="hero-view__attribute">
                    <span class="hero-view__attribute-label">{{ __('heroes.attributes.will') }}:</span>
                    <span class="hero-view__attribute-value">{{ $hero->will }}</span>
                  </div>
                  
                  <div class="hero-view__attribute">
                    <span class="hero-view__attribute-label">{{ __('heroes.attributes.strength') }}:</span>
                    <span class="hero-view__attribute-value">{{ $hero->strength }}</span>
                  </div>
                  
                  <div class="hero-view__attribute">
                    <span class="hero-view__attribute-label">{{ __('heroes.attributes.armor') }}:</span>
                    <span class="hero-view__attribute-value">{{ $hero->armor }}</span>
                  </div>
                  
                  <div class="hero-view__attribute hero-view__attribute--total">
                    <span class="hero-view__attribute-label">{{ __('heroes.attributes.health') }}:</span>
                    <span class="hero-view__attribute-value">{{ $hero->health }}</span>
                  </div>
                  
                  <div class="hero-view__attribute hero-view__attribute--total">
                    <span class="hero-view__attribute-label">{{ __('heroes.total_attributes') }}:</span>
                    <span class="hero-view__attribute-value">{{ $hero->total_attributes }}</span>
                  </div>
                </div>
              </div>
              
              @if($hero->passive_name)
                <div class="hero-view__section">
                  <h2 class="hero-view__section-title">{{ __('heroes.passive_ability') }}</h2>
                  
                  <div class="hero-view__passive">
                    <h3 class="hero-view__passive-name">{{ $hero->passive_name }}</h3>
                    
                    @if($hero->passive_description)
                      <div class="hero-view__passive-description">
                        {!! $hero->passive_description !!}
                      </div>
                    @endif
                  </div>
                </div>
              @endif
              
              @if($hero->lore_text)
                <div class="hero-view__section">
                  <h2 class="hero-view__section-title">{{ __('heroes.lore_text') }}</h2>
                  <div class="hero-view__text-content">
                    {!! $hero->lore_text !!}
                  </div>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>

      @if($hero->heroAbilities->count() > 0)
        <div class="hero-view__abilities">
          <h2 class="hero-view__section-title">{{ __('hero_abilities.plural') }}</h2>
          
          <div class="hero-abilities-grid">
            @foreach($hero->heroAbilities as $ability)
              <div class="hero-ability-card">
                <div class="hero-ability-card__header">
                  <h3 class="hero-ability-card__name">{{ $ability->name }}</h3>
                  
                  <div class="hero-ability-card__cost">
                    @if($ability->cost)
                      <div class="hero-ability-card__cost-icons">
                        <x-cost-display :cost="$ability->cost" />
                      </div>
                    @endif
                  </div>
                </div>
                
                <div class="hero-ability-card__details">
                  @if($ability->attackRange)
                    <div class="hero-ability-card__attribute">
                      <span class="hero-ability-card__attribute-label">{{ __('attack_ranges.singular') }}:</span>
                      <span class="hero-ability-card__attribute-value">{{ $ability->attackRange->name }}</span>
                    </div>
                  @endif
                  
                  @if($ability->attackSubtype)
                    <div class="hero-ability-card__attribute">
                      <span class="hero-ability-card__attribute-label">{{ __('attack_subtypes.singular') }}:</span>
                      <span class="hero-ability-card__attribute-value {{ $ability->attackSubtype->type }}">
                        {{ $ability->attackSubtype->name }}
                      </span>
                    </div>
                  @endif
                  
                  @if($ability->area)
                    <div class="hero-ability-card__attribute">
                      <span class="hero-ability-card__attribute-label">{{ __('hero_abilities.type') }}:</span>
                      <span class="hero-ability-card__attribute-value">
                        {{ __('hero_abilities.area') }}
                      </span>
                    </div>
                  @endif
                </div>
                
                @if($ability->description)
                  <div class="hero-ability-card__description">
                    {!! $ability->description !!}
                  </div>
                @endif
                
                <div class="hero-ability-card__footer">
                  <x-button-link
                    :href="route('admin.hero-abilities.edit', $ability)"
                    variant="secondary"
                    size="sm"
                  >
                    {{ __('admin.view') }}
                  </x-button-link>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @endif
    </div>
  </div>
</x-admin-layout>