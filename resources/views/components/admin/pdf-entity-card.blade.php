@props([
  'entity',
  'type',
  'generateUrl',
  'subtitle' => null
])

<div class="pdf-entity-card" data-entity-id="{{ $entity->id }}" data-entity-type="{{ $type }}">
  <div class="pdf-entity-card__header">
    <h4 class="pdf-entity-card__name">{{ $entity->name }}</h4>
    @if($subtitle)
      <p class="pdf-entity-card__subtitle">{{ $subtitle }}</p>
    @endif
  </div>
  
  <div class="pdf-entity-card__stats">
    @if($type === 'faction')
      <div class="pdf-entity-card__stat">
        <x-icon name="users" />
        <span>{{ $entity->heroes->count() }} {{ __('admin.heroes') }}</span>
      </div>
      <div class="pdf-entity-card__stat">
        <x-icon name="layers" />
        <span>{{ $entity->cards->count() }} {{ __('admin.cards') }}</span>
      </div>
    @elseif($type === 'deck')
      <div class="pdf-entity-card__stat">
        <x-icon name="users" />
        <span>{{ $entity->heroes->sum('pivot.copies') }} {{ __('admin.heroes') }}</span>
      </div>
      <div class="pdf-entity-card__stat">
        <x-icon name="layers" />
        <span>{{ $entity->cards->sum('pivot.copies') }} {{ __('admin.cards') }}</span>
      </div>
    @endif
  </div>
  
  <div class="pdf-entity-card__actions">
    <x-button
      type="button"
      variant="primary"
      icon="file-text"
      size="sm"
      class="generate-pdf-btn"
      data-url="{{ $generateUrl }}"
      data-entity-type="{{ $type }}"
      data-entity-id="{{ $entity->id }}"
    >
      {{ __('admin.pdf_export.generate') }}
    </x-button>
  </div>
  
  <div class="pdf-entity-card__status" style="display: none;">
    <div class="pdf-entity-card__spinner">
      <x-icon name="loader" class="animate-spin" />
    </div>
    <span class="pdf-entity-card__status-text">{{ __('admin.pdf_export.generating') }}</span>
  </div>
</div>