@props([
  'title',
  'editRoute' => null,
  'deleteRoute' => null,
  'restoreRoute' => null,
  'viewRoute' => null,
  'confirmMessage' => __('admin.confirm_delete')
])

<div {{ $attributes->merge(['class' => 'entity-list-card']) }}>
  <div class="entity-list-card__header">
    <h3 class="entity-list-card__title">{{ $title }}</h3>
    
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