@props([
  'heroClass',
  'editRoute' => null,
  'deleteRoute' => null
])

<div class="hero-class-card">
  <div class="hero-class-header">
    <h3 class="hero-class-name">{{ $heroClass->name }}</h3>
    <span class="hero-class-superclass">
      {{ $heroClass->superclass ? $heroClass->superclass->name : 'Sin superclase' }}
    </span>
  </div>
  
  <div class="hero-class-modifiers">
    <h4>Modificadores de Atributos</h4>
    <x-game.attribute-modifiers-grid :modifiers="[
      'Agilidad' => $heroClass->agility_modifier,
      'Mental' => $heroClass->mental_modifier,
      'Voluntad' => $heroClass->will_modifier,
      'Fuerza' => $heroClass->strength_modifier,
      'Armadura' => $heroClass->armor_modifier
    ]" />
  </div>
  
  @if($heroClass->passive)
    <div class="hero-class-passive">
      <h4>Pasiva</h4>
      <p>{{ $heroClass->passive }}</p>
    </div>
  @endif
  
  <div class="hero-class-actions">
    @if($editRoute)
      <a href="{{ $editRoute }}" class="action-btn edit-btn" title="Editar">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.85 0 1 1 4 4L7.5 20.5 4 23l-1-3.5L17 3z"></path></svg>
      </a>
    @endif
    
    @if($deleteRoute)
      <form action="{{ $deleteRoute }}" method="POST" class="delete-form">
        @csrf
        @method('DELETE')
        <button type="submit" class="action-btn delete-btn" title="Eliminar" data-hero-class-name="{{ $heroClass->name }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>
        </button>
      </form>
    @endif
    
    {{ $actions ?? '' }}
  </div>
</div>