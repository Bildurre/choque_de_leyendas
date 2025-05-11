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
        <a href="{{ $viewRoute }}" class="action-button action-button--view" title="{{ __('admin.view') }}" target="_blank">
          <x-icon name="eye" size="sm" class="action-button__icon" />
        </a>
      @endif
      
      @if($editRoute)
        <a href="{{ $editRoute }}" class="action-button action-button--edit" title="{{ __('admin.edit') }}">
          <x-icon name="edit" size="sm" class="action-button__icon" />
        </a>
      @endif
      
      @if($restoreRoute)
        <form action="{{ $restoreRoute }}" method="POST" class="action-button-form">
          @csrf
          <button 
            type="submit" 
            class="action-button action-button--restore"
            title="{{ __('admin.restore') }}"
          >
            <x-icon name="refresh" size="sm" class="action-button__icon" />
          </button>
        </form>
      @endif
      
      @if($deleteRoute)
        <form action="{{ $deleteRoute }}" method="POST" class="action-button-form">
          @csrf
          @method('DELETE')
          <button 
            type="submit" 
            class="action-button action-button--delete"
            data-confirm-message="{{ $confirmMessage }}"
            title="{{ __('admin.delete') }}"
          >
            <x-icon name="trash" size="sm" class="action-button__icon" />
          </button>
        </form>
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