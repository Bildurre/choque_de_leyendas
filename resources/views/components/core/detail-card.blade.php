@props(['title', 'accentColor' => null, 'model' => null, 'editRoute' => null, 'deleteRoute' => null, 'confirmAttribute' => 'name'])

<div class="detail-card">
  <div class="detail-card__header" @if($accentColor) style="border-color: {{ $accentColor }}" @endif>
    @if($editRoute || $deleteRoute)
      <div class="detail-card__actions">
        @if($editRoute)
          <x-admin.action-button 
            variant="edit" 
            :route="$editRoute"
            icon="edit" 
          />
        @endif
        
        @if($deleteRoute && $model)
          <x-admin.action-button 
            variant="delete" 
            :route="$deleteRoute"
            method="DELETE" 
            icon="delete" 
            confirm="true" 
            :confirmAttribute="$confirmAttribute"
            :confirmValue="$model->{$confirmAttribute}"
          />
        @endif

        {{ $headerSlot ?? '' }}
      </div>
    @endif

    <h2 class="detail-card__title">{{ $title }}</h2>
  </div>
  
  <div class="detail-card__body">
    {{ $slot }}
  </div>
</div>