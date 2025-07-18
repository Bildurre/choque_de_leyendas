@props([
  'entity',
  'type' => 'card', // card, hero
  'copies' => 1,
  'viewRoute' => null,
])

<div class="entity-collection-card" data-type="{{ $type }}" data-entity-id="{{ $entity->id }}">
  <div class="entity-collection-card__controls">
    <x-input
      type="number" 
      name="copies_{{ $type }}_{{ $entity->id }}"
      :value="$copies"
      min="1" 
      max="10"
      data-copies-input
      data-entity-type="{{ $type }}"
      data-entity-id="{{ $entity->id }}"
      class="entity-collection-card__copies-input"
      :placeholder="__('pdf.collection.copies')"
    />
    
    <x-action-button
      variant="delete"
      size="sm"
      icon="trash"
      class="entity-collection-card__remove"
      data-remove-item
      data-entity-type="{{ $type }}"
      data-entity-id="{{ $entity->id }}"
      :title="__('pdf.collection.remove_from_collection')"
    />
  </div>
  
  <div class="entity-collection-card__preview">
    <x-previews.preview-image :entity="$entity" :type="$type"/>
  </div>
  
  @if($viewRoute)
    <a href="{{ $viewRoute }}" class="entity-collection-card__link" target="_blank">
      <x-icon name="external-link" size="sm" />
    </a>
  @endif
</div>