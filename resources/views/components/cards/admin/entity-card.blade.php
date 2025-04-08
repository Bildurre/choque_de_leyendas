@props([
  'borderColor' => null,
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
      
      @if($editRoute)
        <a href="{{ $editRoute }}" class="action-btn edit-btn" title="Editar">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.85 0 1 1 4 4L7.5 20.5 4 23l-1-3.5L17 3z"></path></svg>
        </a>
      @endif
      
      @if($deleteRoute)
        <form action="{{ $deleteRoute }}" method="POST" class="delete-form">
          @csrf
          @method('DELETE')
          <button type="submit" class="action-btn delete-btn" title="Eliminar" data-{{ $deleteConfirmAttribute }}="{{ $deleteConfirmValue }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>
          </button>
        </form>
      @endif
      
      @if($hasDetails)
        <button type="button" class="action-btn toggle-btn" title="Ver más" data-toggle="entity-details">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="chevron-up"><polyline points="18 15 12 9 6 15"></polyline></svg>
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