@props([
  'entity',
  'type' => 'card', // card, hero, faction, deck
  'viewRoute' => null,
])

<div class="entity-public-card" data-type="{{ $type }}">
  <div class="entity-public-card__actions">
    <x-pdf.add-button
      data-entity-type="{{ $type }}"
      data-entity-id="{{ $entity->id }}"
    >
    </x-pdf.add-button>
  </div>

  <a href="{{ $viewRoute }}" class="entity-public-card__link">
    <div class="entity-public-card__preview">
      <x-previews.preview-image :entity="$entity" :type="$type"/>
    </div>
  </a>
</div>