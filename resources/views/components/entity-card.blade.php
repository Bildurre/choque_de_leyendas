@props([
  'borderColor' => null,
  'editRoute' => null,
  'deleteRoute' => null,
  'deleteConfirmMessage' => '¿Estás seguro de querer eliminar este elemento?',
  'deleteConfirmDataAttribute' => '',
  'deleteConfirmValue' => ''
])

<div class="entity-card" @if($borderColor) style="border-left: 4px solid {{ $borderColor }}" @endif>
  <div class="entity-content">
    {{ $content }}
  </div>
  
  @if($editRoute || $deleteRoute)
    <div class="entity-actions">
      @if($editRoute)
        <a href="{{ $editRoute }}" class="action-btn edit-btn" title="Editar">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.85 0 1 1 4 4L7.5 20.5 4 23l-1-3.5L17 3z"></path></svg>
        </a>
      @endif
      
      @if($deleteRoute)
        <form action="{{ $deleteRoute }}" method="POST" class="delete-form">
          @csrf
          @method('DELETE')
          <button type="submit" class="action-btn delete-btn" title="Eliminar" data-{{ $deleteConfirmDataAttribute }}="{{ $deleteConfirmValue }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>
          </button>
        </form>
      @endif
      
      {{ $actions ?? '' }}
    </div>
  @endif
</div>