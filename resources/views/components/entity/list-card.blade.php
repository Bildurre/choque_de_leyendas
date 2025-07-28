@props([
  'title',
  'editRoute' => null,
  'deleteRoute' => null,
  'restoreRoute' => null,
  'viewRoute' => null,
  'togglePublishedRoute' => null,
  'isPublished' => false,
  'confirmMessage' => __('admin.confirm_delete'),
  'isReorderable' => false
])

<div {{ $attributes->merge(['class' => 'entity-list-card' . ($isReorderable ? ' entity-list-card--reorderable' : '')]) }}>
  @if($isReorderable)
    <div class="entity-list-card__reorder-controls">
      <div class="entity-list-card__handle" title="{{ __('admin.drag_to_reorder') }}">
        <x-icon name="grip" />
      </div>
      <div class="entity-list-card__arrow-buttons">
        <button 
          type="button" 
          class="entity-list-card__move-up" 
          title="{{ __('admin.move_up') }}"
          aria-label="{{ __('admin.move_up') }}"
        >
          <x-icon name="chevron-up" />
        </button>
        <button 
          type="button" 
          class="entity-list-card__move-down" 
          title="{{ __('admin.move_down') }}"
          aria-label="{{ __('admin.move_down') }}"
        >
          <x-icon name="chevron-down" />
        </button>
      </div>
      <div class="entity-list-card__order-indicator" style="display: none;">
        <span class="entity-list-card__order-current"></span>
        <x-icon name="arrow-right" class="entity-list-card__order-arrow" />
        <span class="entity-list-card__order-new"></span>
      </div>
    </div>
  @endif
  
  <div class="entity-list-card__main">
    <div class="entity-list-card__header">
      
      <div class="entity-list-card__actions">
        @if($viewRoute)
          <x-action-button
            :href="$viewRoute"
            icon="eye"
            variant="view"
            size="sm"
            :title="__('admin.view')"
            target="_blank"
          />
        @endif
        
        @if($editRoute)
          <x-action-button
            :href="$editRoute"
            icon="edit"
            variant="edit"
            size="sm"
            :title="__('admin.edit')"
          />
        @endif
        
        @if($togglePublishedRoute)
          <x-action-button
            :route="$togglePublishedRoute"
            :icon="$isPublished ? 'globe-slash' : 'globe'"
            :variant="$isPublished ? 'unpublish' : 'publish'"
            size="sm"
            method="POST"
            :title="$isPublished ? __('admin.unpublish') : __('admin.publish')"
          />
        @endif
        
        @if($restoreRoute)
          <x-action-button
            :route="$restoreRoute"
            icon="refresh"
            variant="restore"
            size="sm"
            method="POST"
            :title="__('admin.restore')"
          />
        @endif
        
        @if($deleteRoute)
          <x-action-button
            :route="$deleteRoute"
            icon="trash"
            variant="delete"
            size="sm"
            method="DELETE"
            :confirm-message="$confirmMessage"
            :title="__('admin.delete')"
          />
        @endif
        
        {{ $actions ?? '' }}
      </div>
      
      <h3 class="entity-list-card__title">{{ $title }}</h3>
    </div>
    
    <div class="entity-list-card__content">
      @if(isset($badges))
        <div class="entity-list-card__badges">
          {{ $badges }}
        </div>
      @endif
      
      @if(isset($meta))
        <div class="entity-list-card__meta">
          {{ $meta }}
        </div>
      @endif
      
      {{ $slot }}
    </div>
  </div>
</div>