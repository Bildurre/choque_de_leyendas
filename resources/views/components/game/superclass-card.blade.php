@props([
  'superclass',
  'editRoute' => null,
  'deleteRoute' => null
])

<div class="superclass-card">
  <div class="superclass-content">
    <h3 class="superclass-name">{{ $superclass->name }}</h3>
    
    @if($superclass->description)
      <p class="superclass-description">{{ $superclass->description }}</p>
    @endif
    
    <div class="superclass-stats">
      <span class="hero-count">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        {{ $superclass->hero_classes_count }} {{ Str::plural('clase', $superclass->hero_classes_count) }}
      </span>
    </div>
  </div>
  
  <div class="superclass-actions">
    @if($editRoute)
      <a href="{{ $editRoute }}" class="action-btn edit-btn" title="Editar">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.85 0 1 1 4 4L7.5 20.5 4 23l-1-3.5L17 3z"></path></svg>
      </a>
    @endif
    
    @if($deleteRoute)
      <form action="{{ $deleteRoute }}" method="POST" class="delete-form">
        @csrf
        @method('DELETE')
        <button type="submit" class="action-btn delete-btn" title="Eliminar" data-superclass-id="{{ $superclass->id }}" data-superclass-name="{{ $superclass->name }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>
        </button>
      </form>
    @endif
  </div>
</div>