@props([
  'entity',
  'type' => 'card', // card, hero, faction, deck
  'viewRoute' => null,
])

<div class="entity-public-card" data-type="{{ $type }}">
  <div class="entity-public-card__actions">
    {{-- Botón único para agregar a colección (para todos los tipos) --}}
    <button 
      type="button" 
      class="entity-public-card__action {{ in_array($type, ['faction', 'deck']) ? 'entity-public-card__action--add' : '' }}"
      data-entity-type="{{ $type }}"
      data-entity-id="{{ $entity->id }}"
    >
      <x-icon name="pdf-add" size="sm" />
      <span>{{ __('public.collection.add_to_pdf') }}</span>
    </button>
  </div>

  <a href="{{ $viewRoute }}" class="entity-public-card__link">
    <div class="entity-public-card__preview">
      <x-previews.preview-image :entity="$entity" :type="$type"/>
    </div>
  </a>
</div>