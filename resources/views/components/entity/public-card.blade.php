@props([
  'entity',
  'type' => 'card', // card, hero, faction, deck
  'viewRoute' => null,
])

<div class="entity-public-card" data-type="{{ $type }}">
    <div class="entity-public-card__actions">
      @if(in_array($type, ['deck', 'faction']))
        <x-pdf.download-button
          :entity="$entity"
          :entityType="$type"
        />
      @else
        <x-pdf.add-button
          :entityType="$type"
          :entityId="$entity->id"
        />
      @endif
    </div>
  <a href="{{ $viewRoute }}" class="entity-public-card__link">
    <div class="entity-public-card__preview">
      <x-previews.preview-image :entity="$entity" :type="$type"/>
    </div>
  </a>
</div>