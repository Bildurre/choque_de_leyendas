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
    @if($type === 'faction' || $type === 'deck')
      {{-- Botón para agregar a colección --}}
      <button 
        type="button" 
        class="entity-public-card__action entity-public-card__action--add"
        data-entity-type="{{ $type }}"
        data-entity-id="{{ $entity->id }}"
      >
        <x-icon name="plus" size="sm" />
        <span>{{ __('public.add_to_collection') }}</span>
      </button>
      
      {{-- Botón para descarga directa de PDF --}}
      <a 
        href="{{ $type === 'faction' 
          ? route('public.print-collection.faction-pdf', $entity) 
          : route('public.print-collection.deck-pdf', $entity) }}"
        class="entity-public-card__action entity-public-card__action--download"
        target="_blank"
      >
        <x-icon name="download" size="sm" />
        <span>{{ __('public.download_pdf') }}</span>
      </a>
    @else
      {{-- Para heroes y cartas, solo botón de agregar --}}
      <button 
        type="button" 
        class="entity-public-card__action"
        data-entity-type="{{ $type }}"
        data-entity-id="{{ $entity->id }}"
      >
        <x-icon name="file-text" size="sm" />
        <span>{{ __('public.save_to_pdf') }}</span>
      </button>
    @endif
  </div>
</div>