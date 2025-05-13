<div class="faction-view__heroes">
  @if($heroes->count() > 0)
    <div class="hero-grid">
      @foreach($heroes as $hero)
        <div class="hero-card">
          <div class="hero-card__header">
            <h3 class="hero-card__title">{{ $hero->name }}</h3>
            
            <div class="hero-card__badges">
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
          
          <div class="hero-card__content">
            <div class="hero-card__image-container">
              @if($hero->image)
                <img src="{{ $hero->getImageUrl() }}" alt="{{ $hero->name }}" class="hero-card__image">
              @else
                <div class="hero-card__image-placeholder">
                  <x-icon name="user" size="lg" />
                </div>
              @endif
            </div>
            
            <div class="hero-card__attributes">
              <div class="hero-card__attribute">
                <span class="hero-card__attribute-label">{{ __('heroes.attributes.agility') }}:</span>
                <span class="hero-card__attribute-value">{{ $hero->agility }}</span>
              </div>
              <div class="hero-card__attribute">
                <span class="hero-card__attribute-label">{{ __('heroes.attributes.mental') }}:</span>
                <span class="hero-card__attribute-value">{{ $hero->mental }}</span>
              </div>
              <div class="hero-card__attribute">
                <span class="hero-card__attribute-label">{{ __('heroes.attributes.will') }}:</span>
                <span class="hero-card__attribute-value">{{ $hero->will }}</span>
              </div>
              <div class="hero-card__attribute">
                <span class="hero-card__attribute-label">{{ __('heroes.attributes.strength') }}:</span>
                <span class="hero-card__attribute-value">{{ $hero->strength }}</span>
              </div>
              <div class="hero-card__attribute">
                <span class="hero-card__attribute-label">{{ __('heroes.attributes.armor') }}:</span>
                <span class="hero-card__attribute-value">{{ $hero->armor }}</span>
              </div>
              <div class="hero-card__attribute hero-card__attribute--total">
                <span class="hero-card__attribute-label">{{ __('heroes.attributes.health') }}:</span>
                <span class="hero-card__attribute-value">{{ $hero->health }}</span>
              </div>
            </div>
          </div>
          
          <div class="hero-card__footer">
            <x-action-button
              :href="route('admin.heroes.show', $hero)"
              icon="eye"
              variant="view"
              size="sm"
              :title="__('admin.view')"
            />
            <x-action-button
              :href="route('admin.heroes.edit', $hero)"
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
      {{ $heroes->appends(['tab' => 'heroes'])->links() }}
    </div>
  @else
    <div class="faction-view__empty">
      <p>{{ __('factions.no_heroes') }}</p>
      
      <x-button-link
        :href="route('admin.heroes.create', ['faction_id' => $faction->id])"
        variant="primary"
        icon="plus"
      >
        {{ __('heroes.create') }}
      </x-button-link>
    </div>
  @endif
</div>