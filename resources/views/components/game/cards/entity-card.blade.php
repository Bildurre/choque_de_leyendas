@props([
  'borderColor' => null,
  'showRoute' => null,
  'editRoute' => null,
  'deleteRoute' => null,
  'deleteConfirmAttribute' => 'entity-name',
  'deleteConfirmValue' => '',
  'containerClass' => '',
  'title' => '',
  'hasDetails' => false
])

<div {{ $attributes->merge(['class' => 'entity-card ' . $containerClass]) }} 
     @if($borderColor) style="border-left: 4px solid {{ $borderColor }}; box-shadow: inset 0 0 4px {{ $borderColor }}80;" @endif>
  
  <div class="entity-card__header">
    <div class="entity-card__actions">
      @if($showRoute)
        <x-action-button 
          variant="view" 
          :route="$showRoute"
          icon="view" 
        />
      @endif
      
      @if($editRoute)
        <x-action-button 
          variant="edit" 
          :route="$editRoute"
          icon="edit" 
        />
      @endif

      @if($deleteRoute)
        <x-action-button 
          variant="delete" 
          :route="$deleteRoute"
          method="DELETE" 
          icon="delete" 
          confirm="true" 
          :confirmAttribute="$deleteConfirmAttribute" 
          :confirmValue="$deleteConfirmValue" 
        />
      @endif
      
      @if($hasDetails)
        <x-action-button 
        variant="toggle"
        icon="expand"
        data-toggle="entity-details"
        />
      @endif
    </div>

    <div class="entity-card__title">
      @if(isset($badge) && !empty($badge))
        {{ $badge }}
      @endif
      <h2>{{ $title }}</h2>
    </div>
  </div>

  <div class="entity-card__content">
    {{ $slot }}
    
    @if($hasDetails)
      <div class="entity-card__details">
        {{ $details ?? '' }}
      </div>
    @endif
  </div>
</div>