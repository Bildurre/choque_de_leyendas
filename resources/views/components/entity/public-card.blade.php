@props([
  'entity',
  'type' => 'card', // card, hero, faction, deck
  'viewRoute' => null,
])

<div class="entity-public-card" data-type="{{ $type }}">
  <a href="{{ $viewRoute }}" class="entity-public-card__link">
    <div class="entity-public-card__preview">
      @if($type === 'hero')
        <x-previews.hero :hero="$entity" />
      @elseif($type === 'card')
        <x-previews.card :card="$entity" />
      @elseif($type === 'faction')
        <x-previews.faction :faction="$entity" />
      @elseif($type === 'deck')
        <x-previews.deck :deck="$entity" />
      @endif
    </div>
  </a>
  
  <div class="entity-public-card__actions">
    <button 
      type="button" 
      class="entity-public-card__action"
      data-entity-type="{{ $type }}"
      data-entity-id="{{ $entity->id }}"
    >
      <x-icon name="file-text" size="sm" />
      <span>
        @if($type === 'faction')
          {{ __('public.save_faction_pdf') }}
        @elseif($type === 'deck')
          {{ __('public.save_deck_pdf') }}
        @else
          {{ __('public.save_to_pdf') }}
        @endif
      </span>
    </button>
  </div>
</div>