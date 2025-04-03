@extends('layouts.admin')

@section('title', 'Clases de Héroes')

@section('header-title', 'Gestión de Clases')

@section('content')
<div class="hero-classes-container">
  <div class="header-actions-bar">
    <div class="left-actions">
      <h1>Clases de Héroes</h1>
      <p>Gestión de clases para la creación de héroes</p>
    </div>
    <div class="right-actions">
      <a href="{{ route('admin.hero-classes.create') }}" class="btn btn-primary">
        <span>+ Nueva Clase</span>
      </a>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  <div class="hero-classes-grid">
    @forelse($heroClasses as $heroClass)
      <div class="hero-class-card">
        <div class="hero-class-header">
          <h3 class="hero-class-name">{{ $heroClass->name }}</h3>
          <span class="hero-class-superclass">
            {{ $heroClass->superclass ? $heroClass->superclass->name : 'Sin superclase' }}
          </span>
        </div>
        
        <div class="hero-class-modifiers">
          <h4>Modificadores de Atributos</h4>
          <div class="modifiers-grid">
            @php
              $modifiers = [
                'Agilidad' => $heroClass->agility_modifier,
                'Mental' => $heroClass->mental_modifier,
                'Voluntad' => $heroClass->will_modifier,
                'Fuerza' => $heroClass->strength_modifier,
                'Armadura' => $heroClass->armor_modifier
              ];
            @endphp
            
            @foreach($modifiers as $label => $modifier)
              <div class="modifier-item {{ $modifier > 0 ? 'positive' : ($modifier < 0 ? 'negative' : 'neutral') }}">
                <span class="modifier-label">{{ $label }}</span>
                <span class="modifier-value">
                  {{ $modifier > 0 ? '+' : '' }}{{ $modifier }}
                </span>
              </div>
            @endforeach
          </div>
        </div>
        
        @if($heroClass->passive)
          <div class="hero-class-passive">
            <h4>Pasiva</h4>
            <p>{{ $heroClass->passive }}</p>
          </div>
        @endif
        
        <div class="hero-class-actions">
          <a href="{{ route('admin.hero-classes.edit', $heroClass) }}" class="action-btn edit-btn" title="Editar">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.85 0 1 1 4 4L7.5 20.5 4 23l-1-3.5L17 3z"></path></svg>
          </a>
          <form action="{{ route('admin.hero-classes.destroy', $heroClass) }}" method="POST" class="delete-form">
            @csrf
            @method('DELETE')
            <button type="submit" class="action-btn delete-btn" title="Eliminar" data-hero-class-name="{{ $heroClass->name }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>
            </button>
          </form>
        </div>
      </div>
    @empty
      <div class="no-hero-classes">
        <p>No hay clases de héroes disponibles</p>
        <a href="{{ route('admin.hero-classes.create') }}" class="btn btn-primary">Crear primera clase</a>
      </div>
    @endforelse
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Manejo de la confirmación para eliminar clase
  const deleteButtons = document.querySelectorAll('.delete-btn');
  
  deleteButtons.forEach(button => {
    button.addEventListener('click', function(event) {
      event.preventDefault();
      
      const heroClassName = this.getAttribute('data-hero-class-name');
      
      if (confirm(`¿Estás seguro de querer eliminar la clase "${heroClassName}"?`)) {
        // Si el usuario confirma, enviamos el formulario
        this.closest('form').submit();
      }
    });
  });
  
  // Ocultar alertas después de 5 segundos
  const alerts = document.querySelectorAll('.alert');
  
  alerts.forEach(alert => {
    setTimeout(() => {
      alert.style.opacity = '0';
      setTimeout(() => {
        alert.style.display = 'none';
      }, 300);
    }, 5000);
  });
});
</script>
@endpush