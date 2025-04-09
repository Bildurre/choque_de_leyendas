@props([
  'borderColor' => null,
  'showRoute' => null,
  'editRoute' => null,
  'deleteRoute' => null,
  'deleteConfirmAttribute' => 'entity-name',
  'deleteConfirmValue' => '',
  'containerClass' => '',
  'contentClass' => '',
  'title' => '',
  'hasDetails' => false
])

<div {{ $attributes->merge(['class' => 'entity-card ' . $containerClass]) }} 
     @if($borderColor) style="border-left: 4px solid {{ $borderColor }}; box-shadow: inset 0 0 10px {{ $borderColor }}20;" @endif>
  
  <div class="entity-card__header">
    <div class="entity-card__title">
      {{ $badge ?? '' }}
      <h3>{{ $title }}</h3>
    </div>
    
    <div class="entity-card__actions">
      @if(isset($actions))
        {{ $actions }}
      @endif

      @if($showRoute)
        <x-common.action-button 
          type="view" 
          :route="$showRoute" 
          title="Ver detalles" 
          icon="expand" 
        />
      @endif
      
      @if($editRoute)
        <x-common.action-button 
          type="edit" 
          :route="$editRoute" 
          title="Editar" 
          icon="edit" 
        />
      @endif

      @if($deleteRoute)
        <x-common.action-button 
          type="delete" 
          :route="$deleteRoute" 
          title="Eliminar" 
          method="DELETE" 
          icon="delete" 
          confirm="true" 
          :confirmAttribute="$deleteConfirmAttribute" 
          :confirmValue="$deleteConfirmValue" 
        />
      @endif
      
      @if($hasDetails)
        <button type="button" class="action-btn toggle-btn" title="Ver más" data-toggle="entity-details">
          <x-common.icon name="expand" class="chevron-down" />
          <x-common.icon name="collapse" class="chevron-up" />
        </button>
      @endif
    </div>
  </div>

  <div class="entity-card__content {{ $contentClass }}">
    {{ $slot }}
    
    @if($hasDetails)
      <div class="entity-card__details">
        {{ $details ?? '' }}
      </div>
    @endif
  </div>
</div>