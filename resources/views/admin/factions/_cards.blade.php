<div class="faction-view__cards">
  @if($cards->count() > 0)
    <div class="card-grid">
      @foreach($cards as $card)
        <div class="card-item">
          <div class="card-item__header">
            <h3 class="card-item__title">{{ $card->name }}</h3>
            
            @if($card->cardType)
              <div class="card-item__type">
                <x-badge variant="secondary">
                  {{ $card->cardType->name }}
                </x-badge>
              </div>
            @endif
          </div>
          
          <div class="card-item__content">
            @if($card->image)
              <div class="card-item__image-container">
                <img src="{{ $card->getImageUrl() }}" alt="{{ $card->name }}" class="card-item__image">
              </div>
            @endif
            
            @if($card->effect)
              <div class="card-item__effect">
                {{ strip_tags($card->effect) }}
              </div>
            @endif
            
            @if($card->cost)
              <div class="card-item__cost">
                <x-cost-display :cost="$card->cost" />
              </div>
            @endif
          </div>
          
          <div class="card-item__footer">
            <x-action-button
              :href="route('admin.cards.show', $card)"
              icon="eye"
              variant="view"
              size="sm"
              :title="__('admin.view')"
            />
            <x-action-button
              :href="route('admin.cards.edit', $card)"
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
      {{ $cards->appends(['tab' => 'cards'])->links() }}
    </div>
  @else
    <div class="faction-view__empty">
      <p>{{ __('entities.factions.no_cards') }}</p>
      
      <x-button-link
        :href="route('admin.cards.create', ['faction_id' => $faction->id])"
        variant="primary"
        icon="plus"
      >
        {{ __('entities.cards.create') }}
      </x-button-link>
    </div>
  @endif
</div>