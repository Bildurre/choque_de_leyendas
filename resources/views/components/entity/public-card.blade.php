@props([
  'entity',
  'type' => 'card', // card, hero, faction, deck
  'viewRoute' => null,
])

<div class="entity-public-card" data-type="{{ $type }}">
  <div class="entity-public-card__actions">
    @if($type === 'faction' || $type === 'deck')
      {{-- Botón para agregar a colección --}}
      <button 
        type="button" 
        class="entity-public-card__action entity-public-card__action--add"
        data-entity-type="{{ $type }}"
        data-entity-id="{{ $entity->id }}"
      >
        <x-icon name="pdf-add" size="sm" />
        <span>{{ __('public.collection.add_to_pdf') }}</span>
      </button>
      
      {{-- Botón para descarga directa de PDF --}}
      <a 
        href="{{ $type === 'faction' 
          ? route('public.print-collection.faction-pdf', $entity) 
          : route('public.print-collection.deck-pdf', $entity) }}"
        class="entity-public-card__action entity-public-card__action--download"
        target="_blank"
      >
        <x-icon name="pdf-download" size="sm" />
        <span>
          @if($type === 'deck')
            {{ __('public.collection.deck_pdf') }}
          @elseif($type === 'faction')
            {{ __('public.collection.faction_pdf') }}
          @endif
        </span>
      </a>
    @else
      {{-- Para heroes y cartas, solo botón de agregar --}}
      <button 
        type="button" 
        class="entity-public-card__action"
        data-entity-type="{{ $type }}"
        data-entity-id="{{ $entity->id }}"
      >
        <x-icon name="pdf-add" size="sm" />
        <span>{{ __('public.collection.add_to_pdf') }}</span>
      </button>
    @endif
  </div>

  <a href="{{ $viewRoute }}" class="entity-public-card__link">
    <div class="entity-public-card__preview">
      <x-previews.preview-image :entity="$entity" :type="$type"/>
    </div>
  </a>
</div>